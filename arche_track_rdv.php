<?php
session_start();
require 'db_arche.php';

/* if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'main_pastor') {
    header("Location: arche_login.php");
    exit;
} */

// Handle confirmation and cancellation
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($_GET['action'] === 'confirm') {
        $pdo->prepare("UPDATE appointments SET status = 'confirmed' WHERE id = ?")->execute([$id]);
    } elseif ($_GET['action'] === 'cancel') {
        $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ?")->execute([$id]);
    } elseif ($_GET['action'] === 'delete') {
        $pdo->prepare("DELETE FROM appointments WHERE id = ?")->execute([$id]);
    }
    header("Location: arche_track_rdv.php");
    exit;
}

// Fetch appointments with member name
$stmt = $pdo->query("
    SELECT a.*, m.first_name 
    FROM appointments a
    JOIN members m ON a.member_id = m.id
    ORDER BY a.date
");
$appointments = $stmt->fetchAll();
?>




<!DOCTYPE html>
<html lang="fr">
<head> <!-- Begin of Head -->
  <meta charset="UTF-8" />
  <title>Gestion Rendez-vous </title>

  <!-------------------------Link to external CSS----------->
  <link rel="stylesheet" href="css/style.css">
  <script type="text/javascript" src="https://kit.fontawesome.com/b99e675b6e.js"></script>
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

  <!-- Link to external JS -->
  <script type="text/javascript" src="js/arche_header.js" defer></script>
  <script type="text/javascript" src="js/arche_footer.js" defer></script>
  <script type="text/javascript" src="js/chat.js" defer></script>
    
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
    }
    h2 {
        margin-top: 40px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #aaa;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #eee;
    }
</style>


<body>

<div id="arche_footer"></div>
  <!-- <div class="overlay"></div> -->

  <main> <!-- Beginning of Main -->
<br><br>
<h4>Gestion des rendez-vous</h4><br>

<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th><small>Membre</small></th>
        <th><small>Date</small></th>
        <th><small>Heure</small></th>
        <th><small>Status</small></th>
        <th><small>Actions</small></th>
    </tr>
    <?php foreach ($appointments as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a['first_name']) ?></td>
            <td><?= $a['date'] ?></td>
            <td><?= $a['time_slot'] ?></td>
            <td><?= ucfirst($a['status']) ?></td>
            <td>
                <?php if ($a['status'] === 'pending'): ?>
                    <a href="?action=confirm&id=<?= $a['id'] ?>">✅ Confirm</a> |
                    <a href="?action=cancel&id=<?= $a['id'] ?>">❌ Cancel</a> |
                <?php endif; ?>
                <a href="?action=delete&id=<?= $a['id'] ?>" onclick="return confirm('Delete this appointment?')">🗑️ Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table><br>







</main> <!-- End of main -->

  <div id="arche_header"></div>

  <!--Start of Tawk.to Script-->
  <script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function () {
      var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
      s1.async = true;
      s1.src = 'https://embed.tawk.to/680b4a5f29a5a61914179fbb/1ipm1l1d8';
      s1.charset = 'UTF-8';
      s1.setAttribute('crossorigin', '*');
      s0.parentNode.insertBefore(s1, s0);
    })();
  </script>

  <!--End of Tawk.to Script-->

</body>
</html>