<?php
$pageTitle = 'About - Eklaro';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="position-relative overflow-hidden py-5" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
    <div class="container py-5 position-relative">
        <div class="row align-items-center">
            <div class="col-lg-10 mx-auto text-center text-white">
                <div class="mb-4">
                    <i data-lucide="info" class="icon-xl mb-3" style="width: 80px; height: 80px;"></i>
                </div>
                <h1 class="display-4 fw-bold mb-4">About Eklaro</h1>
                <p class="lead fs-4 mb-0">Empowering digital literacy through <strong>AI-powered</strong> fact-checking</p>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    
    <!-- Mission -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-lg border-0 glass-effect hover-glow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <span class="badge bg-primary px-4 py-2 mb-3">Our Mission</span>
                    </div>
                    <h2 class="section-title text-center mb-4">Building a World of Truth & Transparency</h2>
                    <p class="lead text-center mb-4">
                        In an era of information overload and widespread misinformation, Eklaro stands as a 
                        <strong>beacon of truth</strong> and digital literacy. Our mission is to empower individuals with the 
                        tools they need to critically evaluate online content and make informed decisions.
                    </p>
                    <div class="row g-4 mt-4">
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <i data-lucide="shield-check" class="icon-lg" style="color: var(--primary-color);"></i>
                            </div>
                            <h5 class="fw-bold">Free & Open</h5>
                            <p class="text-muted mb-0">Accessible to everyone, always</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <i data-lucide="eye" class="icon-lg" style="color: var(--primary-color);"></i>
                            </div>
                            <h5 class="fw-bold">Transparent</h5>
                            <p class="text-muted mb-0">Clear explanations for every result</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <i data-lucide="zap" class="icon-lg" style="color: var(--primary-color);"></i>
                            </div>
                            <h5 class="fw-bold">Easy to Use</h5>
                            <p class="text-muted mb-0">Simple interface, powerful results</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- How It Works -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center mb-5">
                <span class="badge bg-primary px-4 py-2 mb-3">Technology</span>
                <h2 class="section-title">How Eklaro Works</h2>
                <p class="section-subtitle">Advanced AI technology powering truth verification</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon mx-auto" style="background: var(--gradient-1);">
                            <i data-lucide="brain" class="icon-md"></i>
                        </div>
                        <h5 class="fw-bold mb-3">NLP Analysis</h5>
                        <p class="text-muted mb-0">
                            Our advanced Natural Language Processing algorithms analyze linguistic patterns, 
                            detect bias, and identify suspicious claims in article text.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon mx-auto" style="background: var(--gradient-3);">
                            <i data-lucide="database" class="icon-md"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Fact-Check Database</h5>
                        <p class="text-muted mb-0">
                            We cross-reference claims with Google's Fact Check Tools API and other 
                            verified fact-checking sources.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon mx-auto" style="background: var(--gradient-5);">
                            <i data-lucide="award" class="icon-md"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Credibility Scoring</h5>
                        <p class="text-muted mb-0">
                            Articles receive a comprehensive credibility score based on multiple factors, 
                            with detailed explanations for transparency.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Target Audience -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="text-center mb-5">
                <span class="badge bg-primary px-4 py-2 mb-3">Community</span>
                <h2 class="section-title">Who We Serve</h2>
                <p class="section-subtitle">Empowering diverse communities worldwide</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 glass-effect hover-lift p-4">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <i data-lucide="graduation-cap" class="icon-lg" style="color: var(--primary-color);"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">Students & Educators</h5>
                                <p class="text-muted mb-0">
                                    Teach critical thinking skills and verify research sources for academic work.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 border-0 glass-effect hover-lift p-4">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <i data-lucide="newspaper" class="icon-lg" style="color: var(--primary-color);"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">Journalists</h5>
                                <p class="text-muted mb-0">
                                    Verify sources and maintain journalistic integrity in reporting.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 border-0 glass-effect hover-lift p-4">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <i data-lucide="microscope" class="icon-lg" style="color: var(--primary-color);"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">Researchers</h5>
                                <p class="text-muted mb-0">
                                    Validate information for academic and professional research projects.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 border-0 glass-effect hover-lift p-4">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <i data-lucide="users" class="icon-lg" style="color: var(--primary-color);"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">General Public</h5>
                                <p class="text-muted mb-0">
                                    Stay informed and avoid falling victim to fake news and misinformation.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Future Plans -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-lg border-0" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i data-lucide="rocket" class="icon-lg mb-3"></i>
                        <h2 class="fw-bold mb-3">Future Enhancements</h2>
                        <p class="lead mb-0">Planned features and improvements</p>
                    </div>
                    <div class="row g-4 mt-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start p-3 rounded" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
                                <i data-lucide="check-circle" class="icon-sm me-3 flex-shrink-0 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Advanced Sentiment Analysis</h6>
                                    <p class="small mb-0 opacity-75">Deeper understanding of article tone and intent</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start p-3 rounded" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
                                <i data-lucide="check-circle" class="icon-sm me-3 flex-shrink-0 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Browser Extension</h6>
                                    <p class="small mb-0 opacity-75">Real-time fact-checking while you browse</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start p-3 rounded" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
                                <i data-lucide="check-circle" class="icon-sm me-3 flex-shrink-0 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Mobile Applications</h6>
                                    <p class="small mb-0 opacity-75">Native apps for iOS & Android devices</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start p-3 rounded" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
                                <i data-lucide="check-circle" class="icon-sm me-3 flex-shrink-0 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Advanced AI Models</h6>
                                    <p class="small mb-0 opacity-75">Integration with BERT, GPT, and more</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start p-3 rounded" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
                                <i data-lucide="check-circle" class="icon-sm me-3 flex-shrink-0 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Multi-Language Support</h6>
                                    <p class="small mb-0 opacity-75">Fact-checking in multiple languages</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start p-3 rounded" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
                                <i data-lucide="check-circle" class="icon-sm me-3 flex-shrink-0 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">API Access</h6>
                                    <p class="small mb-0 opacity-75">Developer API for third-party integrations</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <div class="row">
        <div class="col-lg-8 mx-auto text-center">
            <div class="card shadow-lg border-0 glass-effect p-5">
                <h3 class="fw-bold mb-3">Ready to Start Fact-Checking?</h3>
                <p class="text-muted mb-4">Join our community in the fight against misinformation</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="<?php echo APP_URL; ?>/validate" class="btn btn-primary btn-lg">
                        <i data-lucide="file-search" class="icon-sm"></i>
                        Validate an Article
                    </a>
                    <a href="<?php echo APP_URL; ?>/register" class="btn btn-outline-primary btn-lg">
                        <i data-lucide="user-plus" class="icon-sm"></i>
                        Create Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
