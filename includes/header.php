<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Auth.php';

use Eklaro\Auth;

$auth = new Auth();
$currentUser = $auth->getCurrentUser();
$isLoggedIn = $auth->isLoggedIn();
$isAdmin = $auth->isAdmin();

// Get current page for active nav
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$scriptName = $_SERVER['SCRIPT_NAME'];
$isAdminPage = (strpos($scriptName, '/admin/') !== false);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Eklaro - AI-Powered Misinformation Detection'; ?></title>
    <meta name="description" content="Combat misinformation and promote digital literacy with AI-powered article validation and fact-checking.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/enhancements.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo APP_URL; ?>/assets/images/favicon.svg">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar-modern navbar-expand-lg sticky-top">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand-modern d-flex align-items-center" href="<?php echo APP_URL; ?>">
                <div class="brand-icon-wrapper">
                    <i data-lucide="shield-check" class="brand-icon"></i>
                </div>
                <div class="brand-text">
                    <span class="brand-name">Eklaro</span>
                    <span class="brand-tagline">Truth Validator</span>
                </div>
            </a>
            
            <button class="navbar-toggler-modern collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="toggler-line"></span>
                <span class="toggler-line"></span>
                <span class="toggler-line"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav-modern ms-auto align-items-center">
                    <li class="nav-item-modern">
                        <a class="nav-link-modern <?php echo ($currentPage === 'index' && !$isAdminPage) ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>">
                            Home
                        </a>
                    </li>
                    <li class="nav-item-modern">
                        <a class="nav-link-modern <?php echo $currentPage === 'validate' ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/validate">
                            Validate
                        </a>
                    </li>
                    <li class="nav-item-modern">
                        <a class="nav-link-modern <?php echo $currentPage === 'about' ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/about">
                            About
                        </a>
                    </li>
                    
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item-modern">
                            <a class="nav-link-modern <?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/dashboard">
                                Dashboard
                            </a>
                        </li>
                        
                        <?php if ($isAdmin): ?>
                            <li class="nav-item-modern">
                                <a class="nav-link-modern <?php echo $isAdminPage ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/admin">
                                    Admin
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item-modern dropdown">
                            <a class="nav-link-modern user-menu" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar">
                                    <?php echo strtoupper(substr($currentUser['name'], 0, 1)); ?>
                                </div>
                                <span class="user-name"><?php echo htmlspecialchars($currentUser['name']); ?></span>
                                <i data-lucide="chevron-down" class="nav-icon ms-1"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-modern dropdown-menu-end">
                                <li class="dropdown-header">
                                    <div class="dropdown-user-info">
                                        <strong><?php echo htmlspecialchars($currentUser['name']); ?></strong>
                                        <small class="text-muted d-block"><?php echo htmlspecialchars($currentUser['email']); ?></small>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item-modern" href="<?php echo APP_URL; ?>/profile">
                                        <i data-lucide="user-circle" class="dropdown-icon"></i> 
                                        <span>My Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item-modern" href="<?php echo APP_URL; ?>/dashboard">
                                        <i data-lucide="activity" class="dropdown-icon"></i> 
                                        <span>Activity</span>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item-modern text-danger" href="<?php echo APP_URL; ?>/logout">
                                        <i data-lucide="log-out" class="dropdown-icon"></i> 
                                        <span>Logout</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item-modern">
                            <a class="nav-link-modern <?php echo $currentPage === 'login' ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/login">
                                Login
                            </a>
                        </li>
                        <li class="nav-item-modern">
                            <a class="btn-cta" href="<?php echo APP_URL; ?>/register">
                                Get Started
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main>
