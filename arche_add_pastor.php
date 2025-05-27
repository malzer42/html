<?php
session_start();
require 'db_arche.php';

/* if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'main_pastor') {
    header("Location: login.php");
    exit;
} */


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['pastor_email'];
    $address = $_POST['address'];
    $role = $_POST['role'];
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO pastors (first_name, last_name, phone_number, email, address, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $phone_number,  $email, $address,  $role]);


    header("Location: arche_gestion_membre.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head> <!-- Begin of Head -->
  <meta charset="UTF-8" />
  <title>Pasteur</title>

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

<h4>Ajout d'un nouveau Pasteur</h4><br><br>

<div class="form-group"> <!-- Application form -->
          
          <form method="POST" action="create_pastor.php">
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
                  <input type="email" name="pastor_email" placeholder="elarchededieu@gmail.com" required>
                </td>
              </tr>

              <tr>
                <td>
                  <label for="address">Adresse:</label>
                </td>
                <td>
                  <textarea name="address" id="address" rows="4" placeholder="8621 Boulevard Saint-Laurent

Montréal, QC , CA H2P 2M9" style="width: 295px; height: 50px; font-size: 10px;" required></textarea>
                </td>
              </tr>


              <tr>
                <td>
                  <label for="role">Role: </label>
                </td>
                <td>
                  <select name="role" id="role" style="width: 300px; height: 50px; font-size: 16px;">
                    <option value="">       --Choisir un role--</option>
                    <option value="main">Principal</option>
                    <option value="cell">Secondaire</option>
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

        <div class="right">

        </div>
      </div>

      <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
          alert("Ajout du pasteur reussie!");
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

