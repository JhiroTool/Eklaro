    </main>
    
    <!-- Footer -->
    <footer class="footer-modern mt-5 py-5 text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="d-flex align-items-center text-white">
                        <i data-lucide="shield-check" class="me-2"></i>
                        Eklaro
                    </h5>
                    <p class="text-white-50">
                        Combating misinformation and promoting digital literacy through AI-powered validation.
                    </p>
                </div>
                
                <div class="col-md-4 mb-3">
                    <h6 class="text-white">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo APP_URL; ?>" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="<?php echo APP_URL; ?>/validate" class="text-white-50 text-decoration-none">Validate Article</a></li>
                        <li><a href="<?php echo APP_URL; ?>/about" class="text-white-50 text-decoration-none">About</a></li>
                        <li><a href="<?php echo APP_URL; ?>/contact" class="text-white-50 text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4 mb-3">
                    <h6 class="text-white">Target Audience</h6>
                    <ul class="list-unstyled text-white-50">
                        <li><i data-lucide="graduation-cap" class="icon-sm"></i> Students & Educators</li>
                        <li><i data-lucide="newspaper" class="icon-sm"></i> Journalists</li>
                        <li><i data-lucide="microscope" class="icon-sm"></i> Researchers</li>
                        <li><i data-lucide="users" class="icon-sm"></i> General Public</li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-light opacity-25">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-white-50">&copy; <?php echo date('Y'); ?> Eklaro. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white-50 text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-white-50 text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
    
    <!-- Custom JS -->
    <script src="<?php echo APP_URL; ?>/assets/js/main.js"></script>
</body>
</html>
