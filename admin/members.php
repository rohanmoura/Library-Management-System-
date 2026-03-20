<?php
$page_title = 'View Members';
$body_class = 'bg-view-members';
include __DIR__ . '/admin_header.php';

$members = mysqli_query($conn, "SELECT * FROM member_details ORDER BY member_id ASC");
?>

<div class="dashboard-body">
    <h3>All Members</h3>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Membership</th>
                <th>Join Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($m = mysqli_fetch_assoc($members)): ?>
            <tr>
                <td><?php echo $m['member_id']; ?></td>
                <td><?php echo htmlspecialchars($m['name']); ?></td>
                <td><?php echo $m['age']; ?></td>
                <td><?php echo htmlspecialchars($m['gender']); ?></td>
                <td><?php echo htmlspecialchars($m['phone']); ?></td>
                <td><?php echo htmlspecialchars($m['email']); ?></td>
                <td><?php echo htmlspecialchars($m['membership_type']); ?></td>
                <td><?php echo $m['join_date']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
