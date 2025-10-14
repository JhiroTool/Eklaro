<?php
$pageTitle = 'Login - Eklaro';
require_once 'includes/header.php';

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    header('Location: ' . APP_URL . '/dashboard');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!$auth->validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request';
    } else {
        $result = $auth->login($email, $password);
        
        if ($result['success']) {
            header('Location: ' . APP_URL . '/dashboard');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-4">
                <i data-lucide="log-in" class="icon-xl text-primary mb-3"></i>
                <h1>Login to Eklaro</h1>
                <p class="text-muted">Access your dashboard and validation history</p>
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
            
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?php echo $auth->generateCSRFToken(); ?>">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i data-lucide="log-in" class="icon-sm"></i> Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-3">
                <p class="text-muted">
                    Don't have an account? 
                    <a href="<?php echo APP_URL; ?>/register">Register here</a>
                </p>
            </div>
            
            <div class="card mt-3 border-0 bg-light">
                <div class="card-body">
                    <h6>Demo Accounts:</h6>
                    <p class="mb-1 small"><strong>Admin:</strong> admin@eklaro.com / admin123</p>
                    <p class="mb-0 small"><strong>User:</strong> user@eklaro.com / user123</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
