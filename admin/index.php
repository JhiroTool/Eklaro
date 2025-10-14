<?php
$pageTitle = 'Admin Panel - Eklaro';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/Database.php';

use Eklaro\Database;

// Require admin access
$auth->requireAdmin();

$db = Database::getInstance();

// Get system statistics
$totalUsers = $db->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalArticles = $db->query("SELECT COUNT(*) as count FROM articles")->fetch_assoc()['count'];
$totalValidations = $db->query("SELECT COUNT(*) as count FROM validation_results")->fetch_assoc()['count'];
$avgScore = $db->query("SELECT AVG(credibility_score) as avg FROM articles WHERE credibility_score IS NOT NULL")->fetch_assoc()['avg'] ?? 0;

// Get recent activity
$recentActivity = $db->query(
    "SELECT al.*, u.full_name, u.email 
     FROM activity_logs al 
     LEFT JOIN users u ON al.user_id = u.id 
     ORDER BY al.created_at DESC 
     LIMIT 20"
)->fetch_all(MYSQLI_ASSOC);

// Get API usage stats
$apiUsage = $db->query(
    "SELECT api_name, COUNT(*) as count, AVG(response_time_ms) as avg_time 
     FROM api_usage_logs 
     WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
     GROUP BY api_name"
)->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-fluid py-5">
    <!-- Page Header -->
    <div class="mb-5">
        <h1>
            <i data-lucide="settings" class="icon-md"></i>
            Admin Panel
        </h1>
        <p class="text-muted">System overview and management</p>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Users</p>
                            <h3 class="mb-0"><?php echo $totalUsers; ?></h3>
                        </div>
                        <i data-lucide="users" class="icon-lg text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Articles</p>
                            <h3 class="mb-0"><?php echo $totalArticles; ?></h3>
                        </div>
                        <i data-lucide="file-text" class="icon-lg text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Validations</p>
                            <h3 class="mb-0"><?php echo $totalValidations; ?></h3>
                        </div>
                        <i data-lucide="check-circle" class="icon-lg text-info"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Avg Score</p>
                            <h3 class="mb-0"><?php echo round($avgScore, 1); ?>%</h3>
                        </div>
                        <i data-lucide="trending-up" class="icon-lg text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i data-lucide="zap" class="icon-sm"></i>
                        Quick Actions
                    </h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?php echo APP_URL; ?>/admin/users" class="btn btn-primary">
                            <i data-lucide="users" class="icon-sm"></i> Manage Users
                        </a>
                        <a href="<?php echo APP_URL; ?>/admin/articles" class="btn btn-outline-primary">
                            <i data-lucide="file-text" class="icon-sm"></i> View Articles
                        </a>
                        <a href="<?php echo APP_URL; ?>/admin/logs" class="btn btn-outline-primary">
                            <i data-lucide="activity" class="icon-sm"></i> View Logs
                        </a>
                        <a href="<?php echo APP_URL; ?>/admin/settings" class="btn btn-outline-primary">
                            <i data-lucide="settings" class="icon-sm"></i> Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Recent Activity -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i data-lucide="activity" class="icon-sm"></i>
                        Recent Activity
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>IP Address</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($recentActivity, 0, 10) as $activity): ?>
                                <tr>
                                    <td>
                                        <?php if ($activity['full_name']): ?>
                                            <?php echo htmlspecialchars($activity['full_name']); ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($activity['email']); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">Guest</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($activity['action']); ?></span>
                                    </td>
                                    <td><small class="text-muted"><?php echo htmlspecialchars($activity['ip_address']); ?></small></td>
                                    <td><small class="text-muted"><?php echo date('M d, H:i', strtotime($activity['created_at'])); ?></small></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- API Usage -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i data-lucide="bar-chart" class="icon-sm"></i>
                        API Usage (7 Days)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($apiUsage)): ?>
                        <p class="text-muted text-center">No API usage data</p>
                    <?php else: ?>
                        <?php foreach ($apiUsage as $api): ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong><?php echo htmlspecialchars($api['api_name']); ?></strong>
                                <span class="badge bg-primary"><?php echo $api['count']; ?> calls</span>
                            </div>
                            <small class="text-muted">
                                Avg response: <?php echo round($api['avg_time']); ?>ms
                            </small>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
