<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/Auth.php';
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
            if (!isset($_FILES['article_file']) || $_FILES['article_file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('File upload failed');
            }
            
            $file = $_FILES['article_file'];
            
            // Validate file type
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($fileExt, ALLOWED_FILE_TYPES)) {
                throw new Exception('Only .txt files are allowed');
            }
            
            // Validate file size
            if ($file['size'] > MAX_FILE_SIZE) {
                throw new Exception('File size must not exceed ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB');
            }
            
            // Read file content
            $content = file_get_contents($file['tmp_name']);
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
        CURLOPT_USERAGENT => 'Eklaro/1.0'
    ]);
    
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        throw new Exception('Failed to fetch article from URL');
    }
    
    // Simple HTML to text conversion
    $text = strip_tags($html);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);
    
    if (strlen($text) < MIN_ARTICLE_LENGTH) {
        throw new Exception('Could not extract sufficient content from URL');
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
