<?php
$page_title = 'Login';
include __DIR__ . '/../includes/header.php';
?>

<div class="role-selector">
    <h1>Library Management System</h1>
    <p>Select your role to login</p>

    <div class="role-cards">
        <a href="/auth/admin_login.php" class="role-card">
            <h3>Admin</h3>
            <p>System Administrator</p>
        </a>
        <a href="/auth/librarian_login.php" class="role-card">
            <h3>Librarian</h3>
            <p>Library Staff</p>
        </a>
        <a href="/auth/member_login.php" class="role-card">
            <h3>Member</h3>
            <p>Library Member</p>
        </a>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>