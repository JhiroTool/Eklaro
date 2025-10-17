<?php
$pageTitle = 'Validation Results - Eklaro';
require_once 'config/config.php';
require_once 'includes/Database.php';
require_once 'includes/Auth.php';
require_once 'includes/NLPAnalyzer.php';
require_once 'includes/FactCheckAPI.php';
require_once 'includes/ArticleValidator.php';

use Eklaro\ArticleValidator;
use Eklaro\Auth;

$auth = new Auth();
$auth->requireLogin();

// Get article ID
$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($articleId === 0) {
    header('Location: ' . APP_URL . '/validate');
    exit;
}

// Get validation results
$validator = new ArticleValidator();
$result = $validator->getValidationResult($articleId);
if ($result) {
    $isAdmin = $auth->isAdmin();
    $currentUser = $auth->getCurrentUser();
    $ownsArticle = $currentUser && $result['user_id'] && $currentUser['id'] === (int)$result['user_id'];

    if (!$isAdmin && !$ownsArticle) {
        header('Location: ' . APP_URL . '/403');
        exit;
    }
}

require_once 'includes/header.php';

if (!$result) {
    echo '<div class="container py-5"><div class="alert alert-danger">Article not found or validation pending.</div></div>';
    require_once 'includes/footer.php';
    exit;
}

// Determine score class
$scoreClass = 'invalid';
if ($result['credibility_score'] >= SCORE_VALID_MIN) {
    $scoreClass = 'valid';
} elseif ($result['credibility_score'] >= SCORE_PARTIAL_MIN) {
    $scoreClass = 'partial';
}
?>

