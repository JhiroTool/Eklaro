<?php
$pageTitle = 'Eklaro - Combat Misinformation with AI';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden">
    <!-- Animated Particles -->
    <div class="particles-bg">
        <?php for($i = 0; $i < 20; $i++): ?>
        <div class="particle" style="left: <?php echo rand(0, 100); ?>%; animation-delay: <?php echo rand(0, 10); ?>s;"></div>
        <?php endfor; ?>
    </div>
    
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
            <div class="col-lg-10 mx-auto text-center">
                <div class="hero-icon-wrapper mb-4">
                    <i data-lucide="shield-check" class="icon-xl icon-bounce"></i>
                </div>
                <h1 class="display-3 fw-bold mb-4">Empowering Truth in the Digital Age</h1>
                <p class="lead mb-5 fs-4">
                    Harness the power of <strong>AI</strong>, <strong>NLP</strong>, and <strong>machine learning</strong> to validate news articles 
                    and combat misinformation in real-time.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap mb-5">
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
<section class="py-5 bg-light position-relative">
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    <div class="container position-relative">
        <div class="row">
            <div class="col-lg-10 mx-auto text-center">
                <div class="mb-4">
                    <span class="badge bg-primary px-4 py-2 mb-3">Our Mission</span>
                </div>
                <h2 class="section-title mb-4">Building a World of Truth & Transparency</h2>
                <p class="lead text-muted fs-5">
                    Eklaro is dedicated to promoting <strong>digital literacy</strong> and combating the spread of 
                    misinformation. We provide powerful AI-driven tools to help students, educators, 
                    journalists, researchers, and the general public verify the credibility of online content.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="modern-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary px-4 py-2 mb-3">How It Works</span>
            <h2 class="section-title">Powered by Advanced AI Technology</h2>
            <p class="section-subtitle">Four simple steps to validate article credibility</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto" style="background: var(--gradient-1);">
                        <i data-lucide="upload" class="icon-md"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Submit Article</h5>
                    <p class="text-muted mb-0">
                        Paste text, enter URL, or upload a .txt file for instant analysis
                    </p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto" style="background: var(--gradient-3);">
                        <i data-lucide="brain" class="icon-md"></i>
                    </div>
                    <h5 class="fw-bold mb-3">NLP Analysis</h5>
                    <p class="text-muted mb-0">
                        Advanced linguistic pattern detection and sentiment analysis
                    </p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto" style="background: var(--gradient-4);">
                        <i data-lucide="search-check" class="icon-md"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Fact Checking</h5>
                    <p class="text-muted mb-0">
                        Cross-reference with Google Fact Check database
                    </p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto" style="background: var(--gradient-5);">
                        <i data-lucide="award" class="icon-md"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Credibility Score</h5>
                    <p class="text-muted mb-0">
                        Get detailed credibility assessment and explanation
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Target Audience Section -->
<section class="py-5 bg-light position-relative">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary px-4 py-2 mb-3">For Everyone</span>
            <h2 class="section-title">Who Benefits from Eklaro?</h2>
            <p class="section-subtitle">Empowering diverse communities to combat misinformation</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 text-center border-0 glass-effect hover-glow">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i data-lucide="graduation-cap" class="icon-lg" style="color: var(--primary-color);"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Students & Educators</h5>
                        <p class="text-muted mb-0">
                            Teach critical thinking and verify research sources
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 text-center border-0 glass-effect hover-glow">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i data-lucide="newspaper" class="icon-lg" style="color: var(--primary-color);"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Journalists</h5>
                        <p class="text-muted mb-0">
                            Verify sources and maintain journalistic integrity
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 text-center border-0 glass-effect hover-glow">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i data-lucide="microscope" class="icon-lg" style="color: var(--primary-color);"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Researchers</h5>
                        <p class="text-muted mb-0">
                            Validate information for academic work
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 text-center border-0 glass-effect hover-glow">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i data-lucide="users" class="icon-lg" style="color: var(--primary-color);"></i>
                        </div>
                        <h5 class="fw-bold mb-3">General Public</h5>
                        <p class="text-muted mb-0">
                            Stay informed and avoid fake news
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 position-relative" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h2 class="display-6 fw-bold mb-4">Ready to Start Validating?</h2>
                <p class="lead mb-4">
                    Join thousands of users who trust Eklaro to verify their news sources
                </p>
                <a href="<?php echo APP_URL; ?>/validate" class="btn btn-light btn-lg px-5 py-3">
                    <i data-lucide="file-search" class="icon-sm"></i>
                    Check an Article Now
                    <i data-lucide="arrow-right" class="icon-sm ms-2"></i>
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
                <div class="p-4">
                    <div class="mb-3">
                        <i data-lucide="file-check" class="icon-lg" style="color: #3b82f6;"></i>
                    </div>
                    <h3 class="display-4 fw-bold mb-2">1000+</h3>
                    <p class="text-white-50 mb-0">Articles Validated</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="mb-3">
                        <i data-lucide="users" class="icon-lg" style="color: #10b981;"></i>
                    </div>
                    <h3 class="display-4 fw-bold mb-2">500+</h3>
                    <p class="text-white-50 mb-0">Active Users</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="mb-3">
                        <i data-lucide="shield-check" class="icon-lg" style="color: #06b6d4;"></i>
                    </div>
                    <h3 class="display-4 fw-bold mb-2">95%</h3>
                    <p class="text-white-50 mb-0">Accuracy Rate</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
