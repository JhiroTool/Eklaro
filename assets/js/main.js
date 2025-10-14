// Eklaro Main JavaScript

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Scroll reveal animation
    initScrollReveal();
    
    // Animate numbers on stats cards
    animateNumbers();
    
    // Add parallax effect to hero section
    initParallax();
});

// File upload handling
function initFileUpload() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('articleFile');
    
    if (!uploadArea || !fileInput) return;
    
    // Click to upload
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });
    
    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });
    
    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });
}

async function handleFileSelect(file) {
    const uploadArea = document.getElementById('uploadArea');
    const fileName = document.getElementById('fileName');
    
    if (fileName) {
        fileName.textContent = `Selected: ${file.name}`;
    }
    
    // Validate file type
    const allowedExtensions = ['txt', 'pdf', 'doc', 'docx'];
    const fileExtension = file.name.split('.').pop().toLowerCase();
    
    if (!allowedExtensions.includes(fileExtension)) {
        showAlert('Please upload a TXT, PDF, DOC, or DOCX file', 'danger');
        return;
    }
    
    // Validate file size (5MB)
    if (file.size > 5242880) {
        showAlert('File size must be less than 5MB', 'danger');
        return;
    }
    
    // If it's a PDF or DOCX, extract text using JavaScript libraries
    if (fileExtension === 'pdf' || fileExtension === 'docx') {
        try {
            showLoading(`Extracting text from ${fileExtension.toUpperCase()}...`);
            
            let text = '';
            if (fileExtension === 'pdf') {
                text = await extractTextFromPDF(file);
            } else if (fileExtension === 'docx') {
                text = await extractTextFromDOCX(file);
            }
            
            hideLoading();
            
            if (text && text.length > 100) {
                // Switch to paste text tab and populate it
                const textTab = document.querySelector('[data-bs-target="#text-panel"]');
                const textArea = document.getElementById('articleText');
                const titleInput = document.getElementById('articleTitle');
                
                if (textTab && textArea) {
                    // Activate text tab
                    const tab = new bootstrap.Tab(textTab);
                    tab.show();
                    
                    // Populate fields
                    textArea.value = text;
                    if (titleInput) {
                        titleInput.value = file.name.replace(`.${fileExtension}`, '');
                    }
                    
                    showAlert(`${fileExtension.toUpperCase()} text extracted successfully! Review and submit.`, 'success');
                }
            } else {
                showAlert(`Could not extract sufficient text from ${fileExtension.toUpperCase()}. Please try copying the text manually.`, 'warning');
            }
        } catch (error) {
            hideLoading();
            console.error(`${fileExtension.toUpperCase()} extraction error:`, error);
            showAlert(`Failed to extract text from ${fileExtension.toUpperCase()}. You can still upload it and the server will try to process it.`, 'warning');
        }
    }
}

// Extract text from PDF using PDF.js
async function extractTextFromPDF(file) {
    const arrayBuffer = await file.arrayBuffer();
    const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
    
    let fullText = '';
    
    // Extract text from each page
    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
        const page = await pdf.getPage(pageNum);
        const textContent = await page.getTextContent();
        const pageText = textContent.items.map(item => item.str).join(' ');
        fullText += pageText + '\n\n';
    }
    
    return fullText.trim();
}

// Extract text from DOCX using Mammoth.js
async function extractTextFromDOCX(file) {
    const arrayBuffer = await file.arrayBuffer();
    const result = await mammoth.extractRawText({ arrayBuffer: arrayBuffer });
    return result.value.trim();
}

// Show loading overlay
function showLoading(message = 'Processing...') {
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay';
    overlay.id = 'loadingOverlay';
    overlay.innerHTML = `
        <div class="loading-spinner">
            <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">${message}</p>
        </div>
    `;
    document.body.appendChild(overlay);
}

// Hide loading overlay
function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.remove();
    }
}

// Show alert message
function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alertContainer');
    if (!alertContainer) {
        console.warn('Alert container not found');
        return;
    }
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.appendChild(alert);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    }, 5000);
}

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return false;
    }
    
    return true;
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showAlert('Copied to clipboard!', 'success');
    }).catch(err => {
        console.error('Failed to copy:', err);
        showAlert('Failed to copy to clipboard', 'danger');
    });
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return date.toLocaleDateString('en-US', options);
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Chart helper (for dashboard)
function createScoreChart(canvasId, score) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    
    // Simple canvas-based score visualization
    const ctx = canvas.getContext('2d');
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const radius = 80;
    
    // Background circle
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
    ctx.strokeStyle = '#e9ecef';
    ctx.lineWidth = 10;
    ctx.stroke();
    
    // Score arc
    const scoreAngle = (score / 100) * 2 * Math.PI;
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, -Math.PI / 2, -Math.PI / 2 + scoreAngle);
    
    // Color based on score
    if (score >= 70) {
        ctx.strokeStyle = '#198754';
    } else if (score >= 40) {
        ctx.strokeStyle = '#ffc107';
    } else {
        ctx.strokeStyle = '#dc3545';
    }
    
    ctx.lineWidth = 10;
    ctx.lineCap = 'round';
    ctx.stroke();
    
    // Score text
    ctx.fillStyle = '#212529';
    ctx.font = 'bold 32px Arial';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(Math.round(score), centerX, centerY);
}

// Scroll reveal animation
function initScrollReveal() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe elements with animation classes
    document.querySelectorAll('.feature-card, .stats-card, .card').forEach(el => {
        observer.observe(el);
    });
}

// Animate numbers
function animateNumbers() {
    const statsNumbers = document.querySelectorAll('.stats-number');
    
    statsNumbers.forEach(stat => {
        const target = parseInt(stat.textContent);
        if (isNaN(target)) return;
        
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                stat.textContent = target + (stat.textContent.includes('%') ? '%' : '');
                clearInterval(timer);
            } else {
                stat.textContent = Math.floor(current) + (stat.textContent.includes('%') ? '%' : '');
            }
        }, 16);
    });
}

// Parallax effect
function initParallax() {
    const heroSection = document.querySelector('.hero-section');
    if (!heroSection) return;
    
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.floating-element');
        
        parallaxElements.forEach((el, index) => {
            const speed = 0.5 + (index * 0.1);
            el.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href !== '') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});

// Add ripple effect to buttons
function addRippleEffect(e) {
    const button = e.currentTarget;
    const ripple = document.createElement('span');
    const rect = button.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = e.clientX - rect.left - size / 2;
    const y = e.clientY - rect.top - size / 2;
    
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    ripple.classList.add('ripple-effect');
    
    button.appendChild(ripple);
    
    setTimeout(() => ripple.remove(), 600);
}

document.querySelectorAll('.ripple').forEach(button => {
    button.addEventListener('click', addRippleEffect);
});

// Export functions for use in other scripts
window.EklaroApp = {
    initFileUpload,
    showLoading,
    hideLoading,
    showAlert,
    validateForm,
    copyToClipboard,
    formatDate,
    debounce,
    createScoreChart,
    initScrollReveal,
    animateNumbers,
    initParallax
};
