<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian') {
    header("Location: " . BASE_URL . "auth/librarian_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Librarian - Library Management System</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body class="<?= isset($body_class) ? $body_class : '' ?>">

<div class="dashboard-header">
    <h2>Librarian Panel</h2>
       <?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
    <nav>
    <a href="<?= BASE_URL ?>librarian/dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
    
    <a href="<?= BASE_URL ?>librarian/books.php" class="<?= $current_page == 'books.php' ? 'active' : '' ?>">Books</a>
    
    <a href="<?= BASE_URL ?>librarian/issue_book.php" class="<?= $current_page == 'issue_book.php' ? 'active' : '' ?>">Issue Book</a>
    
    <a href="<?= BASE_URL ?>librarian/return_book.php" class="<?= $current_page == 'return_book.php' ? 'active' : '' ?>">Return Book</a>
    
    <a href="<?= BASE_URL ?>librarian/history.php" class="<?= $current_page == 'history.php' ? 'active' : '' ?>">History</a>
    
    <a href="<?= BASE_URL ?>auth/logout.php" class="logout">Logout</a>
</nav>
</div>
