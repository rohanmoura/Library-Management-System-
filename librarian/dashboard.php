<?php
$page_title = 'Dashboard';
include __DIR__ . '/librarian_header.php';

$total_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM books"))['total'] ?? 0;
$available_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(available_quantity) as total FROM books"))['total'] ?? 0;
$issued_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM book_issues WHERE status = 'Approved'"))['total'];
$pending_requests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM book_issues WHERE status = 'Pending'"))['total'];
?>

<div class="dashboard-body">
    <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>

    <div class="stat-cards">
        <div class="stat-card">
            <h4><?php echo $total_books; ?></h4>
            <p>Total Books</p>
        </div>
        <div class="stat-card">
            <h4><?php echo $available_books; ?></h4>
            <p>Available Books</p>
        </div>
        <div class="stat-card">
            <h4><?php echo $issued_books; ?></h4>
            <p>Issued Books</p>
        </div>
        <div class="stat-card">
            <h4><?php echo $pending_requests; ?></h4>
            <p>Pending Requests</p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
