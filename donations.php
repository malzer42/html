<?php
require 'db_arche.php';
$arche_member = $pdo->query("SELECT * FROM members  ORDER BY first_name")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = intval($_POST['member_id']);
    $amount = $_POST['amount'];
    $donation_date = $_POST['donation_date'];
    $note = $_POST['donation_note'];


    if (!$member_id) {
        die("Veuillez choisir un member valide");
    }

    $stmt = $pdo->prepare("INSERT INTO donations (member_id, amount, donation_date, note) VALUES (?, ?, ?, ?)");
    $stmt->execute([$member_id, $amount, $donation_date, $note]);

    header("Location: donations.php?success=1");
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
        <h4>Dimes, Don, Offrandes, et Autres</h4><br><br>

        <div class="form-group"> <!-- Application form -->
          
          <form method="POST" action="donations.php">
            <table>

              <tr>
                <td>
                  <label for="member_id">Membre: </label>
                </td>
                <td>
                  <select name="member_id" id="member_id" style="width: 300px; height: 50px; font-size: 16px;"  required>
                    <option value="">-- Choisir le Membre --</option>
                      <?php foreach ($arche_member as $am): ?>
                            <option value="<?= $am['id'] ?>">
                            <?= $am['first_name'] ?> - <?= $am['last_name'] ?>
                            </option>
                        <?php endforeach; ?>
                  </select>
                </td>

              <tr>
                <td>
                  <label for="amount">Montant: </label>
                </td>
                <td>
                    <input type="number" step="0.01" name="amount" required>
                </td>
              </tr>
            
              <tr>
                <td>
                  <label for="date">Date: </label>
                </td>
                <td>
                    <input type="date" name="donation_date" required>

                </td>
              </tr>


              <tr>
                <td>
                  <label for="donation_note">Note: </label>
                </td>
                <td>
                  <select name="donation_note" id="donation_note" style="width: 300px; height: 50px; font-size: 16px;" required>
                    <option value="">-- Choisir --</option>
                    <option value="dime">Dime</option>
                    <option value="offrandes">Offrandes</option>
                    <option value="Don">Don</option>
                    <option value="Autre">Autre</option>

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
          alert("Ajout reussie!");
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