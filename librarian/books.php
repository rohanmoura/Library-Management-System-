<?php
$page_title = 'View Books';
$body_class = 'bg-manage-books';
include __DIR__ . '/librarian_header.php';

$books = mysqli_query($conn, "SELECT * FROM books ORDER BY book_id ASC");
?>

<div class="dashboard-body">
    <h3>All Books</h3>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Total Qty</th>
                <th>Available</th>
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
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
