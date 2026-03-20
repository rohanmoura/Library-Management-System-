<?php
$page_title = 'Manage Librarians';
$body_class = 'bg-manage-librarians';
include __DIR__ . '/admin_header.php';

// Handle status update
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if (in_array($action, ['Approved', 'Rejected'])) {
        $stmt = mysqli_prepare($conn, "UPDATE librarian_details SET status = ? WHERE librarian_id = ?");
        mysqli_stmt_bind_param($stmt, "si", $action, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: " . BASE_URL . "admin/librarians.php?msg=updated");
        exit;
    }
}

$librarians = mysqli_query($conn, "SELECT * FROM librarian_details ORDER BY librarian_id ASC");
?>

<div class="dashboard-body">
    <div class="top-bar">
        <h3>Manage Librarians</h3>
        <a href="<?= BASE_URL ?>admin/add_librarian.php" class="btn">Add Librarian</a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="msg-success">
            <?php
            if ($_GET['msg'] === 'added') echo 'Librarian added successfully.';
            elseif ($_GET['msg'] === 'updated') echo 'Librarian status updated.';
            ?>
        </div>
    <?php endif; ?>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Specialization</th>
                <th>Experience</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($lib = mysqli_fetch_assoc($librarians)): ?>
            <tr>
                <td><?php echo $lib['librarian_id']; ?></td>
                <td><?php echo htmlspecialchars($lib['name']); ?></td>
                <td><?php echo htmlspecialchars($lib['specialization']); ?></td>
                <td><?php echo $lib['experience']; ?> yrs</td>
                <td><?php echo htmlspecialchars($lib['phone']); ?></td>
                <td><?php echo htmlspecialchars($lib['email']); ?></td>
                <td>
                    <?php
                    $status = $lib['status'];
                    $badge = 'badge-pending';
                    if ($status === 'Approved') $badge = 'badge-approved';
                    elseif ($status === 'Rejected') $badge = 'badge-rejected';
                    ?>
                    <span class="badge <?php echo $badge; ?>"><?php echo $status; ?></span>
                </td>
                <td class="actions">
                    <?php if ($status !== 'Approved'): ?>
                        <a href="<?= BASE_URL ?>admin/librarians.php?action=Approved&id=<?php echo $lib['librarian_id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Approve this librarian?')">Approve</a>
                    <?php endif; ?>
                    <?php if ($status !== 'Rejected'): ?>
                        <a href="<?= BASE_URL ?>admin/librarians.php?action=Rejected&id=<?php echo $lib['librarian_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject this librarian?')">Reject</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
