<?php
require_once 'config/config.php';
require_once 'includes/Database.php';
require_once 'includes/Auth.php';

use Eklaro\Auth;

$auth = new Auth();
$auth->logout();

header('Location: ' . APP_URL . '/login');
exit;
