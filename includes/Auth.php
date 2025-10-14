<?php
namespace Eklaro;

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT id, email, password, full_name, role, is_active FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
        
        $user = $result->fetch_assoc();
        
        if (!$user['is_active']) {
            return ['success' => false, 'message' => 'Account is inactive'];
        }
        
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
        
        // Update last login
        $updateStmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $updateStmt->bind_param("i", $user['id']);
        $updateStmt->execute();
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        
        // Log activity
        $this->logActivity($user['id'], 'login');
        
        return ['success' => true, 'user' => $user];
    }
    
    public function register($email, $password, $fullName) {
        // Check if email exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            return ['success' => false, 'message' => 'Email already exists'];
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user
        $stmt = $this->db->prepare("INSERT INTO users (email, password, full_name, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $email, $hashedPassword, $fullName);
        
        if ($stmt->execute()) {
            $userId = $this->db->lastInsertId();
            $this->logActivity($userId, 'register');
            return ['success' => true, 'message' => 'Registration successful'];
        }
        
        return ['success' => false, 'message' => 'Registration failed'];
    }
    
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->logActivity($_SESSION['user_id'], 'logout');
        }
        
        session_destroy();
        return ['success' => true];
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    public function isAdmin() {
        return $this->isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
    
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'],
            'name' => $_SESSION['user_name'],
            'role' => $_SESSION['user_role']
        ];
    }
    
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
    }
    
    public function requireAdmin() {
        if (!$this->isAdmin()) {
            header('Location: ' . APP_URL . '/403');
            exit;
        }
    }
    
    private function logActivity($userId, $action) {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        $stmt = $this->db->prepare("INSERT INTO activity_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $action, $ipAddress, $userAgent);
        $stmt->execute();
    }
    
    public function generateCSRFToken() {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }
    
    public function validateCSRFToken($token) {
        return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }
}
