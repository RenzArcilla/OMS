<?php
// Simple router - check which page to show
$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'signup':
        include '../templates/signin.php';
        break;
    case 'hero':
        include '../templates/hero.php';
        break;
    case 'dashboard':
        include '../templates/dashboard.php';
        break;
    case 'settings':
        include '../templates/settings.php';
        break;
    case 'logout':
        include '../templates/logout.php';
        break;
    default:
        include '../templates/login.php';
        break;
}
?>
