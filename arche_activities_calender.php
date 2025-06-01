<?php
// session_start();
require 'db_arche.php';
require 'arche_admins_protect.php';

/* if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'main_pastor') {
    header("Location: login.php");
    exit;
}
 */
// Delete an activity
if (isset($_GET['delete'])) {
    $id_activity = $_GET['delete'];
    $pdo->prepare("DELETE FROM calendar_activities WHERE id = ?")->execute([$id_activity]);
    header("Location: arche_activities_calender.php");
    exit;
}


// Fetch all members
$stmt = $pdo->query("SELECT * FROM members ORDER BY first_name");
$members = $stmt->fetchAll();

// Get all activities with the members associated
$sql = "SELECT calendar_activities.id, title, description, activity_date, time, location, first_name, last_name
        FROM calendar_activities
        JOIN members where member_id = members.id
        ORDER BY activity_date ASC, time ASC
        ";

$stm = $pdo->query($sql);
$activities = $stm->fetchAll();



?>
<!DOCTYPE html>
<html lang="fr">
<head> <!-- Begin of Head -->
  <meta charset="UTF-8" />
  <title>Calendrier Activites Arche de Dieu</title>

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
<br>



<h4>Calendrier des activites de l'Arche de Dieu</h4><br>

<a href="arche_create_activity.php">➕ Ajout d'une Activité</a><br><br>

<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th><small>Titre</small></th>
        <th><small>Description</small></th>
        <th><small>Date</small></th>
        <th><small>Heure</small></th>
        <th><small>Lieu</small></th>
        <th><small>Prenom</small></th>
        <th><small>Nom</small></th>
        <th><small>Actions</small></th>
    </tr>

    <?php foreach ($activities as $ac): ?>
        <tr>
            <td><small><?= htmlspecialchars($ac['title']) ?></small></td>
            <td><small><?= htmlspecialchars($ac['description']) ?></small></td>
            <td><small><?= $ac['activity_date'] ?></small></td>
            <td><small><?= $ac['time'] ?></small></td>
            <td><small><?= $ac['location'] ?></small></td>
            <td><small><?= $ac['first_name'] ?></small></td>
            <td><small><?= $ac['last_name'] ?></small></td>
            <td><small>
                <a href="arche_edit_activity.php?id=<?= $ac['id'] ?>" target="_blank">✏️ Modifier</a> |
                <a href="?delete=<?= $ac['id'] ?>" onclick="return confirm('Effacer?')">🗑️ Effacer</a></small>
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





