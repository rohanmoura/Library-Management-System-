<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: " . BASE_URL . "auth/member_login.php");
    exit;
}

$member_id = $_SESSION['user_id'];
$book_id = (int) ($_GET['book_id'] ?? 0);

if ($book_id === 0) {
    header("Location: " . BASE_URL . "member/books.php");
    exit;
}

// Check for duplicate pending/approved request
$stmt = mysqli_prepare($conn, "SELECT issue_id FROM book_issues WHERE member_id = ? AND book_id = ? AND status IN ('Pending', 'Approved')");
mysqli_stmt_bind_param($stmt, "ii", $member_id, $book_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    header("Location: " . BASE_URL . "member/books.php?error=duplicate");
    exit;
}
mysqli_stmt_close($stmt);

// Check book availability
$stmt = mysqli_prepare($conn, "SELECT available_quantity FROM books WHERE book_id = ?");
mysqli_stmt_bind_param($stmt, "i", $book_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$book || $book['available_quantity'] <= 0) {
    header("Location: " . BASE_URL . "member/books.php?error=unavailable");
    exit;
}

// Insert request with status Pending
$issue_date = date('Y-m-d');
$status = 'Pending';
$stmt = mysqli_prepare($conn, "INSERT INTO book_issues (member_id, book_id, issue_date, status) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "iiss", $member_id, $book_id, $issue_date, $status);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: " . BASE_URL . "member/books.php?msg=requested");
exit;
?>
