<?php
$page_title = 'Edit Book';
$body_class = 'bg-edit-book';
include __DIR__ . '/admin_header.php';

$id = (int) ($_GET['id'] ?? 0);
$error = '';

// Fetch book
$stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE book_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$book) {
    header("Location: " . BASE_URL . "admin/books.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $quantity = (int) ($_POST['quantity'] ?? 0);
    $available = (int) ($_POST['available_quantity'] ?? 0);

    if (empty($title) || empty($author) || $quantity < 0) {
        $error = 'Please fill in Title and Author.';
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE books SET title = ?, author = ?, category = ?, quantity = ?, available_quantity = ? WHERE book_id = ?");
        mysqli_stmt_bind_param($stmt, "sssiii", $title, $author, $category, $quantity, $available, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: " . BASE_URL . "admin/books.php?msg=updated");
        exit;
    }
}
?>

<div class="dashboard-body">
    <h3>Edit Book</h3>

    <?php if ($error): ?>
        <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="" class="login-box">
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        </div>
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($book['category']); ?>">
        </div>
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" min="0" value="<?php echo $book['quantity']; ?>" required>
        </div>
        <div class="form-group">
            <label>Available Quantity</label>
            <input type="number" name="available_quantity" min="0" value="<?php echo $book['available_quantity']; ?>" required>
        </div>
        <button type="submit" class="btn btn-full">Update Book</button>
    </form>
    <a href="<?= BASE_URL ?>admin/books.php" class="back-link">Back to Books</a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
