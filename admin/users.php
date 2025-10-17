<?php
$pageTitle = 'Manage Users - Admin - Eklaro';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/Auth.php';

use Eklaro\Database;
use Eklaro\Auth;

$auth = new Auth();
$auth->requireAdmin();

require_once __DIR__ . '/../includes/header.php';

$db = Database::getInstance();

// Get all users
$users = $db->query(
    "SELECT u.*, 
     COUNT(DISTINCT a.id) as article_count,
     MAX(a.created_at) as last_validation
     FROM users u
     LEFT JOIN articles a ON u.id = a.user_id
     GROUP BY u.id
     ORDER BY u.created_at DESC"
)->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-fluid py-5">
    <!-- Page Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i data-lucide="users" class="icon-md"></i>
                    Manage Users
                </h1>
                <p class="text-muted">View and manage user accounts</p>
            </div>
            <a href="<?php echo APP_URL; ?>/admin" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left" class="icon-sm"></i> Back to Admin
            </a>
        </div>
    </div>
    
    <!-- Users Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Users (<?php echo count($users); ?>)</h5>
                <input type="search" class="form-control w-auto" placeholder="Search users..." id="searchUsers">
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Articles</th>
                            <th>Last Login</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'primary'; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td><?php echo $user['article_count']; ?></td>
                            <td>
                                <small class="text-muted">
                                    <?php echo $user['last_login'] ? date('M d, Y', strtotime($user['last_login'])) : 'Never'; ?>
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?>">
                                    <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View Details">
                                        <i data-lucide="eye" class="icon-sm"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" title="Edit">
                                        <i data-lucide="edit" class="icon-sm"></i>
                                    </button>
                                    <?php if ($user['id'] !== $currentUser['id']): ?>
                                    <button class="btn btn-outline-danger" title="Delete">
                                        <i data-lucide="trash-2" class="icon-sm"></i>
                                    </button>
                                    <?php endif; ?>
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
document.getElementById('searchUsers').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
