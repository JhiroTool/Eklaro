<?php
$pageTitle = 'Profile - Eklaro';
require_once 'includes/header.php';
require_once 'includes/Database.php';

use Eklaro\Database;

// Require login
$auth->requireLogin();

$db = Database::getInstance();
$userId = $currentUser['id'];

$success = '';
$error = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$auth->validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request';
    } else {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        if (empty($fullName) || empty($email)) {
            $error = 'All fields are required';
        } else {
            // Check if email is already taken by another user
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->bind_param("si", $email, $userId);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows > 0) {
                $error = 'Email already in use';
            } else {
                // Update profile
                $stmt = $db->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
                $stmt->bind_param("ssi", $fullName, $email, $userId);
                
                if ($stmt->execute()) {
                    $_SESSION['user_name'] = $fullName;
                    $_SESSION['user_email'] = $email;
                    $success = 'Profile updated successfully';
                } else {
                    $error = 'Failed to update profile';
                }
            }
        }
    }
}

// Get user data
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-4">
                <h1>
                    <i data-lucide="user-circle" class="icon-md"></i>
                    My Profile
                </h1>
                <p class="text-muted">Manage your account settings</p>
            </div>
            
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?php echo $auth->generateCSRFToken(); ?>">
                        
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required
                                   value="<?php echo htmlspecialchars($userData['full_name']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                   value="<?php echo htmlspecialchars($userData['email']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Account Role</label>
                            <input type="text" class="form-control" readonly
                                   value="<?php echo ucfirst($userData['role']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Member Since</label>
                            <input type="text" class="form-control" readonly
                                   value="<?php echo date('F d, Y', strtotime($userData['created_at'])); ?>">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="save" class="icon-sm"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Password change functionality coming soon.</p>
                    <button class="btn btn-outline-primary" disabled>
                        <i data-lucide="lock" class="icon-sm"></i> Change Password
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
