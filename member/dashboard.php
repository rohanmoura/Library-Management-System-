<?php
$page_title = 'Dashboard';
include __DIR__ . '/member_header.php';

$member_id = $_SESSION['user_id'];

$available_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(available_quantity) as total FROM books"))['total'] ?? 0;

$stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM book_issues WHERE member_id = ? AND status = 'Approved'");
mysqli_stmt_bind_param($stmt, "i", $member_id);
mysqli_stmt_execute($stmt);
$issued = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM book_issues WHERE member_id = ? AND status = 'Pending'");
mysqli_stmt_bind_param($stmt, "i", $member_id);
mysqli_stmt_execute($stmt);
$pending = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
mysqli_stmt_close($stmt);
?>

<div class="dashboard-body">
    <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>

    <div class="stat-cards">
        <div class="stat-card">
            <h4><?php echo $available_books; ?></h4>
            <p>Available Books</p>
        </div>
        <div class="stat-card">
            <h4><?php echo $issued; ?></h4>
            <p>My Issued Books</p>
        </div>
        <div class="stat-card">
            <h4><?php echo $pending; ?></h4>
            <p>Pending Requests</p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
