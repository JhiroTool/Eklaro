<?php
$pageTitle = 'Manage Articles - Admin - Eklaro';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/Auth.php';

use Eklaro\Database;
use Eklaro\Auth;

$auth = new Auth();
$auth->requireAdmin();

require_once __DIR__ . '/../includes/header.php';

$db = Database::getInstance();

// Get all articles
$articles = $db->query(
    "SELECT a.*, u.full_name, u.email 
     FROM articles a
     LEFT JOIN users u ON a.user_id = u.id
     ORDER BY a.created_at DESC
     LIMIT 100"
)->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-fluid py-5">
    <!-- Page Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i data-lucide="file-text" class="icon-md"></i>
                    Manage Articles
                </h1>
                <p class="text-muted">View all validated articles</p>
            </div>
            <a href="<?php echo APP_URL; ?>/admin" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left" class="icon-sm"></i> Back to Admin
            </a>
        </div>
    </div>
    
    <!-- Articles Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Articles (<?php echo count($articles); ?>)</h5>
                <input type="search" class="form-control w-auto" placeholder="Search articles..." id="searchArticles">
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Score</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article): ?>
                        <tr>
                            <td><?php echo $article['id']; ?></td>
                            <td>
                                <div class="text-truncate" style="max-width: 300px;">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($article['full_name']): ?>
                                    <small><?php echo htmlspecialchars($article['full_name']); ?></small>
                                <?php else: ?>
                                    <span class="text-muted">Guest</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?php echo ucfirst($article['submission_type']); ?></span>
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
                                    <?php echo date('M d, Y', strtotime($article['created_at'])); ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <?php if ($article['validation_status'] === 'completed'): ?>
                                    <a href="<?php echo APP_URL; ?>/results?id=<?php echo $article['id']; ?>" 
                                       class="btn btn-outline-primary" title="View Results">
                                        <i data-lucide="eye" class="icon-sm"></i>
                                    </a>
                                    <?php endif; ?>
                                    <button class="btn btn-outline-danger" title="Delete">
                                        <i data-lucide="trash-2" class="icon-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Simple search functionality
document.getElementById('searchArticles').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
