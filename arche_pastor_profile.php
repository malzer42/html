<?php
require 'db_arche.php';

// Validate and fetch ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Pastor non valide.");
}

$pastor_id = intval($_GET['id']);

// Fetch pastor data
$stmt = $pdo->prepare("SELECT * FROM pastors WHERE id = ?");
$stmt->execute([$pastor_id]);
$pastor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pastor) {
    die("Pasteur non trouvé.");
}

// Fetch appointments for this member
$appt_stmt = $pdo->prepare("SELECT * FROM appointments WHERE pastor_id = ? ORDER BY appointment_date DESC");
$appt_stmt->execute([$pastor_id]);
$appointments = $appt_stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="fr">
<head> <!-- Begin of Head -->
  <meta charset="UTF-8" />
  <title>Profile du Pasteur</title>

  <!-------------------------Link to external CSS----------->
  <link rel="stylesheet" href="css/style.css">
  <script type="text/javascript" src="https://kit.fontawesome.com/b99e675b6e.js"></script>
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

  <!-- Link to external JS -->
  <script type="text/javascript" src="js/arche_header.js" defer></script>
  <script type="text/javascript" src="js/arche_footer.js" defer></script>
  <script type="text/javascript" src="js/chat.js" defer></script>

  <style>
    .container {
      flex-direction: row;
      display: flex;
      height: 100vh;
      /* Full height of the viewport */
    }

    .left {
      flex: 3;
      /* 40% */
      background-color: white;
    }

    .right {
      flex: 7;
      /* 70% */
      background-color: white;
    }

    /* Responsive stacking for screens narrower than 768px */
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .left,
      .right {
        height: 50vh;
        /* Half height each */
      }
    }
  </style>
    
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
<div class="container">
<div class="left">
<br>
<h4>Profil du Pasteur</h4>
<br>

<ul>
    <li><strong>Prénom :</strong> <?= htmlspecialchars($pastor['first_name']) ?></li>
    <li><strong>Nom :</strong> <?= htmlspecialchars($pastor['last_name']) ?></li>
    <li><strong>Email :</strong> <?= htmlspecialchars($pastor['email']) ?></li>
    <li><strong>Téléphone :</strong> <?= htmlspecialchars($pastor['phone_number']) ?></li>
    <li><strong>Adresse :</strong> <?= htmlspecialchars($pastor['address']) ?></li>
    <li><strong>Role :</strong> <?= htmlspecialchars($pastor['role']) ?></li>
    <!-- Add more fields as needed -->
</ul>
</div>

<!-- <a href="arche_pastors_team.php">← Retour à la liste des Pasteurs</a> -->
<div class="right">
  <br>
<h4>Rendez-vous</h4>
<br>

<?php if (count($appointments) > 0): ?>
    <table border="1" cellpadding="8">
        <tr>
            <th>Titre</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Notes</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($appointments as $appt): ?>
          <?php if ($appt['status'] === 'cancelled') continue; ?>
            <tr>
                <td><?= htmlspecialchars($appt['title']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($appt['appointment_date'])) ?></td>
                <td><?= htmlspecialchars($appt['status']) ?></td>
                <td><?= nl2br(htmlspecialchars($appt['notes'])) ?></td>
                <td>
                  <a href="arche_edit_appointment.php?id=<?= $appt['id'] ?>">✏️ Modifier</a> |
                  <a href="arche_cancel_appointment.php?id=<?= $appt['id'] ?>" onclick="return confirm('Annuler ce rendez-vous ?')">❌ Annuler</a>

                </td>
                
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Aucun rendez-vous trouvé pour le Pasteur.</p>
<?php endif; ?>
<br>
<a href="arche_pastor_appointments.php?pastor_id=<?= $pastor_id ?>">
    ➕ Ajouter un rendez-vous
</a>

</div>
</div>
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
