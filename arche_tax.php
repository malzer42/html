<?php
session_start();
require 'db_arche.php';

/* if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'main_pastor') {
    header("Location: arche_tax_login.php");
    exit;
}  */

// Default date range
$start = $_GET['start_date'] ?? date('Y-01-01');
$end = $_GET['end_date'] ?? date('Y-12-31');

// Get member donation totals
$sql = "SELECT m.id, m.first_name, m.last_name, m.email, 
               COALESCE(SUM(d.amount), 0) AS total_donations
        FROM members m
        LEFT JOIN donations d ON m.id = d.member_id 
              AND d.donation_date BETWEEN ? AND ?
        GROUP BY m.id ORDER BY m.first_name" ;

$stmt = $pdo->prepare($sql);
$stmt->execute([$start, $end]);
$members = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Retour d'impot des membres </title>
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
    <div id="arche_footer"></div><br>



    <h4>Dimes, Offrancdes, et Dons des membres - Retour d'impots</h4><br>

    <form method="GET">
        <label>Start Date: <input type="date" name="start_date" value="<?= htmlspecialchars($start) ?>"></label>
        <label>End Date: <input type="date" name="end_date" value="<?= htmlspecialchars($end) ?>"></label>
        <button type="submit">Filter</button>
    </form>
    <br>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Total Donations</th>
            <th>Action</th>
        </tr>
        <?php foreach ($members as $member): ?>
        <tr>
            <td><?= htmlspecialchars($member['first_name']) ?></td>
            <td><?= htmlspecialchars($member['last_name']) ?></td>
            <td><?= htmlspecialchars($member['email']) ?></td>
            <td><?= number_format($member['total_donations'], 2) ?> $</td>
            <td>
                <form method="POST" action="send_tax_return.php" onsubmit="return confirm('Send tax return PDF to <?= htmlspecialchars($member['email']) ?>?');">
                    <input type="hidden" name="member_id" value="<?= $member['id'] ?>">
                    <input type="hidden" name="start_date" value="<?= $start ?>">
                    <input type="hidden" name="end_date" value="<?= $end ?>">
                    <button type="submit">Envoyer le recu d'Impots PDF</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table><br>
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








