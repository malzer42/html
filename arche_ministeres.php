<?php
// session_start();
require 'db_arche.php';
require 'arche_admins_protect.php';

/* if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'main_pastor') {
    header("Location: login.php");
    exit;
}
 */
// Delete ministry
if (isset($_GET['delete'])) {
    $id_ministry = $_GET['delete'];
    $pdo->prepare("DELETE FROM minitries WHERE id = ?")->execute([$id_member]);
    header("Location: arche_ministeres.php");
    exit;
}

// Fetch all ministries
$stmt = $pdo->query("SELECT * FROM minitries ORDER BY name");
$ministry = $stmt->fetchAll();


// Fetch all members
$stmt = $pdo->query("SELECT * FROM members ORDER BY first_name");
$members = $stmt->fetchAll();

// Delete Pastors
if (isset($_GET['delete'])) {
    $id_pastor = $_GET['delete'];
    $pdo->prepare("DELETE FROM pastors WHERE id = ?")->execute([$id_pastor]);
    header("Location: arche_gestion_membre.php");
    exit;
}

// Fetch all pastors
$stmtp = $pdo->query("SELECT * FROM pastors ORDER BY first_name");
$pastors = $stmtp->fetchAll();

// Get all ministries with their leader information
$sql = "SELECT name, description,  first_name, phone_number, email
        FROM minitries 
        JOIN members WHERE leader_id = members.id";


$stmt = $pdo->query($sql);
$ministries = $stmt->fetchAll();


?>
<!DOCTYPE html>
<html lang="fr">
<head> <!-- Begin of Head -->
  <meta charset="UTF-8" />
  <title>Ministeres Arche de Dieu</title>

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



<h4>Ministeres de l'Arche de Dieu</h4><br>

<a href="arche_create_ministry.php">➕ Ajout d'un Ministere</a><br><br>

<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th><small>Ministere</small></th>
        <th><small>Description</small></th>
        <th><small>Responsable</small></th>
        <th><small>Tel</small></th>
        <th><small>Courriel</small></th>
    </tr>
    <?php foreach ($ministries as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m['name']) ?></td>
            <td><?= htmlspecialchars($m['description']) ?></td>
            <td><?= $m['first_name'] ?></td>
            <td><?= $m['phone_number'] ?></td>
            <td><?= $m['email'] ?></td>
            
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