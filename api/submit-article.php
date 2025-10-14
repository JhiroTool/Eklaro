<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/Auth.php';
require_once __DIR__ . '/../includes/NLPAnalyzer.php';
require_once __DIR__ . '/../includes/FactCheckAPI.php';
require_once __DIR__ . '/../includes/ArticleValidator.php';

use Eklaro\Database;
use Eklaro\Auth;
use Eklaro\ArticleValidator;

// Start session
session_start();

$auth = new Auth();
$db = Database::getInstance();

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !$auth->validateCSRFToken($_POST['csrf_token'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

// Get submission type
$submissionType = $_POST['submission_type'] ?? '';
$title = '';
$content = '';
$sourceUrl = null;

try {
    switch ($submissionType) {
        case 'text':
            $title = trim($_POST['article_title'] ?? 'Untitled Article');
            $content = trim($_POST['article_text'] ?? '');
            
            if (empty($content)) {
                throw new Exception('Article content is required');
            }
            
            if (strlen($content) < MIN_ARTICLE_LENGTH) {
                throw new Exception('Article must be at least ' . MIN_ARTICLE_LENGTH . ' characters');
            }
            
            if (strlen($content) > MAX_ARTICLE_LENGTH) {
                throw new Exception('Article must not exceed ' . MAX_ARTICLE_LENGTH . ' characters');
            }
            break;
            
        case 'url':
            $sourceUrl = trim($_POST['article_url'] ?? '');
            
            if (empty($sourceUrl)) {
                throw new Exception('Article URL is required');
            }
            
            if (!filter_var($sourceUrl, FILTER_VALIDATE_URL)) {
                throw new Exception('Invalid URL format');
            }
            
            // Fetch content from URL
            $content = fetchArticleFromUrl($sourceUrl);
            $title = extractTitleFromContent($content);
            break;
            
        case 'file':
            if (!isset($_FILES['article_file'])) {
                throw new Exception('No file was uploaded');
            }
            
            $uploadError = $_FILES['article_file']['error'];
            if ($uploadError !== UPLOAD_ERR_OK) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize in php.ini',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in form',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
                ];
                $errorMsg = $errorMessages[$uploadError] ?? 'Unknown upload error';
                throw new Exception('File upload failed: ' . $errorMsg);
            }
            
            $file = $_FILES['article_file'];
            
            // Validate file type
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['txt', 'pdf', 'doc', 'docx'];
            if (!in_array($fileExt, $allowedTypes)) {
                throw new Exception('Only .txt, .pdf, .doc, and .docx files are allowed');
            }
            
            // Validate file size
            if ($file['size'] > MAX_FILE_SIZE) {
                throw new Exception('File size must not exceed ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB');
            }
            
            // Extract text based on file type
            $content = extractTextFromFile($file['tmp_name'], $fileExt);
            $title = pathinfo($file['name'], PATHINFO_FILENAME);
            
            if (strlen($content) < MIN_ARTICLE_LENGTH) {
                throw new Exception('Article must be at least ' . MIN_ARTICLE_LENGTH . ' characters');
            }
            break;
            
        default:
            throw new Exception('Invalid submission type');
    }
    
    // Get user ID if logged in
    $userId = $auth->isLoggedIn() ? $auth->getCurrentUser()['id'] : null;
    
    // Insert article into database
    $stmt = $db->prepare(
        "INSERT INTO articles (user_id, title, content, source_url, submission_type, validation_status) 
         VALUES (?, ?, ?, ?, ?, 'pending')"
    );
    
    $stmt->bind_param("issss", $userId, $title, $content, $sourceUrl, $submissionType);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to save article');
    }
    
    $articleId = $db->lastInsertId();
    
    // Validate article
    $validator = new ArticleValidator();
    $result = $validator->validateArticle($articleId);
    
    if (!$result['success']) {
        throw new Exception($result['message']);
    }
    
    echo json_encode([
        'success' => true,
        'article_id' => $articleId,
        'credibility_score' => $result['credibility_score'],
        'credibility_label' => $result['credibility_label']
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function fetchArticleFromUrl($url) {
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9',
            'Accept-Encoding: gzip, deflate',
            'Connection: keep-alive',
        ],
        CURLOPT_ENCODING => 'gzip, deflate'
    ]);
    
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($httpCode !== 200 || $html === false) {
        throw new Exception('Failed to fetch article from URL (HTTP ' . $httpCode . '). Try pasting the text directly instead.');
    }
    
    // Better HTML to text conversion
    // Remove script and style tags with their content
    $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
    $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);
    $html = preg_replace('/<head\b[^>]*>(.*?)<\/head>/is', '', $html);
    
    // Try to extract main content (common article containers)
    if (preg_match('/<article[^>]*>(.*?)<\/article>/is', $html, $matches)) {
        $html = $matches[1];
    } elseif (preg_match('/<main[^>]*>(.*?)<\/main>/is', $html, $matches)) {
        $html = $matches[1];
    } elseif (preg_match('/<div[^>]*class="[^"]*content[^"]*"[^>]*>(.*?)<\/div>/is', $html, $matches)) {
        $html = $matches[1];
    }
    
    // Convert to text
    $text = strip_tags($html);
    
    // Clean up whitespace
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);
    
    // Remove common navigation/footer text
    $text = preg_replace('/(Cookie Policy|Privacy Policy|Terms of Service|Subscribe|Sign Up|Log In|Menu|Navigation)/i', '', $text);
    
    if (strlen($text) < MIN_ARTICLE_LENGTH) {
        throw new Exception('Could not extract sufficient content from URL. The page may require JavaScript or have anti-scraping protection. Please copy and paste the article text instead.');
    }
    
    return $text;
}

function extractTitleFromContent($content) {
    // Extract first sentence or first 100 characters as title
    $sentences = preg_split('/[.!?]/', $content, 2);
    $title = trim($sentences[0]);
    
    if (strlen($title) > 100) {
        $title = substr($title, 0, 97) . '...';
    }
    
    return $title ?: 'Untitled Article';
}

function extractTextFromFile($filePath, $fileExt) {
    switch ($fileExt) {
        case 'txt':
            return file_get_contents($filePath);
            
        case 'pdf':
            // Try using pdftotext command if available
            if (function_exists('shell_exec')) {
                $output = shell_exec("pdftotext " . escapeshellarg($filePath) . " -");
                if ($output && strlen(trim($output)) > 0) {
                    return $output;
                }
            }
            
            // Fallback: Basic PDF text extraction using regex (limited)
            $content = file_get_contents($filePath);
            if (preg_match_all('/\((.*?)\)/s', $content, $matches)) {
                $text = implode(' ', $matches[1]);
                $text = preg_replace('/[^\x20-\x7E\s]/', '', $text); // Remove non-printable chars
                if (strlen(trim($text)) > 100) {
                    return $text;
                }
            }
            
            throw new Exception('Could not extract text from PDF. Please try converting to .txt or copying the text directly.');
            
        case 'doc':
        case 'docx':
            // Try using antiword for .doc or docx2txt for .docx
            if (function_exists('shell_exec')) {
                if ($fileExt === 'doc') {
                    $output = shell_exec("antiword " . escapeshellarg($filePath));
                } else {
                    $output = shell_exec("docx2txt " . escapeshellarg($filePath) . " -");
                }
                
                if ($output && strlen(trim($output)) > 0) {
                    return $output;
                }
            }
            
            throw new Exception('Could not extract text from ' . strtoupper($fileExt) . '. Please convert to .txt or copy the text directly. You may need to install text extraction tools on the server.');
            
        default:
            throw new Exception('Unsupported file type');
    }
}
