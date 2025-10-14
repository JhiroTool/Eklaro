<?php
/**
 * Eklaro Configuration File
 * Copy this file to config.php and update with your settings
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'eklaro_db');

// Application Configuration
define('APP_NAME', 'Eklaro');
define('APP_URL', 'http://localhost/Eklaro');
define('APP_ENV', 'development'); // development, production

// Security
define('SESSION_LIFETIME', 3600); // 1 hour in seconds
define('CSRF_TOKEN_NAME', 'csrf_token');

// API Keys
define('GOOGLE_FACT_CHECK_API_KEY', 'YOUR_API_KEY_HERE');

// File Upload Settings
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_FILE_TYPES', ['txt']);
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// Validation Settings
define('MIN_ARTICLE_LENGTH', 100); // Minimum characters
define('MAX_ARTICLE_LENGTH', 50000); // Maximum characters

// Credibility Score Thresholds
define('SCORE_VALID_MIN', 70);
define('SCORE_PARTIAL_MIN', 40);
// Below SCORE_PARTIAL_MIN is considered Invalid

// Pagination
define('ITEMS_PER_PAGE', 10);

// Email Configuration (for future use)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-password');
define('SMTP_FROM', 'noreply@eklaro.com');

// Timezone
date_default_timezone_set('UTC');

// Error Reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