<div class="container py-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <i data-lucide="file-check" class="icon-xl text-primary mb-3"></i>
        <h1>Validation Results</h1>
        <p class="text-muted"><?php echo htmlspecialchars($result['title']); ?></p>
    </div>
    
    <div class="row">
        <!-- Credibility Score -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm sticky-top" style="top: 100px;">
                <div class="card-body text-center">
                    <h5 class="card-title mb-4">Credibility Score</h5>
                    
                    <div class="score-circle <?php echo $scoreClass; ?> mb-3">
                        <?php echo round($result['credibility_score']); ?>
                    </div>
                    
                    <div class="credibility-badge <?php echo $scoreClass; ?> mb-3">
                        <?php echo $result['credibility_label']; ?>
                    </div>
                    
                    <hr>
                    
                    <div class="text-start">
                        <h6 class="mb-3">Quick Stats</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i data-lucide="alert-triangle" class="icon-sm text-warning"></i>
                                <strong><?php echo count($result['suspicious_claims']); ?></strong> Suspicious Claims
                            </li>
                            <li class="mb-2">
                                <i data-lucide="check-circle" class="icon-sm text-success"></i>
                                <strong><?php echo count($result['fact_check_sources']); ?></strong> Fact-Check Matches
                            </li>
                            <li class="mb-2">
                                <i data-lucide="file-text" class="icon-sm text-info"></i>
                                <strong><?php echo $result['linguistic_features']['word_count']; ?></strong> Words
                            </li>
                        </ul>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                            <i data-lucide="printer" class="icon-sm"></i> Print Report
                        </button>
                        <a href="<?php echo APP_URL; ?>/validate" class="btn btn-primary btn-sm">
                            <i data-lucide="file-search" class="icon-sm"></i> Validate Another
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detailed Analysis -->
        <div class="col-lg-8">
            <!-- Explanation -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i data-lucide="info" class="icon-sm"></i>
                        Analysis Explanation
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space: pre-line;"><?php echo htmlspecialchars($result['explanation']); ?></p>
                </div>
            </div>
            
            <!-- Improvement Recommendations -->
            <?php
            $credibilityScore = $result['credibility_score'];
            $readabilityScore = $result['nlp_analysis']['readability_score'];
            $wordCount = $result['linguistic_features']['word_count'];
            $sentenceCount = $result['linguistic_features']['sentence_count'];
            $avgSentenceLength = $result['linguistic_features']['avg_sentence_length'];
            $suspiciousCount = count($result['suspicious_claims']);
            
            // Generate recommendations
            $recommendations = [];
            
            // Credibility recommendations
            if ($credibilityScore < 70) {
                $recommendations[] = [
                    'icon' => 'shield-check',
                    'title' => 'Boost Credibility',
                    'color' => 'warning',
                    'tips' => [
                        '<strong>Add Verified Citations:</strong> Reference peer-reviewed journals, government reports, or reputable organizations (e.g., WHO, UN, academic databases).',
                        '<strong>Use Specific Data:</strong> Replace general claims with statistics or findings from studies.',
                        '<strong>Clarify Source Attribution:</strong> Ensure every claim is traceable to a source using APA or IEEE format.'
                    ]
                ];
            }
            
            // Readability recommendations
            if ($readabilityScore < 50) {
                $recommendations[] = [
                    'icon' => 'book-open',
                    'title' => 'Improve Readability',
                    'color' => 'info',
                    'tips' => [
                        '<strong>Shorten Sentences:</strong> Aim for 12–15 words per sentence. Break complex ideas into digestible parts.',
                        '<strong>Simplify Vocabulary:</strong> Use precise but accessible language (e.g., "improve" instead of "ameliorate").',
                        '<strong>Use Active Voice:</strong> Change "The system was developed" to "We developed the system".',
                        '<strong>Add Transitions:</strong> Use connectors like "however," "in contrast," "as a result" to guide readers.'
                    ]
                ];
            }
            
            // Linguistic structure recommendations
            if ($avgSentenceLength < 10 || $avgSentenceLength > 25) {
                $recommendations[] = [
                    'icon' => 'type',
                    'title' => 'Refine Linguistic Structure',
                    'color' => 'success',
                    'tips' => [
                        '<strong>Sentence Length:</strong> Your average is ' . round($avgSentenceLength, 1) . ' words/sentence. ' . 
                        ($avgSentenceLength < 10 ? 'Combine related short sentences to improve flow.' : 'Break down longer sentences for clarity.'),
                        '<strong>Vary Structure:</strong> Mix simple, compound, and complex sentence forms for better rhythm.',
                        '<strong>Use Topic Sentences:</strong> Start each paragraph with a clear main idea to anchor your points.'
                    ]
                ];
            }
            
            // Suspicious claims recommendations
            if ($suspiciousCount > 5) {
                $recommendations[] = [
                    'icon' => 'alert-triangle',
                    'title' => 'Address Suspicious Claims',
                    'color' => 'danger',
                    'tips' => [
                        '<strong>Review Flagged Content:</strong> You have ' . $suspiciousCount . ' suspicious claims detected. Review each one carefully.',
                        '<strong>Provide Evidence:</strong> Support claims with credible sources and data.',
                        '<strong>Avoid Exaggeration:</strong> Replace absolute terms like "always," "never," "everyone" with more measured language.',
                        '<strong>Be Objective:</strong> Remove emotional or sensational language that may undermine credibility.'
                    ]
                ];
            }
            ?>
            
            <?php if (!empty($recommendations)): ?>
            <div class="card shadow-sm mb-4 border-start border-4 border-primary">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-primary">
                        <i data-lucide="lightbulb" class="icon-sm"></i>
                        💡 Recommendations to Improve Your Article
                    </h5>
                </div>
                <div class="card-body">
                    <?php foreach ($recommendations as $index => $rec): ?>
                    <div class="mb-4 <?php echo $index < count($recommendations) - 1 ? 'pb-4 border-bottom' : ''; ?>">
                        <h6 class="text-<?php echo $rec['color']; ?> mb-3">
                            <i data-lucide="<?php echo $rec['icon']; ?>" class="icon-sm"></i>
                            <?php echo $rec['title']; ?>
                        </h6>
                        <ul class="mb-0">
                            <?php foreach ($rec['tips'] as $tip): ?>
                            <li class="mb-2"><?php echo $tip; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="alert alert-info mb-0 mt-4">
                        <i data-lucide="info" class="icon-sm"></i>
                        <strong>Pro Tip:</strong> After making improvements, validate your article again to see your updated credibility score!
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Suspicious Claims -->
            <?php if (!empty($result['suspicious_claims'])): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i data-lucide="alert-triangle" class="icon-sm"></i>
                        Suspicious Claims Detected
                    </h5>
                </div>
                <div class="card-body">
                    <?php foreach ($result['suspicious_claims'] as $claim): ?>
                    <div class="suspicious-claim <?php echo $claim['claim_type']; ?>">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <strong class="text-capitalize"><?php echo str_replace('_', ' ', $claim['claim_type']); ?></strong>
                            <span class="badge bg-secondary"><?php echo round($claim['confidence_score']); ?>% confidence</span>
                        </div>
                        <p class="mb-0 small"><?php 
                            // Clean the claim text - remove URLs and excessive whitespace
                            $claimText = $claim['claim_text'];
                            $claimText = preg_replace('/https?:\/\/[^\s]+/', '', $claimText); // Remove URLs
                            $claimText = preg_replace('/\s+/', ' ', $claimText); // Clean whitespace
                            $claimText = trim($claimText);
                            // Limit length
                            if (strlen($claimText) > 200) {
                                $claimText = substr($claimText, 0, 200) . '...';
                            }
                            echo htmlspecialchars($claimText); 
                        ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Fact-Check Matches -->
            <?php if (!empty($result['fact_check_sources'])): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i data-lucide="search-check" class="icon-sm"></i>
                        Fact-Check Matches
                    </h5>
                </div>
                <div class="card-body">
                    <?php foreach ($result['fact_check_sources'] as $source): ?>
                    <div class="border-start border-4 border-success ps-3 mb-3">
                        <h6 class="mb-2"><?php echo htmlspecialchars($source['source_name']); ?></h6>
                        <p class="mb-2 small"><strong>Claim:</strong> <?php echo htmlspecialchars($source['claim']); ?></p>
                        <p class="mb-2 small">
                            <strong>Rating:</strong> 
                            <span class="badge bg-info"><?php echo htmlspecialchars($source['rating']); ?></span>
                        </p>
                        <p class="mb-2 small"><strong>Publisher:</strong> <?php echo htmlspecialchars($source['publisher']); ?></p>
                        <a href="<?php echo htmlspecialchars($source['source_url']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i data-lucide="external-link" class="icon-sm"></i> View Source
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Linguistic Features -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i data-lucide="bar-chart" class="icon-sm"></i>
                        Linguistic Analysis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted">Word Count</small>
                                <h4 class="mb-0"><?php echo $result['linguistic_features']['word_count']; ?></h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted">Sentence Count</small>
                                <h4 class="mb-0"><?php echo $result['linguistic_features']['sentence_count']; ?></h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted">Avg Sentence Length</small>
                                <h4 class="mb-0"><?php echo round($result['linguistic_features']['avg_sentence_length'], 1); ?></h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted">Readability Score</small>
                                <h4 class="mb-0"><?php echo round($result['nlp_analysis']['readability_score'], 1); ?>/100</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Article Content -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i data-lucide="file-text" class="icon-sm"></i>
                        Article Content
                    </h5>
                </div>
                <div class="card-body">
                    <p style="white-space: pre-wrap; word-wrap: break-word;"><?php 
                        // Clean and format the content
                        $content = $result['content'];
                        // Remove excessive whitespace
                        $content = preg_replace('/\s+/', ' ', $content);
                        // Limit length for display
                        if (strlen($content) > 5000) {
                            $content = substr($content, 0, 5000) . '...';
                        }
                        echo htmlspecialchars($content); 
                    ?></p>
                    <?php if ($result['source_url']): ?>
                    <hr>
                    <p class="mb-0">
                        <strong>Source:</strong> 
                        <a href="<?php echo htmlspecialchars($result['source_url']); ?>" target="_blank">
                            <?php echo htmlspecialchars($result['source_url']); ?>
                        </a>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
