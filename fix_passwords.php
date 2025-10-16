<?php
// Script to fix user passwords HAHHAH

require_once 'config/config.php';
require_once 'includes/Database.php';

use Eklaro\Database;

$db = Database::getInstance();

// Hash the passwords
$adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
$userPassword = password_hash('user123', PASSWORD_DEFAULT);

// Update admin password
$stmt = $db->prepare("UPDATE users SET password = ? WHERE email = 'admin@eklaro.com'");
$stmt->bind_param("s", $adminPassword);
$stmt->execute();

// Update user password
$stmt = $db->prepare("UPDATE users SET password = ? WHERE email = 'user@eklaro.com'");
$stmt->bind_param("s", $userPassword);
$stmt->execute();

echo "Passwords updated successfully!\n";
echo "Admin: admin@eklaro.com / admin123\n";
echo "User: user@eklaro.com / user123\n";
?>
