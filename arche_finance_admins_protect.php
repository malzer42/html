<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: arche_finance_admins_login.php");
    exit;
}
