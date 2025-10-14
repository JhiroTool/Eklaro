<?php
$pageTitle = 'About - Eklaro';
require_once 'includes/header.php';
?>

<div class="container py-5">
    <!-- Hero -->
    <div class="text-center mb-5">
        <i data-lucide="info" class="icon-xl text-primary mb-3" style="width: 80px; height: 80px;"></i>
        <h1>About Eklaro</h1>
        <p class="lead text-muted">Empowering digital literacy through AI-powered fact-checking</p>
    </div>
    
    <!-- Mission -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <h2 class="mb-4">Our Mission</h2>
                    <p class="lead">
                        In an era of information overload and widespread misinformation, Eklaro stands as a 
                        beacon of truth and digital literacy. Our mission is to empower individuals with the 
                        tools they need to critically evaluate online content and make informed decisions.
                    </p>
                    <p>
                        We believe that combating misinformation requires more than just fact-checking—it 
                        requires education, transparency, and accessible technology. That's why we've built 
                        Eklaro to be free, open, and easy to use for everyone.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- How It Works -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">How Eklaro Works</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i data-lucide="brain" class="icon-lg text-primary mb-3"></i>
                            <h5>NLP Analysis</h5>
                            <p class="text-muted">
                                Our advanced Natural Language Processing algorithms analyze linguistic patterns, 
                                detect bias, and identify suspicious claims in article text.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i data-lucide="database" class="icon-lg text-primary mb-3"></i>
                            <h5>Fact-Check Database</h5>
                            <p class="text-muted">
                                We cross-reference claims with Google's Fact Check Tools API and other 
                                verified fact-checking sources.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i data-lucide="award" class="icon-lg text-primary mb-3"></i>
                            <h5>Credibility Scoring</h5>
                            <p class="text-muted">
                                Articles receive a comprehensive credibility score based on multiple factors, 
                                with detailed explanations for transparency.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Target Audience -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <h2 class="text-center mb-4">Who We Serve</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex">
                        <i data-lucide="graduation-cap" class="icon-md text-primary me-3 flex-shrink-0"></i>
                        <div>
                            <h5>Students & Educators</h5>
                            <p class="text-muted">
                                Teach critical thinking skills and verify research sources for academic work.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex">
                        <i data-lucide="newspaper" class="icon-md text-primary me-3 flex-shrink-0"></i>
                        <div>
                            <h5>Journalists</h5>
                            <p class="text-muted">
                                Verify sources and maintain journalistic integrity in reporting.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex">
                        <i data-lucide="microscope" class="icon-md text-primary me-3 flex-shrink-0"></i>
                        <div>
                            <h5>Researchers</h5>
                            <p class="text-muted">
                                Validate information for academic and professional research projects.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex">
                        <i data-lucide="users" class="icon-md text-primary me-3 flex-shrink-0"></i>
                        <div>
                            <h5>General Public</h5>
                            <p class="text-muted">
                                Stay informed and avoid falling victim to fake news and misinformation.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Future Plans -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body p-5">
                    <h2 class="mb-4">
                        <i data-lucide="rocket" class="icon-md"></i>
                        Future Enhancements
                    </h2>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i data-lucide="check" class="icon-sm text-success"></i>
                            Advanced sentiment analysis
                        </li>
                        <li class="mb-2">
                            <i data-lucide="check" class="icon-sm text-success"></i>
                            Browser extension for real-time fact-checking
                        </li>
                        <li class="mb-2">
                            <i data-lucide="check" class="icon-sm text-success"></i>
                            Mobile applications (iOS & Android)
                        </li>
                        <li class="mb-2">
                            <i data-lucide="check" class="icon-sm text-success"></i>
                            Integration with advanced AI models (BERT, GPT)
                        </li>
                        <li class="mb-2">
                            <i data-lucide="check" class="icon-sm text-success"></i>
                            Multi-language support
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
