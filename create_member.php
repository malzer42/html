<?php
require 'db_arche.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone_number = trim($_POST['phone_number']);
    $email = trim($_POST['member_email']);
    $address = trim($_POST['address']);
    $pastor_first_name = trim($_POST['pastor_first_name']);
    $category = trim($_POST['category']);
    $ministry = trim($_POST['ministry']);


    $stmtp = $pdo->prepare("SELECT id FROM pastors WHERE first_name = :first_name");
    $stmtp->execute(['first_name' => $pastor_first_name]);
    $pastor = $stmtp->fetch(PDO::FETCH_ASSOC);
    var_dump($pastor);
    $pastor_id = $pastor['id'];

    $pastor_id = trim($_POST['pastor_first_name']);
    $pastor_first_name = trim($_POST['pastor_first_name']);

    /* $stmtp = $pdo->prepare("SELECT id FROM pastors WHERE first_name = :first_name");
    $stmtp->execute(['first_name' => $pastor_first_name]);
    $pastor = $stmtp->fetch(PDO::FETCH_ASSOC);
    $pastor_id = $pastor['id']; */



    // $pastor_id = isset($_POST['pastor_id']) && is_numeric($_POST['pastor_id']) ? (int)$_POST['pastor_id'] : null;
    var_dump($_POST['pastor_id']);



    if (!$pastor_id) {
        die("Veuillez choisir un pasteur valide");
    }

    $stmt = $pdo->prepare("INSERT INTO members (first_name, last_name, phone_number, email, address, category, ministry, pastor_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $phone_number, $email, $address, $category, $ministry, $pastor_id]);

    header("Location: arche_add_member.php?success=1");
    exit;
}
?>

