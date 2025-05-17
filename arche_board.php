
<?php

session_start();
if (!isset($_SESSION['admin_logged_in']) || !isset($_SESSION['dashboard_access'])  ) {
    header('Location: arche_board_login.php');
    exit;
}

require 'db_arche.php';

$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$params = [];
$where = '';

if ($start_date && $end_date) {
    $where = "WHERE d.date BETWEEN :start_date AND :end_date";
    $params = [
        ':start_date' => $start_date,
        ':end_date' => $end_date
    ];
}

/* $sql = "
    SELECT m.first_name, m.last_name, SUM(d.amount) AS total_donations
    FROM members m
    LEFT JOIN donations d ON m.id = d.member_id
    $where
    GROUP BY m.id, m.first_name, m.last_name
"; */


$sql = "
    SELECT m.first_name, m.last_name, d.amount, d.donation_date, d.note 
    FROM members m
    LEFT JOIN donations d ON m.id = d.member_id
    ORDER BY d.donation_date DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();
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
<style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
    }
    h2 {
        margin-top: 40px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #aaa;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #eee;
    }
</style>


<body>

<div id="arche_footer"></div>
  <!-- <div class="overlay"></div> -->

  <main> <!-- Beginning of Main -->
    <br>
    <h4>Dimes, Offrandes, Dons</h4>

    <table>
        <tr>
            <th><small>Prénom</small></th>
            <th><small>Nom</small></th>
            <th><small>Montant</small></th>
            <th><small>Date</small></th>
            <th><small>Note</small></th>
            <!-- <th><small>Total</small></th> -->
        </tr>
        <tbody>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><small><?= htmlspecialchars($row['first_name']) ?></small></td>
                    <td><small><?= htmlspecialchars($row['last_name']) ?></small></td>
                    <td><small>$<?= number_format($row['amount'] ?? 0, 2) ?></small></td>
                    <td><small><?= htmlspecialchars($row['donation_date'] ?? '') ?></small></td>
                    <td><small><?= htmlspecialchars($row['note'] ?? '') ?></small></td>
                    <!-- <td><small>$<?= number_format($row['total_donations'] ?? 0, 2) ?></small></td> -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    
    </table><br>

   <!--  <h4>Gestion Interne </h4>
    <div>
        <a href="logout.php"><button type="submit" value="logout" style="width: 100px; height: 50px; font-size: 16px;">Logout</button></a>
        
        <a href="arche.html" target="_blank"><button type="submit" value="tawk" style="width: 100px; height: 50px; font-size: 16px;">Arche</button></a> 
    </div>
 -->


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



