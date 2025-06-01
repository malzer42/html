<?php
session_start();
require 'db_arche.php';
$cell_pastor = $pdo->query("SELECT * FROM pastors  ORDER BY first_name")->fetchAll();

/* if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'main_pastor') {
    header("Location: login.php");
    exit;
} */

if (!isset($_GET['id'])) {
    die('ID de membre fournie');
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM members WHERE id = ?");
$stmt->execute([$id]);
$member = $stmt->fetch();

if (!$member) {

    die('Membre non existant');
}

?>

<!DOCTYPE html>
<html lang="en">

<head> <!-- Begin of Head -->
  <meta charset="UTF-8" />
  <title>Edit of members</title>

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


    <div class="container">
      <div class="left">

<br>
<h4>Modification des informations du membre</h4>
<br>

<div class="form-group"> <!-- Application form -->
          
          <form method="POST" action="arche_update_member.php">

            <table>
              <tr>
                <td>
                  <input type="hidden" name="id" value="<?= htmlspecialchars($member['id']) ?>">
                </td>
              </tr>

              <tr>
                <td>
                  <label for="first_name">Prenom: </label>
                </td>
                <td>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($member['first_name']) ?>" required>
                </td>
              </tr>

              <tr>
                <td>
                  <label for="last_name">Nom: </label>
                </td>
                <td>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($member['last_name']) ?>" required>
                </td>
              </tr>

              <tr>
                <td>
                  <label for="phone">Phone: </label>
                </td>
                <td>
                    <input type="text" name="phone_number" value="<?= $member['phone_number'] ?>">
                </td>
              </tr>
              <tr>
                <td>
                  <label for="email">Courriel: </label>
                </td>
                <td>
                    <input type="email" name="email" value="<?= $member['email'] ?>" required>
                </td>
              </tr>

              <tr>
                <td>
                  <label for="address">Adresse:</label>
                </td>
                <td>
                    <textarea name="address" style="width: 295px; height: 50px; font-size: 16px;"><?= htmlspecialchars($member['address']) ?></textarea>
                </td>
              </tr>

              <tr>
                <td>
                  <label for="pastor_first_name">Pasteur: </label>
                </td>
                <td>
                  <select name="pastor_first_name" id="pastor_first_name" style="width: 300px; height: 50px; font-size: 16px;"  required>
                    <option value="">-- Choisir le pasteur de la cellule --</option>
                      <?php foreach ($cell_pastor as $cp): ?>
                            <option value="<?= $cp['id'] ?>">
                            <?= htmlspecialchars( $cp['first_name'] )?> - <?= htmlspecialchars($cp['last_name']) ?>
                            </option>
                        <?php endforeach; ?>

                  </select>
                </td>
              </tr>

              <tr>
                <td>
                  <label for="category">Categorie: </label>
                </td>
                <td>
                  <select name="category" id="category" style="width: 300px; height: 50px; font-size: 16px;">
                    <option value="">       --Choisir une Categorie--</option>
                    <option value="Homme" <?= $member['category'] == 'Homme' ? 'selected' : '' ?>>Homme</option>
                    <option value="Dame" <?= $member['category'] == 'Dame' ? 'selected' : '' ?>>Dame</option>
                    <option value="Jeunesse" <?= $member['category'] == 'Jeunesse' ? 'selected' : '' ?>>Jeunesse</option>
                    <option value="Ecodim" <?= $member['category'] == 'Ecodim' ? 'selected' : '' ?>>Ecodim</option>
                    <option value="Visiteur" <?= $member['category'] == 'Visiteur' ? 'selected' : '' ?>>Visiteur</option>
                    

                  </select>
                </td>
              </tr>


              <tr>
                <td>
                  <label for="ministry">Ministere: </label>
                </td>
                <td>
                  <select name="ministry" id="ministry" style="width: 300px; height: 50px; font-size: 16px;">
                    <option value="">       --Choisir un Ministere--</option>
                    <option value="Hommes" <?= $member['ministry'] == 'Hommes' ? 'selected' : '' ?>>Hommes</option>
                    <option value="Femmes" <?= $member['ministry'] == 'Femmes' ? 'selected' : '' ?>>Femmes</option>
                    <option value="Jeunes" <?= $member['ministry'] == 'Jeunes' ? 'selected' : '' ?>>Jeunes</option>
                    <option value="Enfants" <?= $member['ministry'] == 'Enfants' ? 'selected' : '' ?>>Enfants</option>
                    <option value="Autres" <?= $member['ministry'] == 'Autres' ? 'selected' : '' ?>>Autres</option>
                  </select>
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
          alert("Modification du Membre reussie!");
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
