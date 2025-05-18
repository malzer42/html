<?php
require 'db_arche.php';
$cell_pastor = $pdo->query("SELECT * FROM pastors  ORDER BY first_name")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pastor_id = $_POST['pastor_id'];
    $day_of_week = $_POST['day_of_week'];
    $time = $_POST['time'];
    $location = $_POST['location'];

    $stmt = $pdo->prepare("INSERT INTO prayer_schedules (pastor_id, day_of_week, time, location) VALUES (?, ?, ?, ?)");
    $stmt->execute([$pastor_id, $day_of_week, $time, $location]);

    $msg = "Schedule saved.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Horaire Prieres</title>
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
<body>
    <div id="arche_footer"></div>
<br>
<h4>Ajout d'une Reunion des prieres </h4>
<?php if (isset($msg)) echo "<p>$msg</p>"; ?>
<br><br>

<div class="form-group"> 
<form method="POST">
    <table>
        <tr>
            <td>
                <label for="pastor_first_name">Pasteur: </label>
            </td>
            <td>
                <select name="pastor_id" id="pastor_id" style="width: 300px; height: 50px; font-size: 16px;"  required>
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
                <label for="day_of_week">Jour:</label>
            </td>
            <td>
                <select name="day_of_week" style="width: 300px; height: 50px; font-size: 16px;" >
                    <?php foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $d) {
                        echo "<option value='$d'>$d</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="time">Heure:</label>
            </td>
            <td>
                <input type="time" name="time" required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="location">Place:</label>
            </td>
            <td>
                <input type="text" name="location" required><br>

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
