<?php
$page = $_GET['page'] ?? 'home';
$activePage = $page;

include '../includes/header.php';

switch ($page) {
    case 'login':
        include '../templates/login.php';
        break;
    case 'register':
        include '../templates/register.php';
        break;
    case 'upload':
        include '../templates/upload.php';
        break;
    default:
        include '../templates/index.php';
        break;
}

include '../includes/footer.php';
?>