<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/Auth.php';

use Eklaro\Auth;

$auth = new Auth();
$auth->requireAdmin();

require_once __DIR__ . '/../includes/header.php';
