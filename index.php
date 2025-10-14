<?php
$pageTitle = 'Eklaro - Combat Misinformation with AI';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden">
    <div class="hero-animation">
        <div class="floating-element" style="top: 10%; left: 10%;">
            <i data-lucide="brain" class="icon-lg text-white opacity-25"></i>
        </div>
        <div class="floating-element" style="top: 20%; right: 15%; animation-delay: 1s;">
            <i data-lucide="shield-check" class="icon-lg text-white opacity-25"></i>
        </div>
        <div class="floating-element" style="bottom: 20%; left: 20%; animation-delay: 2s;">
            <i data-lucide="search-check" class="icon-lg text-white opacity-25"></i>
        </div>
        <div class="floating-element" style="bottom: 15%; right: 10%; animation-delay: 1.5s;">
            <i data-lucide="newspaper" class="icon-lg text-white opacity-25"></i>
        </div>
    </div>
    
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <div class="hero-icon-wrapper mb-4">
                    <i data-lucide="shield-check" class="icon-xl pulse"></i>
                </div>
                <h1 class="display-3 fw-bold mb-4">Empowering Truth in the Digital Age</h1>
                <p class="lead mb-5 fs-4">
                    Harness the power of AI, NLP, and machine learning to validate news articles 
                    and combat misinformation in real-time.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap mb-4">
                    <a href="<?php echo APP_URL; ?>/validate" class="btn btn-light btn-lg px-5 py-3 cta-button">
                        <i data-lucide="file-search" class="icon-sm"></i>
                        Start Validating
                        <i data-lucide="arrow-right" class="icon-sm ms-2"></i>
                    </a>
                    <a href="<?php echo APP_URL; ?>/register" class="btn btn-outline-light btn-lg px-5 py-3">
                        <i data-lucide="user-plus" class="icon-sm"></i>
                        Create Account
                    </a>
                </div>
                <div class="hero-badges mt-4">
                    <span class="badge bg-white text-primary me-2 px-3 py-2">
                        <i data-lucide="zap" class="icon-sm"></i> AI-Powered
                    </span>
                    <span class="badge bg-white text-primary me-2 px-3 py-2">
                        <i data-lucide="shield" class="icon-sm"></i> Fact-Checked
                    </span>
                    <span class="badge bg-white text-primary px-3 py-2">
                        <i data-lucide="trending-up" class="icon-sm"></i> Real-Time
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Statement -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Our Mission</h2>
                <p class="lead text-muted">
                    Eklaro is dedicated to promoting digital literacy and combating the spread of 
                    misinformation. We provide powerful AI-driven tools to help students, educators, 
                    journalists, researchers, and the general public verify the credibility of online content.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>How Eklaro Works</h2>
            <p class="text-muted">Advanced AI technology to validate article credibility</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card">
                    <div class="card-body text-center">
                        <div class="feature-icon mx-auto">
                            <i data-lucide="upload" class="icon-md"></i>
                        </div>
                        <h5 class="card-title">Submit Article</h5>
                        <p class="card-text text-muted">
                            Paste text, enter URL, or upload a .txt file for analysis
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card">
                    <div class="card-body text-center">
                        <div class="feature-icon mx-auto">
                            <i data-lucide="brain" class="icon-md"></i>
                        </div>
                        <h5 class="card-title">NLP Analysis</h5>
                        <p class="card-text text-muted">
                            Advanced linguistic pattern detection and sentiment analysis
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card">
                    <div class="card-body text-center">
                        <div class="feature-icon mx-auto">
                            <i data-lucide="search-check" class="icon-md"></i>
                        </div>
                        <h5 class="card-title">Fact Checking</h5>
                        <p class="card-text text-muted">
                            Cross-reference with Google Fact Check database
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card">
                    <div class="card-body text-center">
                        <div class="feature-icon mx-auto">
                            <i data-lucide="award" class="icon-md"></i>
                        </div>
                        <h5 class="card-title">Credibility Score</h5>
                        <p class="card-text text-muted">
                            Get detailed credibility assessment and explanation
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Target Audience Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Who Benefits from Eklaro?</h2>
            <p class="text-muted">Empowering diverse communities to combat misinformation</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i data-lucide="graduation-cap" class="icon-lg text-primary mb-3"></i>
                        <h5>Students & Educators</h5>
                        <p class="text-muted small">
                            Teach critical thinking and verify research sources
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i data-lucide="newspaper" class="icon-lg text-primary mb-3"></i>
                        <h5>Journalists</h5>
                        <p class="text-muted small">
                            Verify sources and maintain journalistic integrity
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i data-lucide="microscope" class="icon-lg text-primary mb-3"></i>
                        <h5>Researchers</h5>
                        <p class="text-muted small">
                            Validate information for academic work
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i data-lucide="users" class="icon-lg text-primary mb-3"></i>
                        <h5>General Public</h5>
                        <p class="text-muted small">
                            Stay informed and avoid fake news
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Ready to Start Validating?</h2>
                <p class="lead text-muted mb-4">
                    Join thousands of users who trust Eklaro to verify their news sources
                </p>
                <a href="<?php echo APP_URL; ?>/validate" class="btn btn-primary btn-lg">
                    <i data-lucide="file-search" class="icon-sm"></i>
                    Check an Article Now
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <div class="p-3">
                    <i data-lucide="file-check" class="icon-lg mb-3"></i>
                    <h3 class="display-4 fw-bold">1000+</h3>
                    <p class="text-white-50">Articles Validated</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3">
                    <i data-lucide="users" class="icon-lg mb-3"></i>
                    <h3 class="display-4 fw-bold">500+</h3>
                    <p class="text-white-50">Active Users</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3">
                    <i data-lucide="shield-check" class="icon-lg mb-3"></i>
                    <h3 class="display-4 fw-bold">95%</h3>
                    <p class="text-white-50">Accuracy Rate</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
