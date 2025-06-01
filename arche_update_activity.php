<?php

require 'db_arche.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $activity_date = $_POST['activity_date'];
    $time = $_POST['time'];
    $location = $_POST['location'];

    $pdo->prepare("UPDATE calendar_activities SET title = ?, description = ?, activity_date = ?,  time = ?, location = ? WHERE id = ?")
        ->execute([$title, $description, $activity_date,  $time, $location, $id]);

    header("Location: arche_edit_activity.php?success=1");
    exit;
}
?>