<?php
require 'db_arche.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID invalide.");
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ?");
$stmt->execute([$id]);

// Optionally: fetch member_id to redirect
$pastor = $pdo->query("SELECT pastor_id FROM appointments WHERE id = $id")->fetch();
header("Location: arche_pastor_profile.php?id=" . $pastor['pastor_id'] . "&cancelled=1");
exit;
