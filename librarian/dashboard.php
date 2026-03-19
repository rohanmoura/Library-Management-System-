<?php
session_start();
require_once __DIR__ . '/../config/config.php';

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
    <title>Librarian Dashboard - Library Management System</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<div class="dashboard-header">
    <h2>Librarian Dashboard</h2>
    <a href="<?= BASE_URL ?>auth/logout.php">Logout</a>
</div>

<div class="dashboard-body">
    <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
    <p>You are logged in as <strong>Librarian</strong>.</p>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
