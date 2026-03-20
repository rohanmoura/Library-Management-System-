<?php
$page_title = 'Dashboard';
$body_class = 'bg-admin-dashboard';
include __DIR__ . '/admin_header.php';

// Get stats
$total_books = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(quantity) as total FROM books"))['total'] ?? 0;
$total_members = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM member_details"))['total'];
$total_librarians = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM librarian_details"))['total'];
$total_issued = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM book_issues WHERE status IN ('Pending','Approved')"))['total'];
?>

<div class="dashboard-body">
    <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>

    <div class="stat-cards">
        <div class="stat-card">
            <h4><?php echo $total_books; ?></h4>
            <p>Total Books</p>
        </div>
        <div class="stat-card">
            <h4><?php echo $total_members; ?></h4>
            <p>Total Members</p>
        </div>
        <div class="stat-card">
            <h4><?php echo $total_librarians; ?></h4>
            <p>Total Librarians</p>
        </div>
        <div class="stat-card">
            <h4><?php echo $total_issued; ?></h4>
            <p>Issued Books</p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
