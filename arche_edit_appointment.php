<?php
require 'db_arche.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Rendez-vous non valide.");
}

$appointment_id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->execute([$appointment_id]);
$appt = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$appt) {
    die("Rendez-vous introuvable.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $appointment_date = $_POST['appointment_date'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];

    $stmt = $pdo->prepare("UPDATE appointments SET title = ?, appointment_date = ?, status = ?, notes = ? WHERE id = ?");
    $stmt->execute([$title, $appointment_date, $status, $notes, $appointment_id]);

    header("Location: arche_pastor_profile.php?id=" . $appt['pastor_id'] . "&updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head> <!-- Begin of Head -->
  <meta charset="UTF-8" />
  <title>Mofification RDV</title>

  <!-------------------------Link to external CSS----------->
  <link rel="stylesheet" href="css/style.css">
  <script type="text/javascript" src="https://kit.fontawesome.com/b99e675b6e.js"></script>
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

  <!-- Link to external JS -->
  <script type="text/javascript" src="js/arche_header.js" defer></script>
  <script type="text/javascript" src="js/arche_footer.js" defer></script>
  <script type="text/javascript" src="js/chat.js" defer></script>
    
</head>
<body>
    <div id="arche_footer"></div>
    <main> <!-- Beginning of Main -->
        <br>
        <h4>Modifier le Rendez-vous</h4>
        <br>
    <div class="form-group"> <!-- Application form -->
        <form method="POST">
            <table>
                <tr>
                    <td>
                        <label for="title">Titre</label>
                    </td>
                    <td>
                        <input type="text" name="title" value="<?= htmlspecialchars($appt['title']) ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="date et heure">Date et Heure:</label>
                    </td>
                    <td>
                        <input type="datetime-local" name="appointment_date" value="<?= date('Y-m-d\TH:i', strtotime($appt['appointment_date'])) ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="statut">Statut:</label>
                    </td>
                    <td>
                        <select name="status">
                            <option value="pending" <?= $appt['status'] == 'pending' ? 'selected' : '' ?>>En attente</option>
                            <option value="confirmed" <?= $appt['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmé</option>
                            <option value="cancelled" <?= $appt['status'] == 'cancelled' ? 'selected' : '' ?>>Annulé</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="notes">Notes:</label>
                    </td>
                    <td>
                        <textarea name="notes"><?= htmlspecialchars($appt['notes']) ?></textarea>
                    </td>
                </tr>
                <td>
                    <td><br>
                    <div class="cf-turnstile" data-sitekey="0x4AAAAAABT_sdEEEltXHkt4"></div>
                    <div>
                        <button type="submit" value="submit" style="width: 200px; height: 50px; font-size: 16px;">Mettre à jour</button>
                        <a href="arche_pastor_profile.php?id=<?= $pastor_id ?>">Annuler</a>
                    </div>
                  </td>
                </td>
            </table>
        </form>
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
