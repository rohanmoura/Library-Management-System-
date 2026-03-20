<?php
$page_title = 'Issue Book';
$body_class = 'bg-add-book';
include __DIR__ . '/librarian_header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = (int) ($_POST['member_id'] ?? 0);
    $book_id = (int) ($_POST['book_id'] ?? 0);

    if ($member_id === 0 || $book_id === 0) {
        $error = 'Please select both a member and a book.';
    } else {
        // Check available quantity
        $stmt = mysqli_prepare($conn, "SELECT available_quantity FROM books WHERE book_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $book_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $book = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$book || $book['available_quantity'] <= 0) {
            $error = 'This book is not available for issue.';
        } else {
            // Insert into book_issues
            $issue_date = date('Y-m-d');
            $status = 'Approved';
            $stmt = mysqli_prepare($conn, "INSERT INTO book_issues (member_id, book_id, issue_date, status) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iiss", $member_id, $book_id, $issue_date, $status);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Reduce available_quantity
            $stmt = mysqli_prepare($conn, "UPDATE books SET available_quantity = available_quantity - 1 WHERE book_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $book_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $success = 'Book issued successfully.';
        }
    }
}

// Fetch members and available books for dropdowns
$members = mysqli_query($conn, "SELECT member_id, name FROM member_details ORDER BY name");
$books_list = mysqli_query($conn, "SELECT book_id, title, author, available_quantity FROM books WHERE available_quantity > 0 ORDER BY title");
?>

<div class="dashboard-body">
    <h3>Issue a Book</h3>

    <?php if ($error): ?>
        <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="msg-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="" class="login-box">
        <div class="form-group">
            <label>Select Member</label>
            <select name="member_id" required>
                <option value="">-- Choose Member --</option>
                <?php while ($m = mysqli_fetch_assoc($members)): ?>
                    <option value="<?php echo $m['member_id']; ?>"><?php echo htmlspecialchars($m['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Select Book</label>
            <select name="book_id" required>
                <option value="">-- Choose Book --</option>
                <?php while ($b = mysqli_fetch_assoc($books_list)): ?>
                    <option value="<?php echo $b['book_id']; ?>"><?php echo htmlspecialchars($b['title'] . ' - ' . $b['author'] . ' (' . $b['available_quantity'] . ' available)'); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-full">Issue Book</button>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
