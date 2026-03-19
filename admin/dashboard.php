<?php
session_start();

// Session protection - admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /auth/admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Library Management System</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<div class="dashboard-header">
    <h2>Admin Dashboard</h2>
    <a href="/auth/logout.php">Logout</a>
</div>

<div class="dashboard-body">
    <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
    <p>You are logged in as <strong>Admin</strong>.</p>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>