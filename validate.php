<?php
$pageTitle = 'Validate Article - Eklaro';
require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-9 mx-auto">
            <!-- Alert Container -->
            <div id="alertContainer"></div>
            
            <!-- Page Header -->
            <div class="text-center mb-5">
                <div class="mb-4">
                    <i data-lucide="file-search" class="icon-xl" style="color: var(--primary-color);"></i>
                </div>
                <h1 class="display-5 fw-bold mb-3" style="color: var(--primary-color);">Validate Article</h1>
                <p class="lead text-muted fs-5">
                    Submit an article for <strong>AI-powered</strong> credibility analysis and fact-checking
                </p>
            </div>
            
            <!-- Submission Form -->
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <form id="validateForm" action="<?php echo APP_URL; ?>/api/submit-article.php" method="POST" enctype="multipart/form-data">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo $auth->generateCSRFToken(); ?>">
                        
                        <!-- Submission Type Tabs -->
                        <ul class="nav nav-tabs mb-5" id="submissionTabs" role="tablist">
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
                                <div class="mb-4">
                                    <label for="articleTitle" class="form-label fw-bold">Article Title</label>
                                    <input type="text" class="form-control form-control-lg" id="articleTitle" name="article_title" placeholder="Enter article title">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="articleText" class="form-label fw-bold">Article Content *</label>
                                    <textarea class="form-control form-control-lg" id="articleText" name="article_text" rows="12" 
                                              placeholder="Paste the article content here..." style="resize: vertical;"></textarea>
                                    <div class="form-text mt-2">
                                        <i data-lucide="info" class="icon-sm"></i>
                                        Minimum <?php echo MIN_ARTICLE_LENGTH; ?> characters, maximum <?php echo MAX_ARTICLE_LENGTH; ?> characters
                                    </div>
                                </div>
                                
                                <input type="hidden" name="submission_type" value="text">
                            </div>
                            
                            <!-- URL Input -->
                            <div class="tab-pane fade" id="url-panel" role="tabpanel">
                                <div class="mb-4">
                                    <label for="articleUrl" class="form-label fw-bold">Article URL *</label>
                                    <input type="url" class="form-control form-control-lg" id="articleUrl" name="article_url" 
                                           placeholder="https://example.com/article">
                                    <div class="form-text mt-2">
                                        <i data-lucide="link" class="icon-sm"></i>
                                        Enter the full URL of the article you want to validate
                                    </div>
                                </div>
                                
                                <div class="alert alert-info border-0 shadow-sm">
                                    <i data-lucide="info" class="icon-sm"></i>
                                    <strong>Note:</strong> URL extraction is currently in beta. For best results, paste the article text directly.
                                </div>
                                
                                <input type="hidden" name="submission_type" value="url">
                            </div>
                            
                            <!-- File Upload -->
                            <div class="tab-pane fade" id="file-panel" role="tabpanel">
                                <div class="mb-4">
                                    <label for="articleFile" class="form-label fw-bold">Upload Article File *</label>
                                    <div class="upload-area" id="uploadArea">
                                        <i data-lucide="upload-cloud" class="icon-xl mb-3" style="color: var(--primary-color);"></i>
                                        <h5 class="fw-bold mb-2">Click to upload or drag and drop</h5>
                                        <p class="text-muted mb-0">TXT, PDF, DOC, DOCX files (Max 5MB)</p>
                                        <p id="fileName" class="text-primary fw-bold mt-3"></p>
                                    </div>
                                    <input type="file" class="d-none" id="articleFile" name="article_file" accept=".txt,.pdf,.doc,.docx">
                                </div>
                                
                                <input type="hidden" name="submission_type" value="file">
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i data-lucide="check-circle" class="icon-sm"></i>
                                Analyze Article
                                <i data-lucide="arrow-right" class="icon-sm ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Information Panel -->
            <div class="card mt-5 border-0 glass-effect">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">
                        <i data-lucide="info" class="icon-sm" style="color: var(--primary-color);"></i>
                        What happens next?
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i data-lucide="brain" class="icon-sm me-2 mt-1" style="color: var(--primary-color);"></i>
                                <p class="mb-0">Our AI analyzes linguistic patterns and credibility indicators</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i data-lucide="search-check" class="icon-sm me-2 mt-1" style="color: var(--primary-color);"></i>
                                <p class="mb-0">Claims are cross-referenced with fact-checking databases</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i data-lucide="award" class="icon-sm me-2 mt-1" style="color: var(--primary-color);"></i>
                                <p class="mb-0">You receive a detailed credibility score and explanation</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i data-lucide="alert-triangle" class="icon-sm me-2 mt-1" style="color: var(--primary-color);"></i>
                                <p class="mb-0">Suspicious claims and fact-check matches are highlighted</p>
                            </div>
                        </div>
                    </div>
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
            
            // Create FormData and only include active tab fields
            const formData = new FormData();
            
            // Add CSRF token
            formData.append('csrf_token', form.querySelector('input[name="csrf_token"]').value);
            formData.append('submission_type', submissionType);
            
            // Add fields based on submission type
            if (submissionType === 'text') {
                formData.append('article_title', document.getElementById('articleTitle').value);
                formData.append('article_text', document.getElementById('articleText').value);
            } else if (submissionType === 'url') {
                formData.append('article_url', document.getElementById('articleUrl').value);
            } else if (submissionType === 'file') {
                const fileInput = document.getElementById('articleFile');
                if (fileInput.files[0]) {
                    formData.append('article_file', fileInput.files[0]);
                }
            }
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Response is not JSON:', text);
                        throw new Error('Server returned an error: ' + text.substring(0, 200));
                    }
                });
            })
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
                EklaroApp.showAlert('Error: ' + error.message, 'danger');
                console.error('Full error:', error);
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
