<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "auth/admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin - Library Management System</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<div class="dashboard-header">
    <h2>Admin Panel</h2>
    <nav>
        <a href="<?= BASE_URL ?>admin/dashboard.php">Dashboard</a>
        <a href="<?= BASE_URL ?>admin/books.php">Books</a>
        <a href="<?= BASE_URL ?>admin/librarians.php">Librarians</a>
        <a href="<?= BASE_URL ?>admin/members.php">Members</a>
        <a href="<?= BASE_URL ?>admin/reports.php">Reports</a>
        <a href="<?= BASE_URL ?>auth/logout.php">Logout</a>
    </nav>
</div>
