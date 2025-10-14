<?php
$pageTitle = 'Validate Article - Eklaro';
require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Alert Container -->
            <div id="alertContainer"></div>
            
            <!-- Page Header -->
            <div class="text-center mb-5">
                <i data-lucide="file-search" class="icon-xl text-primary mb-3"></i>
                <h1>Validate Article</h1>
                <p class="lead text-muted">
                    Submit an article for AI-powered credibility analysis and fact-checking
                </p>
            </div>
            
            <!-- Submission Form -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form id="validateForm" action="<?php echo APP_URL; ?>/api/submit-article.php" method="POST" enctype="multipart/form-data">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo $auth->generateCSRFToken(); ?>">
                        
                        <!-- Submission Type Tabs -->
                        <ul class="nav nav-tabs mb-4" id="submissionTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="text-tab" data-bs-toggle="tab" data-bs-target="#text-panel" type="button">
                                    <i data-lucide="type" class="icon-sm"></i> Paste Text
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="url-tab" data-bs-toggle="tab" data-bs-target="#url-panel" type="button">
                                    <i data-lucide="link" class="icon-sm"></i> Enter URL
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="file-tab" data-bs-toggle="tab" data-bs-target="#file-panel" type="button">
                                    <i data-lucide="upload" class="icon-sm"></i> Upload File
                                </button>
                            </li>
                        </ul>
                        
                        <!-- Tab Content -->
                        <div class="tab-content" id="submissionTabContent">
                            <!-- Text Input -->
                            <div class="tab-pane fade show active" id="text-panel" role="tabpanel">
                                <div class="mb-3">
                                    <label for="articleTitle" class="form-label">Article Title</label>
                                    <input type="text" class="form-control" id="articleTitle" name="article_title" placeholder="Enter article title">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="articleText" class="form-label">Article Content *</label>
                                    <textarea class="form-control" id="articleText" name="article_text" rows="10" 
                                              placeholder="Paste the article content here..." required></textarea>
                                    <div class="form-text">
                                        Minimum <?php echo MIN_ARTICLE_LENGTH; ?> characters, maximum <?php echo MAX_ARTICLE_LENGTH; ?> characters
                                    </div>
                                </div>
                                
                                <input type="hidden" name="submission_type" value="text">
                            </div>
                            
                            <!-- URL Input -->
                            <div class="tab-pane fade" id="url-panel" role="tabpanel">
                                <div class="mb-3">
                                    <label for="articleUrl" class="form-label">Article URL *</label>
                                    <input type="url" class="form-control" id="articleUrl" name="article_url" 
                                           placeholder="https://example.com/article">
                                    <div class="form-text">
                                        Enter the full URL of the article you want to validate
                                    </div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i data-lucide="info" class="icon-sm"></i>
                                    <strong>Note:</strong> URL extraction is currently in beta. For best results, paste the article text directly.
                                </div>
                                
                                <input type="hidden" name="submission_type" value="url">
                            </div>
                            
                            <!-- File Upload -->
                            <div class="tab-pane fade" id="file-panel" role="tabpanel">
                                <div class="mb-3">
                                    <label for="articleFile" class="form-label">Upload Article File *</label>
                                    <div class="upload-area" id="uploadArea">
                                        <i data-lucide="upload-cloud" class="icon-xl text-muted mb-3"></i>
                                        <p class="mb-2">Click to upload or drag and drop</p>
                                        <p class="text-muted small">Only .txt files (Max 5MB)</p>
                                        <p id="fileName" class="text-primary small mt-2"></p>
                                    </div>
                                    <input type="file" class="d-none" id="articleFile" name="article_file" accept=".txt">
                                </div>
                                
                                <input type="hidden" name="submission_type" value="file">
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i data-lucide="check-circle" class="icon-sm"></i>
                                Analyze Article
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Information Panel -->
            <div class="card mt-4 border-0 bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i data-lucide="info" class="icon-sm"></i>
                        What happens next?
                    </h5>
                    <ul class="mb-0">
                        <li>Our AI analyzes linguistic patterns and credibility indicators</li>
                        <li>Claims are cross-referenced with fact-checking databases</li>
                        <li>You receive a detailed credibility score and explanation</li>
                        <li>Suspicious claims and fact-check matches are highlighted</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize file upload
document.addEventListener('DOMContentLoaded', function() {
    EklaroApp.initFileUpload();
    
    // Form submission
    const form = document.getElementById('validateForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get active tab
        const activeTab = document.querySelector('.tab-pane.active');
        const submissionType = activeTab.querySelector('input[name="submission_type"]').value;
        
        // Validate based on submission type
        let isValid = false;
        
        if (submissionType === 'text') {
            const text = document.getElementById('articleText').value.trim();
            if (text.length < <?php echo MIN_ARTICLE_LENGTH; ?>) {
                EklaroApp.showAlert('Article must be at least <?php echo MIN_ARTICLE_LENGTH; ?> characters', 'danger');
                return;
            }
            if (text.length > <?php echo MAX_ARTICLE_LENGTH; ?>) {
                EklaroApp.showAlert('Article must not exceed <?php echo MAX_ARTICLE_LENGTH; ?> characters', 'danger');
                return;
            }
            isValid = true;
        } else if (submissionType === 'url') {
            const url = document.getElementById('articleUrl').value.trim();
            if (!url) {
                EklaroApp.showAlert('Please enter a valid URL', 'danger');
                return;
            }
            isValid = true;
        } else if (submissionType === 'file') {
            const file = document.getElementById('articleFile').files[0];
            if (!file) {
                EklaroApp.showAlert('Please select a file to upload', 'danger');
                return;
            }
            isValid = true;
        }
        
        if (isValid) {
            EklaroApp.showLoading('Analyzing article...');
            
            // Submit form via AJAX
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                EklaroApp.hideLoading();
                
                if (data.success) {
                    // Redirect to results page
                    window.location.href = '<?php echo APP_URL; ?>/results?id=' + data.article_id;
                } else {
                    EklaroApp.showAlert(data.message || 'Validation failed', 'danger');
                }
            })
            .catch(error => {
                EklaroApp.hideLoading();
                EklaroApp.showAlert('An error occurred. Please try again.', 'danger');
                console.error('Error:', error);
            });
        }
    });
    
    // Re-initialize Lucide icons after tab changes
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(button => {
        button.addEventListener('shown.bs.tab', function() {
            lucide.createIcons();
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
