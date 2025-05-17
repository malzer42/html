<?php
require 'db_arche.php'; // assumes db.php has DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['pastor_email'];
    $address = $_POST['address'];
    $role = $_POST['role'];
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO pastors (first_name, last_name, phone_number, email, address, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $phone_number,  $email, $address,  $role]);
    
}

header("Location: create_pastor.html?success=1");
 exit;

?>
