<?php
require 'db_arche.php';
$cell_pastor = $pdo->query("SELECT * FROM pastors  ORDER BY first_name")->fetchAll();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $activity_date = $_POST['activity_date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $member_id = $_POST['member_id'];
  

    if (!$member_id) {
        die("Veuillez choisir un pasteur valide");
    }

    $stmt = $pdo->prepare("INSERT INTO calendar_activities (title, description, activity_date, time, location, member_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $activity_date, $time, $location, $member_id]);

    header("Location: arche_create_activity.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head> <!-- Begin of Head -->
  <meta charset="UTF-8" />
  <title>Creer une Activité</title>

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
<h4>Creer une Activité</h4><br><br>

<div class="form-group"> <!-- Application form -->
          
          <form method="POST" action="arche_create_activity.php">
            <table>
                <tr>
                    <td>
                        <label for="titel">Titre: </label>
                    </td>
                    <td>
                        <input type="text" name="title" placeholder="Titre" required>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="description">Description: </label>
                    </td>
                    <td>
                        <textarea name="description" id="description" rows="4" placeholder="Description de l'activité"
                        style="width: 295px; height: 50px; font-size: 12px;" required></textarea>
                    </td>
                </tr>
              
                <tr>
                    <td>
                        <label for="date">Date:</label>
                    </td>
                    <td>
                        <input type="date" name="activity_date">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="time">Heure:</label>
                    </td>
                    <td>
                        <input type="time" name="time">
                    </td>
                </tr>


                <tr>
                    <td>
                        <label for="location">Lieu:</label>
                    </td>
                    <td>
                        <input type="text" name="location" required>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <label for="member_first_name">Membre: </label>
                    </td>
                    <td>
                        <select name="member_id" id="member_id" style="width: 300px; height: 50px; font-size: 16px;"  required>
                            <option value="">-- Choisir un Membre --</option>

                            <?php
                              require 'db_arche.php';
                              $stmt = $pdo->query("SELECT id, first_name, last_name FROM members");
                              while ($row = $stmt->fetch()) {
                                echo "<option value='{$row['id']}'>" . htmlspecialchars($row['first_name'] . " " . $row['last_name']) . "</option>";
                              }
                            ?>

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
          alert("Ajout de l'activité reussie!");
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