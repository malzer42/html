<?php
require 'db_arche.php';

$stmt = $pdo->query("
    SELECT 
        p.day_of_week,
        p.time,
        p.location,
        ps.first_name,
        ps.last_name
    FROM prayer_schedules p
    JOIN pastors ps ON p.pastor_id = ps.id
    ORDER BY 
        FIELD(p.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
        p.time
");

$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<h4>Horaire des Prieres</h4><br>

<table border="1" cellpadding="10">
    <tr>
        <th><small>Jour</small></th>
        <th><small>Heure</small></th>
        <th><small>Lieu</small></th>
        <th><small>Pasteur</small></th>
    </tr>
    <?php foreach ($schedules as $row): ?>
        <tr>
            <td><small><?= htmlspecialchars((string)($row['day_of_week'] ?? '')) ?></small></td>
            <td><small><?= htmlspecialchars($row['time']) ?></small></td>
            <td><small><?= htmlspecialchars($row['location']) ?></small></td>
            <td><small><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></small></td>
        </tr>
    <?php endforeach; ?>
</table>
<br>

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