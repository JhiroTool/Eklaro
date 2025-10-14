<?php
$pageTitle = 'Dashboard - Eklaro';
require_once 'includes/header.php';
require_once 'includes/Database.php';

use Eklaro\Database;

// Require login
$auth->requireLogin();

$db = Database::getInstance();
$userId = $currentUser['id'];

// Get user statistics
$stmt = $db->prepare("SELECT COUNT(*) as total FROM articles WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$totalArticles = $stmt->get_result()->fetch_assoc()['total'];

$stmt = $db->prepare("SELECT AVG(credibility_score) as avg_score FROM articles WHERE user_id = ? AND credibility_score IS NOT NULL");
$stmt->bind_param("i", $userId);
$stmt->execute();
$avgScore = $stmt->get_result()->fetch_assoc()['avg_score'] ?? 0;

$stmt = $db->prepare("SELECT COUNT(*) as valid_count FROM articles WHERE user_id = ? AND credibility_score >= ?");
$validThreshold = SCORE_VALID_MIN;
$stmt->bind_param("id", $userId, $validThreshold);
$stmt->execute();
$validCount = $stmt->get_result()->fetch_assoc()['valid_count'];

$stmt = $db->prepare("SELECT COUNT(*) as invalid_count FROM articles WHERE user_id = ? AND credibility_score < ?");
$partialThreshold = SCORE_PARTIAL_MIN;
$stmt->bind_param("id", $userId, $partialThreshold);
$stmt->execute();
$invalidCount = $stmt->get_result()->fetch_assoc()['invalid_count'];

// Get recent articles
$stmt = $db->prepare(
    "SELECT id, title, credibility_score, validation_status, created_at 
     FROM articles 
     WHERE user_id = ? 
     ORDER BY created_at DESC 
     LIMIT 10"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$recentArticles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-5">
        <div class="d-flex align-items-center mb-3">
            <div class="me-3">
                <i data-lucide="layout-dashboard" class="icon-lg" style="color: var(--primary-color);"></i>
            </div>
            <div>
                <h1 class="display-5 fw-bold mb-2" style="color: var(--primary-color);">Dashboard</h1>
                <p class="text-muted fs-5 mb-0">Welcome back, <strong><?php echo htmlspecialchars($currentUser['name']); ?></strong>!</p>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3">
            <div class="stats-card" style="background: var(--gradient-1);">
                <i data-lucide="file-check" class="icon-md mb-3"></i>
                <div class="stats-number"><?php echo $totalArticles; ?></div>
                <div class="stats-label">Total Articles Validated</div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="stats-card" style="background: var(--gradient-2);">
                <i data-lucide="trending-up" class="icon-md mb-3"></i>
                <div class="stats-number"><?php echo round($avgScore, 1); ?>%</div>
                <div class="stats-label">Average Credibility Score</div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="stats-card" style="background: var(--gradient-3);">
                <i data-lucide="check-circle" class="icon-md mb-3"></i>
                <div class="stats-number"><?php echo $validCount; ?></div>
                <div class="stats-label">Valid Articles</div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="stats-card" style="background: var(--gradient-5);">
                <i data-lucide="alert-circle" class="icon-md mb-3"></i>
                <div class="stats-number"><?php echo $invalidCount; ?></div>
                <div class="stats-label">Invalid Articles</div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-lg border-0 glass-effect">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">
                        <i data-lucide="zap" class="icon-sm" style="color: var(--primary-color);"></i>
                        Quick Actions
                    </h5>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="<?php echo APP_URL; ?>/validate" class="btn btn-primary btn-lg">
                            <i data-lucide="file-search" class="icon-sm"></i> Validate New Article
                        </a>
                        <a href="<?php echo APP_URL; ?>/profile" class="btn btn-outline-primary btn-lg">
                            <i data-lucide="user" class="icon-sm"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Articles -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-0 fw-bold">
                        <i data-lucide="clock" class="icon-sm" style="color: var(--primary-color);"></i>
                        Recent Validations
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recentArticles)): ?>
                    <div class="p-5 text-center text-muted">
                        <i data-lucide="inbox" class="icon-xl mb-4" style="color: var(--primary-color); opacity: 0.3;"></i>
                        <h5 class="fw-bold mb-3">No articles validated yet</h5>
                        <p class="mb-4">Start your first validation to see results here.</p>
                        <a href="<?php echo APP_URL; ?>/validate" class="btn btn-primary">Start Validating Now!</a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                <tr>
                                    <th class="border-0 py-3">Title</th>
                                    <th class="border-0 py-3">Score</th>
                                    <th class="border-0 py-3">Status</th>
                                    <th class="border-0 py-3">Date</th>
                                    <th class="border-0 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentArticles as $article): ?>
                                <tr class="align-middle">
                                    <td class="py-3">
                                        <div class="text-truncate fw-semibold" style="max-width: 350px;">
                                            <i data-lucide="file-text" class="icon-sm me-2" style="color: var(--primary-color);"></i>
                                            <?php echo htmlspecialchars($article['title']); ?>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <?php if ($article['credibility_score']): ?>
                                            <?php
                                            $scoreClass = 'danger';
                                            if ($article['credibility_score'] >= SCORE_VALID_MIN) {
                                                $scoreClass = 'success';
                                            } elseif ($article['credibility_score'] >= SCORE_PARTIAL_MIN) {
                                                $scoreClass = 'warning';
                                            }
                                            ?>
                                            <span class="badge bg-<?php echo $scoreClass; ?> px-3 py-2 fs-6">
                                                <?php echo round($article['credibility_score']); ?>%
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3">
                                        <?php
                                        $statusBadge = [
                                            'pending' => 'secondary',
                                            'processing' => 'info',
                                            'completed' => 'success',
                                            'failed' => 'danger'
                                        ];
                                        $badgeClass = $statusBadge[$article['validation_status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $badgeClass; ?> px-3 py-2">
                                            <?php echo ucfirst($article['validation_status']); ?>
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <small class="text-muted">
                                            <i data-lucide="calendar" class="icon-sm me-1"></i>
                                            <?php echo date('M d, Y H:i', strtotime($article['created_at'])); ?>
                                        </small>
                                    </td>
                                    <td class="py-3">
                                        <?php if ($article['validation_status'] === 'completed'): ?>
                                        <a href="<?php echo APP_URL; ?>/results?id=<?php echo $article['id']; ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i data-lucide="eye" class="icon-sm"></i> View Results
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
