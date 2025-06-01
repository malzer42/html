<?php
session_start();
require 'db_arche.php';

$cell_pastor = $pdo->query("SELECT * FROM pastors  ORDER BY first_name")->fetchAll();

/* if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'main_pastor') {
    header("Location: login.php");
    exit;
} */


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['member_email']);

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM members WHERE email = ?");
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        // Email already exists
        echo "Un membre avec cet email existe déjà.";
    } else{

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone_number = trim($_POST['phone_number']);
    $email = trim($_POST['member_email']);
    $address = trim($_POST['address']);
    $pastor_first_name = trim($_POST['pastor_first_name']);
    $category = trim($_POST['category']);
    $ministry = trim($_POST['ministry']);


    $stmtp = $pdo->prepare("SELECT id FROM pastors WHERE first_name = :first_name");
    $stmtp->execute(['first_name' => $pastor_first_name]);
    $pastor = $stmtp->fetch(PDO::FETCH_ASSOC);
    $pastor_id = $pastor['id'];

    if (!$pastor_id) {
        die("Veuillez choisir un pasteur valide");
    }

    $stmt = $pdo->prepare("INSERT INTO members (first_name, last_name, phone_number, email, address, pastor_id, category, ministry) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $phone_number, $email, $address, $pastor_id, $category, $ministry]);

    header("Location: arche_gestion_membre.php?success=1");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head> <!-- Begin of Head -->
  <meta charset="UTF-8" />
  <title>Ajout membre</title>

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

<br><br>
<h4>Ajout d'un Membre</h4><br><br>

<div class="form-group"> <!-- Application form -->
          
          <form method="POST" action="create_member.php">
            <table>

              <tr>
                <td>
                  <label for="first_name">Prenom: </label>
                </td>
                <td>
                  <input type="text" name="first_name" placeholder="Prenom" required>
                </td>
              </tr>

              <tr>
                <td>
                  <label for="last_name">Nom: </label>
                </td>
                <td>
                  <input type="text" name="last_name" placeholder="Nom" required>
                </td>
              </tr>

              <tr>
                <td>
                  <label for="phone">Phone: </label>
                </td>
                <td>
                  <input type="tel" name="phone_number" pattern="^\+?[0-9\s\-\(\)]{7,15}$"
                    placeholder="+1 (514) 271-7788" required>
                </td>
              </tr>
<tr>
                <td>
                  <label for="email">Courriel: </label>
                </td>
                <td>
                  <input type="email" name="member_email" placeholder="elarchededieu@gmail.com" required>
                </td>
              </tr>

              <tr>
                <td>
                  <label for="address">Adresse:</label>
                </td>
                <td>
                  <textarea name="address" id="address" rows="4" placeholder="8621 Boulevard Saint-Laurent

Montréal, QC , CA H2P 2M9"
                    style="width: 295px; height: 50px; font-size: 14px;" required></textarea>
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
                            <?= $cp['first_name'] ?> - <?= $cp['last_name'] ?>
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
                    <option value="Homme">Homme</option>
                    <option value="Dame">Dame</option>
                    <option value="Jeunesse">Jeunesse</option>
                    <option value="Ecodim">Ecodim</option>
                    <option value="Visiteur">Visiteur</option>

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
                    <option value="Hommes">Hommes</option>
                    <option value="Femmes">Femmes</option>
                    <option value="Jeunes">Jeunes</option>
                    <option value="Enfants">Enfants</option>
                    <option value="Autres">Autres</option>
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
          alert("Ajout du Membre reussie!");
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
