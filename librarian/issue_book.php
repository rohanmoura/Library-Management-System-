<?php
$page_title = 'Issue Book';
include __DIR__ . '/librarian_header.php';

$error = '';
$success = '';

// Handle approve pending request
if (isset($_GET['approve_id'])) {
    $issue_id = (int) $_GET['approve_id'];

    // Get issue details
    $stmt = mysqli_prepare($conn, "SELECT book_id FROM book_issues WHERE issue_id = ? AND status = 'Pending'");
    mysqli_stmt_bind_param($stmt, "i", $issue_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $issue = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($issue) {
        // Check availability
        $stmt = mysqli_prepare($conn, "SELECT available_quantity FROM books WHERE book_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $issue['book_id']);
        mysqli_stmt_execute($stmt);
        $book = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if ($book && $book['available_quantity'] > 0) {
            // Approve the request
            $stmt = mysqli_prepare($conn, "UPDATE book_issues SET status = 'Approved' WHERE issue_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $issue_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Reduce available quantity
            $stmt = mysqli_prepare($conn, "UPDATE books SET available_quantity = available_quantity - 1 WHERE book_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $issue['book_id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $success = 'Request approved and book issued.';
        } else {
            $error = 'Book is not available to approve this request.';
        }
    }
}

// Handle direct issue
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = (int) ($_POST['member_id'] ?? 0);
    $book_id = (int) ($_POST['book_id'] ?? 0);

    if ($member_id === 0 || $book_id === 0) {
        $error = 'Please select both a member and a book.';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT available_quantity FROM books WHERE book_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $book_id);
        mysqli_stmt_execute($stmt);
        $book = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        mysqli_stmt_close($stmt);

        if (!$book || $book['available_quantity'] <= 0) {
            $error = 'This book is not available for issue.';
        } else {
            $issue_date = date('Y-m-d');
            $status = 'Approved';
            $stmt = mysqli_prepare($conn, "INSERT INTO book_issues (member_id, book_id, issue_date, status) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "iiss", $member_id, $book_id, $issue_date, $status);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare($conn, "UPDATE books SET available_quantity = available_quantity - 1 WHERE book_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $book_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $success = 'Book issued successfully.';
        }
    }
}

// Fetch pending requests
$pending = mysqli_query($conn, "SELECT bi.issue_id, bi.issue_date, b.title, b.author, md.name AS member_name
                                 FROM book_issues bi
                                 JOIN books b ON bi.book_id = b.book_id
                                 JOIN member_details md ON bi.member_id = md.member_id
                                 WHERE bi.status = 'Pending'
                                 ORDER BY bi.issue_id ASC");

// Fetch members and available books for direct issue
$members = mysqli_query($conn, "SELECT member_id, name FROM member_details ORDER BY name");
$books_list = mysqli_query($conn, "SELECT book_id, title, author, available_quantity FROM books WHERE available_quantity > 0 ORDER BY title");
?>

<div class="dashboard-body">

    <?php if ($error): ?>
        <div class="msg-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="msg-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <!-- Pending Requests Section -->
    <h3>Pending Member Requests</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Book</th>
                <th>Author</th>
                <th>Member</th>
                <th>Request Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($pending) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($pending)): ?>
                <tr>
                    <td><?php echo $row['issue_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><?php echo htmlspecialchars($row['member_name']); ?></td>
                    <td><?php echo $row['issue_date']; ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>librarian/issue_book.php?approve_id=<?php echo $row['issue_id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Approve this request?')">Approve</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center; padding:15px;">No pending requests.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Direct Issue Section -->
    <h3 style="margin-top:30px;">Issue Book Directly</h3>
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
