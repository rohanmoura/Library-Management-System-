<?php
$page_title = 'Add Book';
$body_class = 'bg-add-book';
include __DIR__ . '/admin_header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $quantity = (int) ($_POST['quantity'] ?? 0);

    if (empty($title) || empty($author) || $quantity < 1) {
        $error = 'Please fill in Title, Author and Quantity (min 1).';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO books (title, author, category, quantity, available_quantity) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssii", $title, $author, $category, $quantity, $quantity);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: " . BASE_URL . "admin/books.php?msg=added");
        exit;
    }
}
?>

<div class="dashboard-body">
    <h3>Add New Book</h3>

    <?php if ($error): ?>
        <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="" class="login-box">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author" value="<?php echo htmlspecialchars($_POST['author'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" min="1" value="<?php echo htmlspecialchars($_POST['quantity'] ?? '1'); ?>" required>
        </div>
        <button type="submit" class="btn btn-full">Add Book</button>
    </form>
    <a href="<?= BASE_URL ?>admin/books.php" class="back-link">Back to Books</a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
