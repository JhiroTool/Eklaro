<?php
$pageTitle = '403 - Access Denied - Eklaro';
require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <i data-lucide="shield-off" class="icon-xl text-danger mb-4" style="width: 100px; height: 100px;"></i>
            <h1 class="display-1 fw-bold">403</h1>
            <h2 class="mb-4">Access Denied</h2>
            <p class="lead text-muted mb-4">
                You don't have permission to access this page.
            </p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="<?php echo APP_URL; ?>" class="btn btn-primary">
                    <i data-lucide="home" class="icon-sm"></i> Go Home
                </a>
                <?php if (!$auth->isLoggedIn()): ?>
                <a href="<?php echo APP_URL; ?>/login" class="btn btn-outline-primary">
                    <i data-lucide="log-in" class="icon-sm"></i> Login
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
