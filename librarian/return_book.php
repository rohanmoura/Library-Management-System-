<?php
$page_title = 'Return Book';
$body_class = 'bg-view-members';
include __DIR__ . '/librarian_header.php';

$success = '';

// Handle return
if (isset($_GET['return_id'])) {
    $issue_id = (int) $_GET['return_id'];

    // Get book_id from the issue
    $stmt = mysqli_prepare($conn, "SELECT book_id FROM book_issues WHERE issue_id = ? AND status = 'Approved'");
    mysqli_stmt_bind_param($stmt, "i", $issue_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $issue = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($issue) {
        // Update issue status to Done
        $stmt = mysqli_prepare($conn, "UPDATE book_issues SET status = 'Done' WHERE issue_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $issue_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Increase available_quantity
        $stmt = mysqli_prepare($conn, "UPDATE books SET available_quantity = available_quantity + 1 WHERE book_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $issue['book_id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $success = 'Book returned successfully.';
    }
}

// Fetch issued books (status = Approved)
$issued = mysqli_query($conn, "SELECT bi.issue_id, bi.issue_date, b.title, b.author, md.name AS member_name
                                FROM book_issues bi
                                JOIN books b ON bi.book_id = b.book_id
                                JOIN member_details md ON bi.member_id = md.member_id
                                WHERE bi.status = 'Approved'
                                ORDER BY bi.issue_date ASC");
?>

<div class="dashboard-body">
    <h3>Return Book</h3>

    <?php if ($success): ?>
        <div class="msg-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Book</th>
                <th>Author</th>
                <th>Member</th>
                <th>Issue Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($issued) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($issued)): ?>
                <tr>
                    <td><?php echo $row['issue_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><?php echo htmlspecialchars($row['member_name']); ?></td>
                    <td><?php echo $row['issue_date']; ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>librarian/return_book.php?return_id=<?php echo $row['issue_id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Confirm return?')">Return</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center; padding:20px;">No books currently issued.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
