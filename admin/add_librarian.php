<?php
$page_title = 'Add Librarian';
$body_class = 'bg-add-librarian';
include __DIR__ . '/admin_header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $experience = (int) ($_POST['experience'] ?? 0);
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($username) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Check if username already exists
        $check = mysqli_prepare($conn, "SELECT login_id FROM librarian_login WHERE username = ?");
        mysqli_stmt_bind_param($check, "s", $username);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $error = 'Username already exists.';
        } else {
            // Insert librarian details
            $stmt = mysqli_prepare($conn, "INSERT INTO librarian_details (name, specialization, experience, phone, email, status) VALUES (?, ?, ?, ?, ?, 'Approved')");
            mysqli_stmt_bind_param($stmt, "ssiss", $name, $specialization, $experience, $phone, $email);
            mysqli_stmt_execute($stmt);
            $librarian_id = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);

            // Insert login credentials
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO librarian_login (librarian_id, username, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iss", $librarian_id, $username, $hashed);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            header("Location: " . BASE_URL . "admin/librarians.php?msg=added");
            exit;
        }
        mysqli_stmt_close($check);
    }
}
?>

<div class="dashboard-body">
    <h3>Add New Librarian</h3>

    <?php if ($error): ?>
        <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="" class="login-box">
        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Specialization</label>
            <input type="text" name="specialization" value="<?php echo htmlspecialchars($_POST['specialization'] ?? ''); ?>" placeholder="e.g., Cataloging, Circulation">
        </div>
        <div class="form-group">
            <label>Experience (years)</label>
            <input type="number" name="experience" min="0" value="<?php echo htmlspecialchars($_POST['experience'] ?? '0'); ?>">
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
        </div>
        <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">
        <div class="form-group">
            <label>Username *</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Password *</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-full">Add Librarian</button>
    </form>
    <a href="<?= BASE_URL ?>admin/librarians.php" class="back-link">Back to Librarians</a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
