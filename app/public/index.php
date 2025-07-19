<?php
// Simple router - check which page to show
$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'signup':
        include '../templates/signin.php';
        break;
    default:
        include '../templates/login.php';
        break;
}
?>
