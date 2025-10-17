<?php
$pageTitle = 'System Logs - Admin - Eklaro';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/Auth.php';

use Eklaro\Database;
use Eklaro\Auth;

$auth = new Auth();
$auth->requireAdmin();

require_once __DIR__ . '/../includes/header.php';

$db = Database::getInstance();

// Get activity logs
$activityLogs = $db->query(
    "SELECT al.*, u.full_name, u.email 
     FROM activity_logs al 
     LEFT JOIN users u ON al.user_id = u.id 
     ORDER BY al.created_at DESC 
     LIMIT 100"
)->fetch_all(MYSQLI_ASSOC);

// Get API logs
$apiLogs = $db->query(
    "SELECT * FROM api_usage_logs 
     ORDER BY created_at DESC 
     LIMIT 50"
)->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-fluid py-5">
    <!-- Page Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i data-lucide="activity" class="icon-md"></i>
                    System Logs
                </h1>
                <p class="text-muted">Monitor system activity and API usage</p>
            </div>
            <a href="<?php echo APP_URL; ?>/admin" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left" class="icon-sm"></i> Back to Admin
            </a>
        </div>
    </div>
    
    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="logTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity-panel" type="button">
                <i data-lucide="user-check" class="icon-sm"></i> Activity Logs
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="api-tab" data-bs-toggle="tab" data-bs-target="#api-panel" type="button">
                <i data-lucide="cloud" class="icon-sm"></i> API Logs
            </button>
        </li>
    </ul>
    
    <div class="tab-content" id="logTabContent">
        <!-- Activity Logs -->
        <div class="tab-pane fade show active" id="activity-panel" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">User Activity Logs</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activityLogs as $log): ?>
                                <tr>
                                    <td><?php echo $log['id']; ?></td>
                                    <td>
                                        <?php if ($log['full_name']): ?>
                                            <?php echo htmlspecialchars($log['full_name']); ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($log['email']); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">Guest</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($log['action']); ?></span>
                                    </td>
                                    <td><small><?php echo htmlspecialchars($log['ip_address']); ?></small></td>
                                    <td>
                                        <small class="text-muted text-truncate d-inline-block" style="max-width: 200px;">
                                            <?php echo htmlspecialchars($log['user_agent']); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('M d, Y H:i:s', strtotime($log['created_at'])); ?>
                                        </small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- API Logs -->
        <div class="tab-pane fade" id="api-panel" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">API Usage Logs</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>API Name</th>
                                    <th>Endpoint</th>
                                    <th>Status</th>
                                    <th>Response Time</th>
                                    <th>Error</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($apiLogs as $log): ?>
                                <tr>
                                    <td><?php echo $log['id']; ?></td>
                                    <td><?php echo htmlspecialchars($log['api_name']); ?></td>
                                    <td>
                                        <small class="text-truncate d-inline-block" style="max-width: 200px;">
                                            <?php echo htmlspecialchars($log['endpoint']); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = 'secondary';
                                        if ($log['response_status'] >= 200 && $log['response_status'] < 300) {
                                            $statusClass = 'success';
                                        } elseif ($log['response_status'] >= 400) {
                                            $statusClass = 'danger';
                                        }
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass; ?>">
                                            <?php echo $log['response_status'] ?? 'N/A'; ?>
                                        </span>
                                    </td>
                                    <td><small><?php echo $log['response_time_ms']; ?>ms</small></td>
                                    <td>
                                        <?php if ($log['error_message']): ?>
                                            <small class="text-danger"><?php echo htmlspecialchars($log['error_message']); ?></small>
                                        <?php else: ?>
                                            <small class="text-muted">-</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('M d, Y H:i:s', strtotime($log['created_at'])); ?>
                                        </small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Re-initialize Lucide icons after tab changes
document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(button => {
    button.addEventListener('shown.bs.tab', function() {
        lucide.createIcons();
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
