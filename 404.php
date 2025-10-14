<?php
$pageTitle = '404 - Page Not Found - Eklaro';
require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <i data-lucide="alert-circle" class="icon-xl text-warning mb-4" style="width: 100px; height: 100px;"></i>
            <h1 class="display-1 fw-bold">404</h1>
            <h2 class="mb-4">Page Not Found</h2>
            <p class="lead text-muted mb-4">
                The page you're looking for doesn't exist or has been moved.
            </p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="<?php echo APP_URL; ?>" class="btn btn-primary">
                    <i data-lucide="home" class="icon-sm"></i> Go Home
                </a>
                <a href="<?php echo APP_URL; ?>/validate" class="btn btn-outline-primary">
                    <i data-lucide="file-search" class="icon-sm"></i> Validate Article
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
