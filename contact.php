<?php
$pageTitle = 'Contact - Eklaro';
require_once 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$auth->validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $error = 'All fields are required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address';
        } else {
            // In a real application, send email here
            $success = 'Thank you for contacting us! We will get back to you soon.';
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-5">
                <i data-lucide="mail" class="icon-xl text-primary mb-3" style="width: 80px; height: 80px;"></i>
                <h1>Contact Us</h1>
                <p class="lead text-muted">Have questions? We'd love to hear from you.</p>
            </div>
            
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <div class="row mb-5">
                <div class="col-md-4 mb-3">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body">
                            <i data-lucide="mail" class="icon-lg text-primary mb-3"></i>
                            <h5>Email</h5>
                            <p class="text-muted">support@eklaro.com</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body">
                            <i data-lucide="message-circle" class="icon-lg text-primary mb-3"></i>
                            <h5>Support</h5>
                            <p class="text-muted">24/7 Help Center</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body">
                            <i data-lucide="github" class="icon-lg text-primary mb-3"></i>
                            <h5>GitHub</h5>
                            <p class="text-muted">Open Source</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Send us a message</h5>
                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?php echo $auth->generateCSRFToken(); ?>">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required
                                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required
                                   value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i data-lucide="send" class="icon-sm"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
