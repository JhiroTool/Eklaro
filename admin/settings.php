<?php
$pageTitle = 'Admin Settings - Eklaro';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/Auth.php';

use Eklaro\Database;
use Eklaro\Auth;

$auth = new Auth();
$auth->requireAdmin();

require_once __DIR__ . '/../includes/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_settings'])) {
    $_SESSION['admin_settings'] = [
        'model_parameters' => [
            'credibility_weight' => 0.6,
            'fact_check_weight' => 0.4,
            'suspicion_threshold' => 0.7
        ],
        'api_keys' => [
            'fact_check' => ''
        ],
        'backups' => [
            'last_backup_at' => null
        ]
    ];
}

$settings = $_SESSION['admin_settings'];
$flashMessages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    if ($auth->validateCSRFToken($token)) {
        if ($action === 'model_parameters') {
            $credibilityWeight = isset($_POST['credibility_weight']) ? max(0, min(1, (float) $_POST['credibility_weight'])) : 0.6;
            $factCheckWeight = isset($_POST['fact_check_weight']) ? max(0, min(1, (float) $_POST['fact_check_weight'])) : 0.4;
            $suspicionThreshold = isset($_POST['suspicion_threshold']) ? max(0, min(1, (float) $_POST['suspicion_threshold'])) : 0.7;

            $settings['model_parameters'] = [
                'credibility_weight' => $credibilityWeight,
                'fact_check_weight' => $factCheckWeight,
                'suspicion_threshold' => $suspicionThreshold
            ];

            $flashMessages[] = ['type' => 'success', 'text' => 'Model parameters updated.'];
        } elseif ($action === 'api_keys') {
            $factCheckKey = trim($_POST['fact_check_key'] ?? '');

            if ($factCheckKey !== '') {
                $settings['api_keys']['fact_check'] = $factCheckKey;
                $flashMessages[] = ['type' => 'success', 'text' => 'Fact-check API key stored for this session.'];
            } else {
                $settings['api_keys']['fact_check'] = '';
                $flashMessages[] = ['type' => 'info', 'text' => 'API key cleared for this session.'];
            }
        } elseif ($action === 'database_backup') {
            $settings['backups']['last_backup_at'] = date('Y-m-d H:i:s');
            $flashMessages[] = ['type' => 'success', 'text' => 'Database backup initiated (simulation).'];
        }

        $_SESSION['admin_settings'] = $settings;
    } else {
        $flashMessages[] = ['type' => 'danger', 'text' => 'Invalid CSRF token.'];
    }
}

$db = Database::getInstance();
$recentLogs = $db->query(
    "SELECT al.*, u.full_name FROM activity_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT 10"
)->fetch_all(MYSQLI_ASSOC);

$maskKey = function ($key) {
    if ($key === '') {
        return 'Not set';
    }

    $length = strlen($key);

    if ($length <= 4) {
        return str_repeat('*', $length);
    }

    return str_repeat('*', $length - 4) . substr($key, -4);
};
?>

<div class="container-fluid py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>
                <i data-lucide="settings" class="icon-md"></i>
                Admin Settings
            </h1>
            <p class="text-muted">Configure system behavior and infrastructure options</p>
        </div>
        <a href="<?php echo APP_URL; ?>/admin" class="btn btn-outline-secondary">
            <i data-lucide="arrow-left" class="icon-sm"></i> Back to Admin
        </a>
    </div>

    <?php foreach ($flashMessages as $message): ?>
        <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message['text']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach; ?>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i data-lucide="sliders" class="icon-sm"></i>
                        Model Parameters
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" class="row g-3">
                        <input type="hidden" name="csrf_token" value="<?php echo $auth->generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="model_parameters">
                        <div class="col-12">
                            <label class="form-label">Credibility Score Weight</label>
                            <input type="number" step="0.05" min="0" max="1" name="credibility_weight" class="form-control" value="<?php echo htmlspecialchars((string) $settings['model_parameters']['credibility_weight']); ?>">
                            <div class="form-text">Distribution between NLP signal strength and final scoring (0-1).</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Fact-Check Weight</label>
                            <input type="number" step="0.05" min="0" max="1" name="fact_check_weight" class="form-control" value="<?php echo htmlspecialchars((string) $settings['model_parameters']['fact_check_weight']); ?>">
                            <div class="form-text">Influence of verified matches on credibility scoring.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Suspicion Threshold</label>
                            <input type="number" step="0.05" min="0" max="1" name="suspicion_threshold" class="form-control" value="<?php echo htmlspecialchars((string) $settings['model_parameters']['suspicion_threshold']); ?>">
                            <div class="form-text">Trigger threshold for highlighting suspicious claims.</div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="save" class="icon-sm"></i> Save Parameters
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i data-lucide="key" class="icon-sm"></i>
                        API Key Management
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" class="row g-3">
                        <input type="hidden" name="csrf_token" value="<?php echo $auth->generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="api_keys">
                        <div class="col-12">
                            <label class="form-label">Google Fact Check Tools API Key</label>
                            <input type="text" name="fact_check_key" class="form-control" placeholder="Enter new key">
                            <div class="form-text">Current session key: <?php echo htmlspecialchars($maskKey($settings['api_keys']['fact_check'])); ?></div>
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="shield" class="icon-sm"></i> Update API Key
                            </button>
                            <button type="submit" name="fact_check_key" value="" class="btn btn-outline-danger">
                                <i data-lucide="x" class="icon-sm"></i> Clear Key
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i data-lucide="database" class="icon-sm"></i>
                        Database Backups
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" class="d-flex flex-column gap-3">
                        <input type="hidden" name="csrf_token" value="<?php echo $auth->generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="database_backup">
                        <div>
                            <div class="fw-semibold">Last backup:</div>
                            <div class="text-muted"><?php echo $settings['backups']['last_backup_at'] ? htmlspecialchars($settings['backups']['last_backup_at']) : 'No backups run this session'; ?></div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="download" class="icon-sm"></i> Run Backup
                            </button>
                            <button type="button" class="btn btn-outline-primary" disabled>
                                <i data-lucide="upload" class="icon-sm"></i> Restore Backup (coming soon)
                            </button>
                        </div>
                        <div class="alert alert-info mb-0">
                            Scheduled backups and remote storage integration can be configured in future updates.
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i data-lucide="list" class="icon-sm"></i>
                        Recent System Logs
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recentLogs)): ?>
                        <p class="text-muted mb-0">No recent activity found.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentLogs as $log): ?>
                                        <tr>
                                            <td><small><?php echo htmlspecialchars(date('M d, Y H:i', strtotime($log['created_at']))); ?></small></td>
                                            <td><small><?php echo $log['full_name'] ? htmlspecialchars($log['full_name']) : 'Guest'; ?></small></td>
                                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($log['action']); ?></span></td>
                                            <td><small><?php echo htmlspecialchars($log['ip_address'] ?? ''); ?></small></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="<?php echo APP_URL; ?>/admin/logs" class="btn btn-outline-primary btn-sm mt-2">
                            <i data-lucide="arrow-right" class="icon-sm"></i> View all logs
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
