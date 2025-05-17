<?php
require 'db_arche.php';

$slots = $pdo->query("SELECT * FROM pastor_availability WHERE is_booked = 0 AND date >= CURDATE() ORDER BY date, time_slot")->fetchAll();
// $arche_member = $pdo->query("SELECT * FROM members ORDER BY first_name")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $first_name = trim($_POST['first_name']);
  $last_name = trim($_POST['last_name']);
  $slotId = $_POST['slot_id'];

  $stmtp = $pdo->prepare("SELECT id FROM members WHERE first_name = :first_name AND last_name = :last_name");
    $stmtp->execute([
    ':first_name' => $first_name,
    ':last_name' => $last_name
    ]);

  $member = $stmtp->fetch(PDO::FETCH_ASSOC);
  $memberId = $member['id']; // Set after member login

  // Get slot details
  $stmt = $pdo->prepare("SELECT * FROM pastor_availability WHERE id = ? AND is_booked = 0");
  $stmt->execute([$slotId]);
  $slot = $stmt->fetch();

  if ($slot) {
    // Insert appointment
    $insert = $pdo->prepare("INSERT INTO appointments (member_id, date, time_slot) VALUES (?, ?, ?)");
    $insert->execute([$memberId, $slot['date'], $slot['time_slot']]);

    // Mark slot as booked
    $update = $pdo->prepare("UPDATE pastor_availability SET is_booked = 1 WHERE id = ?");
    $update->execute([$slotId]);

    echo "Appointment request submitted.";
    } else {
        echo "Slot unavailable.";
  }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head> <!-- Begin of Head -->
  <meta charset="UTF-8" />
  <title>Administration</title>

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
  <!-- <div class="overlay"></div> -->

  <main> <!-- Beginning of Main -->

<br><br>
<h4>Prenez un Rendez-vous avec le Pasteur Badi</h4><br><br>

<div class="form-group"> <!-- Application form -->

    <form method="POST">
        <table>
          <tr>
            <td>
              <label for="first_name">Prenom: </label>
            </td>
            <td>
              <!-- <select name="first_name" id="first_name" style="width: 300px; height: 50px; font-size: 16px;" required>
                <option value="">Choisir le prenom du membre</option>
                  <?php foreach ($arche_member as $am): ?>
                            <option value="<?= $am['id'] ?>">
                            <?= $am['first_name'] ?> 
                            </option>
                  <?php endforeach; ?>

              </select> -->
              <input type="text" name="first_name" placeholder="Prenom" required>
            </td>
          </tr>

          <tr>
            <td>
              <label for="last_name">Nom: </label>
            </td>
              <td>
                <!-- <select name="last_name" id="last_name" style="width: 300px; height: 50px; font-size: 16px;" required>
                <option value="">Choisir le nom du membre</option>
                  <?php foreach ($arche_member as $am): ?>
                            <option value="<?= $am['id'] ?>">
                            <?= $am['last_name'] ?> 
                            </option>
                  <?php endforeach; ?>

              </select> -->
                <input type="text" name="last_name" placeholder="Nom" required>
            </td>
          </tr>


          <tr>
            <td>
              <label for="slodt_id">Disponibilité:</label>
            </td>
            <td>
              <select name="slot_id" style="width: 300px; height: 50px; font-size: 16px;" required>
                      <option value="">Choisir une disponibilite du pasteur</option>
                        <?php foreach ($slots as $s): ?>
                            <option value="<?= $s['id'] ?>">
                            <?= $s['date'] ?> at <?= substr($s['time_slot'], 0, 5) ?>
                            </option>
                        <?php endforeach; ?>
              </select>

            </td>
          </tr>
             
          <td>
            <td><br>
                <div class="cf-turnstile" data-sitekey="0x4AAAAAABT_sdEEEltXHkt4"></div>

                <div>
                  <button type="submit" value="submit"
                    style="width: 200px; height: 50px; font-size: 16px;">Soumettre</button>
                  <input type="reset" value="Reset" style="width: 100px; height: 50px; font-size: 16px;">

                </div>

              </td>
              </td>
        </table>
    
    </form>


</dvi>

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

