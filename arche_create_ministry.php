<?php
require 'db_arche.php';
$arche_member = $pdo->query("SELECT * FROM members  ORDER BY first_name")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $leader_id = intval($_POST['leader_id']); // Get member id directly
    

    var_dump($leader_id);




    if (!$leader_id) {
        die("Veuillez choisir un member valide");
    }

    $stmt = $pdo->prepare("INSERT INTO minitries (name, description, leader_id) VALUES (?, ?, ?)");
    $stmt->execute([$name, $description, $leader_id]);

    header("Location: arche_create_ministry.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head> <!-- Begin of Head -->
  <meta charset="UTF-8" />
  <title>Créer un ministere</title>

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
<h4>Créer un nouveau ministère</h4><br><br>

<div class="form-group"> <!-- Application form -->
          
          <form method="POST" action="arche_create_ministry.php">
            <table>
                <tr>
                    <td>
                        <label for="name">Nom</label>
                    </td>
                    <td>
                        <input type="text" name="name" placeholder="Nom du ministere" required >
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="description">Description</label>
                    </td>
                    <td>
                        <textarea name="description" id="description" rows="4" placeholder="Description du ministere"
                    style="width: 295px; height: 50px; font-size: 10px;" required></textarea>
                    </td>
                </tr>

                <tr>
                <td>
                  <label for="leader_id">Responsable: </label>
                </td>
                <td>
                  <select name="leader_id" id="leader_id" style="width: 300px; height: 50px; font-size: 16px;"  required>
                    <option value="">-- Choisir le Responsable du Ministere --</option>
                      <?php foreach ($arche_member as $am): ?>
                            <option value="<?= $am['id'] ?>">
                            <?= $am['first_name'] ?> - <?= $am['last_name'] ?>
                            </option>
                        <?php endforeach; ?>

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
          alert("Ajout du Ministere reussie!");
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


