<?php
require 'db_arche.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $amount = $_POST['amount'];
    $donation_date = $_POST['donation_date'];
    $note = $_POST['donation_note'];

    

    $stmtp = $pdo->prepare("SELECT id FROM members WHERE first_name = :first_name AND last_name = :last_name");
    $stmtp->execute([
    ':first_name' => $first_name,
    ':last_name' => $last_name
    ]);
    $member = $stmtp->fetch(PDO::FETCH_ASSOC);
    $member_id = $member['id'];



    // $pastor_id = isset($_POST['pastor_id']) && is_numeric($_POST['pastor_id']) ? (int)$_POST['pastor_id'] : null;
    var_dump($_POST['member_id']);



    if (!$member_id) {
        die("Veuillez choisir un member valide");
    }

    $stmt = $pdo->prepare("INSERT INTO donations (member_id, amount, donation_date, note) VALUES (?, ?, ?, ?)");
    $stmt->execute([$member_id, $amount, $donation_date, $note]);

    header("Location: donations.html?success=1");
    exit;
}
?>