<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT admin_id, username, password FROM admins WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['admin_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = 'admin';
                header("Location: /admin/dashboard.php");
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }
        mysqli_stmt_close($stmt);
    }
}

$page_title = 'Admin Login';
include __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="login-box">
        <h2>Admin Login</h2>

        <?php if ($error): ?>
            <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-full">Login</button>
        </form>
        <a href="/auth/login.php" class="back-link">Back to Role Selection</a>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
