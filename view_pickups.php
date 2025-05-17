<?php
// Enable error reporting (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
unset($_SESSION['dashboard_access']);
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}


require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// MySQL connection variables

require 'db.php';

// Now access env variables
/* $host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
 */
// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch pickups
$sql = "SELECT * FROM pickups ORDER BY pickup_date, pickup_time";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Scheduled Pickups</title>
  <style>
    table {
      border-collapse: collapse;
      width: 90%;
      margin: 20px auto;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
    }
    th {
      background-color: #eee;
    }
    h2 {
      text-align: center;
    }
  </style>
</head>
<body>

  <h2>Scheduled Package Pickups</h2>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <tr>
        <th>ID</th>
        <th>Sender Name</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Date</th>
        <th>Time</th>
        <th>Package Details</th>
        <th>Submitted At</th>
      </tr>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row["id"] ?></td>
          <td><?= htmlspecialchars($row["name"]) ?></td>
          <td><?= nl2br(htmlspecialchars($row["address"])) ?></td>
          <td><?= htmlspecialchars($row["phone"]) ?></td>
          <td><?= $row["pickup_date"] ?></td>
          <td><?= $row["pickup_time"] ?></td>
          <td><?= nl2br(htmlspecialchars($row["package_details"])) ?></td>
          <td><?= $row["created_at"] ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p style="text-align:center;">No pickups scheduled yet.</p>
  <?php endif; ?>

  <p style="text-align:right;"><a href="logout.php">Logout</a></p>

</body>

</html>

<?php $conn->close(); ?>
