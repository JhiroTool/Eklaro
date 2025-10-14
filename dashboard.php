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
        <h1>
            <i data-lucide="layout-dashboard" class="icon-md"></i>
            Dashboard
        </h1>
        <p class="text-muted">Welcome back, <?php echo htmlspecialchars($currentUser['name']); ?>!</p>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3">
            <div class="stats-card">
                <i data-lucide="file-check" class="icon-md mb-2"></i>
                <div class="stats-number"><?php echo $totalArticles; ?></div>
                <div class="stats-label">Total Articles Validated</div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i data-lucide="trending-up" class="icon-md mb-2"></i>
                <div class="stats-number"><?php echo round($avgScore, 1); ?>%</div>
                <div class="stats-label">Average Credibility Score</div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i data-lucide="check-circle" class="icon-md mb-2"></i>
                <div class="stats-number"><?php echo $validCount; ?></div>
                <div class="stats-label">Valid Articles</div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <i data-lucide="alert-circle" class="icon-md mb-2"></i>
                <div class="stats-number"><?php echo $invalidCount; ?></div>
                <div class="stats-label">Invalid Articles</div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i data-lucide="zap" class="icon-sm"></i>
                        Quick Actions
                    </h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?php echo APP_URL; ?>/validate" class="btn btn-primary">
                            <i data-lucide="file-search" class="icon-sm"></i> Validate New Article
                        </a>
                        <a href="<?php echo APP_URL; ?>/profile" class="btn btn-outline-primary">
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
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i data-lucide="clock" class="icon-sm"></i>
                        Recent Validations
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recentArticles)): ?>
                    <div class="p-4 text-center text-muted">
                        <i data-lucide="inbox" class="icon-xl mb-3"></i>
                        <p>No articles validated yet. <a href="<?php echo APP_URL; ?>/validate">Start validating now!</a></p>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Score</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentArticles as $article): ?>
                                <tr>
                                    <td>
                                        <div class="text-truncate" style="max-width: 300px;">
                                            <?php echo htmlspecialchars($article['title']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($article['credibility_score']): ?>
                                            <?php
                                            $scoreClass = 'danger';
                                            if ($article['credibility_score'] >= SCORE_VALID_MIN) {
                                                $scoreClass = 'success';
                                            } elseif ($article['credibility_score'] >= SCORE_PARTIAL_MIN) {
                                                $scoreClass = 'warning';
                                            }
                                            ?>
                                            <span class="badge bg-<?php echo $scoreClass; ?>">
                                                <?php echo round($article['credibility_score']); ?>%
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusBadge = [
                                            'pending' => 'secondary',
                                            'processing' => 'info',
                                            'completed' => 'success',
                                            'failed' => 'danger'
                                        ];
                                        $badgeClass = $statusBadge[$article['validation_status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $badgeClass; ?>">
                                            <?php echo ucfirst($article['validation_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('M d, Y H:i', strtotime($article['created_at'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if ($article['validation_status'] === 'completed'): ?>
                                        <a href="<?php echo APP_URL; ?>/results?id=<?php echo $article['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i data-lucide="eye" class="icon-sm"></i> View
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
