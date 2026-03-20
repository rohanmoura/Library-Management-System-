<?php
$page_title = 'Browse Books';
include __DIR__ . '/member_header.php';

$member_id = $_SESSION['user_id'];
$search = trim($_GET['search'] ?? '');
$success = $_GET['msg'] ?? '';
$error = $_GET['error'] ?? '';

// Build query with optional search
if (!empty($search)) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY title");
    $like = '%' . $search . '%';
    mysqli_stmt_bind_param($stmt, "ss", $like, $like);
    mysqli_stmt_execute($stmt);
    $books = mysqli_stmt_get_result($stmt);
} else {
    $books = mysqli_query($conn, "SELECT * FROM books ORDER BY title");
}

// Get this member's pending/approved book_ids to prevent duplicate requests
$stmt2 = mysqli_prepare($conn, "SELECT book_id FROM book_issues WHERE member_id = ? AND status IN ('Pending', 'Approved')");
mysqli_stmt_bind_param($stmt2, "i", $member_id);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$requested_books = [];
while ($r = mysqli_fetch_assoc($result2)) {
    $requested_books[] = $r['book_id'];
}
mysqli_stmt_close($stmt2);
?>

<div class="dashboard-body">
    <div class="top-bar">
        <h3>Browse Books</h3>
        <form method="GET" action="" style="display:flex; gap:8px;">
            <input type="text" name="search" placeholder="Search by title or author..." value="<?php echo htmlspecialchars($search); ?>" style="padding:8px 12px; border:1px solid #ccc; border-radius:4px; font-size:14px;">
            <button type="submit" class="btn btn-sm">Search</button>
            <?php if (!empty($search)): ?>
                <a href="<?= BASE_URL ?>member/books.php" class="btn btn-sm" style="background:#888;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if ($success === 'requested'): ?>
        <div class="msg-success">Book request submitted successfully.</div>
    <?php endif; ?>
    <?php if ($error === 'duplicate'): ?>
        <div class="msg-error">You already have a pending or active request for this book.</div>
    <?php elseif ($error === 'unavailable'): ?>
        <div class="msg-error">This book is currently not available.</div>
    <?php endif; ?>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Available</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($book = mysqli_fetch_assoc($books)): ?>
            <tr>
                <td><?php echo $book['book_id']; ?></td>
                <td><?php echo htmlspecialchars($book['title']); ?></td>
                <td><?php echo htmlspecialchars($book['author']); ?></td>
                <td><?php echo htmlspecialchars($book['category']); ?></td>
                <td><?php echo $book['available_quantity']; ?></td>
                <td>
                    <?php if (in_array($book['book_id'], $requested_books)): ?>
                        <span class="badge badge-pending">Requested</span>
                    <?php elseif ($book['available_quantity'] > 0): ?>
                        <a href="<?= BASE_URL ?>member/request_book.php?book_id=<?php echo $book['book_id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Request this book?')">Request</a>
                    <?php else: ?>
                        <span style="color:#999; font-size:13px;">Unavailable</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
