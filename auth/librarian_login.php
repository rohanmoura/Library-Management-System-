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
        $stmt = mysqli_prepare($conn, "SELECT ll.login_id, ll.librarian_id, ll.username, ll.password, ld.status
                                        FROM librarian_login ll
                                        JOIN librarian_details ld ON ll.librarian_id = ld.librarian_id
                                        WHERE ll.username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['status'] !== 'Approved') {
                $error = 'Your account is not approved yet.';
            } elseif (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['librarian_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = 'librarian';

                // Update last_login
                $update = mysqli_prepare($conn, "UPDATE librarian_login SET last_login = NOW() WHERE login_id = ?");
                mysqli_stmt_bind_param($update, "i", $row['login_id']);
                mysqli_stmt_execute($update);
                mysqli_stmt_close($update);

                header("Location: /librarian/dashboard.php");
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

$page_title = 'Librarian Login';
include __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="login-box">
        <h2>Librarian Login</h2>

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
