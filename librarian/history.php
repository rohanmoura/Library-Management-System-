<?php
$page_title = 'Issue History';
$body_class = 'bg-reports';
include __DIR__ . '/librarian_header.php';

$records = mysqli_query($conn, "SELECT bi.issue_id, bi.issue_date, bi.status, bi.notes,
                                       b.title, b.author,
                                       md.name AS member_name
                                FROM book_issues bi
                                JOIN books b ON bi.book_id = b.book_id
                                JOIN member_details md ON bi.member_id = md.member_id
                                ORDER BY bi.issue_id ASC");
?>

<div class="dashboard-body">
    <h3>Issue History</h3>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Book</th>
                <th>Author</th>
                <th>Member</th>
                <th>Issue Date</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($records) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($records)): ?>
                <tr>
                    <td><?php echo $row['issue_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><?php echo htmlspecialchars($row['member_name']); ?></td>
                    <td><?php echo $row['issue_date']; ?></td>
                    <td>
                        <?php
                        $s = $row['status'];
                        $badge = 'badge-pending';
                        if ($s === 'Approved') $badge = 'badge-approved';
                        elseif ($s === 'Done') $badge = 'badge-approved';
                        elseif ($s === 'Cancelled') $badge = 'badge-rejected';
                        ?>
                        <span class="badge <?php echo $badge; ?>"><?php echo $s; ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($row['notes'] ?? '-'); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center; padding:20px;">No records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
