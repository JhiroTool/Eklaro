<?php
$pageTitle = 'Validate Article - Eklaro';
require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-9 mx-auto">
            <!-- Alert Container -->
            <div id="alertContainer"></div>
            
            <?php
            // Show guest validation limit banner
            if (!$auth->isLoggedIn()):
                $guestValidations = $_SESSION['guest_validations'] ?? 0;
                $remainingValidations = 3 - $guestValidations;
            ?>
            <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                <i data-lucide="info" class="icon-sm"></i>
                <strong>Guest User:</strong> You have <strong><?php echo $remainingValidations; ?></strong> free validation<?php echo $remainingValidations != 1 ? 's' : ''; ?> remaining. 
                <a href="<?php echo APP_URL; ?>/register" class="alert-link">Register</a> or 
                <a href="<?php echo APP_URL; ?>/login" class="alert-link">Login</a> for unlimited validations!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
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
                    // Show remaining validations for guests
                    if (data.guest_validations_remaining !== null && data.guest_validations_remaining >= 0) {
                        if (data.guest_validations_remaining === 0) {
                            showLimitModal('last');
                        } else {
                            EklaroApp.showAlert(`Validation successful! You have ${data.guest_validations_remaining} free validation(s) remaining.`, 'info');
                        }
                    }
                    
                    // Redirect to results page after a short delay
                    setTimeout(() => {
                        window.location.href = '<?php echo APP_URL; ?>/results?id=' + data.article_id;
                    }, data.guest_validations_remaining !== null && data.guest_validations_remaining === 0 ? 3000 : 1500);
                } else {
                    // Check if login is required
                    if (data.require_login) {
                        showLimitModal('exceeded');
                    } else {
                        EklaroApp.showAlert(data.message || 'Validation failed', 'danger');
                    }
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
    
    // Show limit modal with blur effect
    function showLimitModal(type) {
        const modalHTML = `
            <div id="limitModal" class="limit-modal-overlay">
                <div class="limit-modal">
                    <div class="limit-modal-icon">
                        ${type === 'exceeded' ? 
                            '<i data-lucide="lock" class="icon-xxl text-warning"></i>' : 
                            '<i data-lucide="alert-circle" class="icon-xxl text-info"></i>'}
                    </div>
                    <h3 class="mb-3">${type === 'exceeded' ? 
                        'Validation Limit Reached' : 
                        'Last Free Validation Used!'}</h3>
                    <p class="text-muted mb-4">
                        ${type === 'exceeded' ? 
                            'You have reached the limit of <strong>3 free validations</strong>. Create an account to continue validating articles with unlimited access!' :
                            'This was your <strong>last free validation</strong>! Register now to get unlimited validations and save your validation history.'}
                    </p>
                    <div class="d-grid gap-2">
                        <a href="<?php echo APP_URL; ?>/register" class="btn btn-primary btn-lg">
                            <i data-lucide="user-plus" class="icon-sm"></i> Create Free Account
                        </a>
                        <a href="<?php echo APP_URL; ?>/login" class="btn btn-outline-primary">
                            <i data-lucide="log-in" class="icon-sm"></i> Already have an account? Login
                        </a>
                        ${type === 'last' ? '<button onclick="closeLimitModal()" class="btn btn-link text-muted">Continue to Results</button>' : ''}
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        lucide.createIcons();
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }
    
    // Close limit modal
    window.closeLimitModal = function() {
        const modal = document.getElementById('limitModal');
        if (modal) {
            modal.remove();
            document.body.style.overflow = '';
        }
    };
});
</script>

<style>
.limit-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease-out;
}

.limit-modal {
    background: white;
    border-radius: 16px;
    padding: 3rem 2rem;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    text-align: center;
    animation: slideUp 0.3s ease-out;
}

.limit-modal-icon {
    margin-bottom: 1.5rem;
}

.icon-xxl {
    width: 80px;
    height: 80px;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>
