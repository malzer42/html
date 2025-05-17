<?php
session_start();
require 'db_arche.php';

/* if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'main_pastor') {
    header("Location: login.php");
    exit;
} */

$pid = $_GET['id'] ?? null;
if (!$pid) exit('Invalid ID.');

$stmt = $pdo->prepare("SELECT * FROM pastors WHERE id = ?");
$stmt->execute([$pid]);
$pastor = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pfirst_name = $_POST['first_name'];
    $plast_name = $_POST['last_name'];
    $pphone_number = $_POST['phone_number'];
    $pemail = $_POST['email'];
    $paddress = $_POST['address'];

    $pdo->prepare("UPDATE pastors SET first_name = ?, last_name = ?, phone_number = ?,  email = ?, address = ? WHERE id = ?")
        ->execute([$pfirst_name, $plast_name, $pphone_number,  $email, $paddress, $pid]);

    header("Location: arche_gestion_membre.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head> <!-- Begin of Head -->
  <meta charset="UTF-8" />
  <title>Edit of pastors</title>

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



</head> <!-- End of Head -->

<body> <!-- Begin of Body -->

  <div id="arche_footer"></div>
  <!-- <div class="overlay"></div> -->

  <main> <!-- Beginning of Main -->

    <h1>Gestion de l'eglise Arche de Dieu</h1>

    <div class="container">
      <div class="left">


<h4>Modification des informations du Pasteur</h4>

<div class="form-group"> <!-- Application form -->
          
          <form method="POST" action="create_pastor.php">
            <table>

              <tr>
                <td>
                  <label for="first_name">Prenom: </label>
                </td>
                <td>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($pastor['first_name']) ?>" required>
                </td>
              </tr>

              <tr>
                <td>
                  <label for="last_name">Nom: </label>
                </td>
                <td>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($pastor['last_name']) ?>" required>
                </td>
              </tr>

              <tr>
                <td>
                  <label for="phone">Phone: </label>
                </td>
                <td>
                    <input type="text" name="phone_number" value="<?= $pastor['phone_number'] ?>">
                </td>
              </tr>
              <tr>
                <td>
                  <label for="email">Courriel: </label>
                </td>
                <td>
                    <input type="email" name="email" value="<?= $pastor['email'] ?>" required>
                </td>
              </tr>

              <tr>
                <td>
                  <label for="address">Adresse:</label>
                </td>
                <td>
                    <textarea name="address" style="width: 295px; height: 50px; font-size: 16px;"><?= htmlspecialchars($pastor['address']) ?></textarea>
                </td>
              </tr>

              <td>
              <td>
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


        </div>



<script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
          alert("Ajout du Pasteur reussie!");
        }
      </script>


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