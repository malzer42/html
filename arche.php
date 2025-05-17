<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !isset($_SESSION['dashboard_access'])  ) {
    header('Location: arche_login.php');
    exit;
}

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
if ($conn->connect_error) die("DB connection failed.");

// Handle pickup status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pickup_update_status'])) {
    $id = (int)$_POST['pickup_id'];
    $status_pickup = $_POST['status'];
    $stmt1 = $conn->prepare("UPDATE pickups SET status = ? WHERE id = ?");
    $stmt1->bind_param("si", $status_pickup, $id);
    $stmt1->execute();
    $stmt1->close();
}

// Handle application status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_update_status'])) {
    $id = (int)$_POST['application_id'];
    $status_application = $_POST['status'];
    $stmt2 = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt2->bind_param("si", $status_application, $id);
    $stmt2->execute();
    $stmt2->close();
}



// Fetch all pickups
$resultPickups = $conn->query("SELECT * FROM pickups ORDER BY pickup_date DESC");

// Fetch all job Applications
$resultJobApplications = $conn->query("SELECT * FROM applications ORDER BY submitted_at DESC");
?>



<!DOCTYPE html>
<html lang="en">

<head> <!-- Begin of Head -->
  <meta charset="UTF-8" />
  <title>Arche</title>

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

        <div class="bg-gray-800 p-6 rounded-2xl shadow-lg text-center">
          <h4 class="text-xl font-bold text-yellow-300">Donatien Badibanga Ntumba</h4>
          <!-- <p class="text-sm text-gray-400 mb-2">Pasteur Principal</p> -->
          <img src="pasteurBadi.avif" height="700" width="94%" alt="Team Member" class="mx-auto rounded-full mb-4">


        </div>



        </section> <!-- End of section 5 -->

      </div>
      <div class="right">

        <h4><i>Arche de Dieu</i></h4>

        <div class="rounded-lg overflow-hidden shadow-lg">
          <iframe src="https://www.google.com/maps?q=45.54164,
                                          -73.63918&hl=es;z=14&output=embed" width="100%" height="700"
            style="border:0;" allowfullscreen="" loading="lazy">

          </iframe>
        </div>

      </div>
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