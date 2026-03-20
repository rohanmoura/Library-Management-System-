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
<body>

<div class="dashboard-header">
    <h2>Librarian Panel</h2>
    <nav>
        <a href="<?= BASE_URL ?>librarian/dashboard.php">Dashboard</a>
        <a href="<?= BASE_URL ?>librarian/books.php">Books</a>
        <a href="<?= BASE_URL ?>librarian/issue_book.php">Issue Book</a>
        <a href="<?= BASE_URL ?>librarian/return_book.php">Return Book</a>
        <a href="<?= BASE_URL ?>librarian/history.php">History</a>
        <a href="<?= BASE_URL ?>auth/logout.php">Logout</a>
    </nav>
</div>
