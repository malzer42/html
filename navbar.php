<?php
session_start();
?>
<hr>
<footer>
<navbar style="background:#333; padding:10px;">
    <a href="arche.php" style="color:#fff; margin-right:15px;">Home</a>
    <?php if (isset($_SESSION['user_role'])): ?>
        <?php if ($_SESSION['user_role'] === 'main_pastor'): ?>
            <a href="main_dashboard.php" style="color:#fff; margin-right:15px;">Dashboard</a>
            <a href="register_cell_pastor.php" style="color:#fff; margin-right:15px;">Add Cell Pastor</a>
            <a href="manage_pastors.php" style="color:#fff; margin-right:15px;">Manage Pastors</a>
            <a href="manage_appointments.php" style="color:#fff; margin-right:15px;">Appointments</a>
            <a href="generate_tax_receipts.php" style="color:#fff; margin-right:15px;">Tax Receipts</a>
        <?php elseif ($_SESSION['user_role'] === 'cell_pastor'): ?>
            <a href="cell_dashboard.php" style="color:#fff; margin-right:15px;">Dashboard</a>
            <a href="view_cell_members.php" style="color:#fff; margin-right:15px;">My Members</a>
        <?php endif; ?>
        <a href="arche_logout.php" style="color:#fff;">Logout</a>
    <?php else: ?>
        <a href="arche_login.php" style="color:#fff;">Login</a>
    <?php endif; ?>
</navbar>
</footer>
