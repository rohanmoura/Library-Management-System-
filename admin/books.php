<?php
$page_title = 'Manage Books';
$body_class = 'bg-manage-books';
include __DIR__ . '/admin_header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM books WHERE book_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: " . BASE_URL . "admin/books.php?msg=deleted");
    exit;
}

$books = mysqli_query($conn, "SELECT * FROM books ORDER BY book_id ASC");
?>

<div class="dashboard-body">
    <div class="top-bar">
        <h3>Manage Books</h3>
        <a href="<?= BASE_URL ?>admin/add_book.php" class="btn">Add Book</a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="msg-success">
            <?php
            if ($_GET['msg'] === 'added') echo 'Book added successfully.';
            elseif ($_GET['msg'] === 'updated') echo 'Book updated successfully.';
            elseif ($_GET['msg'] === 'deleted') echo 'Book deleted successfully.';
            ?>
        </div>
    <?php endif; ?>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Qty</th>
                <th>Available</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($book = mysqli_fetch_assoc($books)): ?>
            <tr>
                <td><?php echo $book['book_id']; ?></td>
                <td><?php echo htmlspecialchars($book['title']); ?></td>
                <td><?php echo htmlspecialchars($book['author']); ?></td>
                <td><?php echo htmlspecialchars($book['category']); ?></td>
                <td><?php echo $book['quantity']; ?></td>
                <td><?php echo $book['available_quantity']; ?></td>
                <td class="actions">
                    <a href="<?= BASE_URL ?>admin/edit_book.php?id=<?php echo $book['book_id']; ?>" class="btn btn-sm">Edit</a>
                    <a href="<?= BASE_URL ?>admin/books.php?delete=<?php echo $book['book_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this book?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
