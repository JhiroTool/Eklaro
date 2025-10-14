<?php
$pageTitle = 'Validation Results - Eklaro';
require_once 'includes/header.php';
require_once 'includes/NLPAnalyzer.php';
require_once 'includes/FactCheckAPI.php';
require_once 'includes/ArticleValidator.php';

use Eklaro\ArticleValidator;

// Get article ID
$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($articleId === 0) {
    header('Location: ' . APP_URL . '/validate');
    exit;
}

// Get validation results
$validator = new ArticleValidator();
$result = $validator->getValidationResult($articleId);

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
