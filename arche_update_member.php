<?php

require 'db_arche.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $pastor_first_name = trim($_POST['pastor_first_name']);
    $category = $_POST['category'];
    $ministry = $_POST['ministry'];

    $pdo->prepare("UPDATE members SET first_name = ?, last_name = ?, phone_number = ?,  email = ?, address = ?, category = ?, ministry = ? WHERE id = ?")
        ->execute([$first_name, $last_name, $phone_number,  $email, $address, $category, $ministry, $id]);

    header("Location: arche_edit_member.php?success=1");
    exit;
}
?>
 