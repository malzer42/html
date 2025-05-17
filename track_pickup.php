<?php
session_start();
unset($_SESSION['dashboard_access']);

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
if ($conn->connect_error) die("Connection failed");

$pickup = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tracking = strtoupper(trim($_POST['tracking_number'] ?? ''));
    if (preg_match('/^PICK\d{6}$/', $tracking)) {
        $id = (int)substr($tracking, 4); // Extract numeric ID
        $stmt = $conn->prepare("SELECT * FROM pickups WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pickup = $result->fetch_assoc();
        $stmt->close();

        if (!$pickup) {
            $error = "No pickup found with tracking number: $tracking";
        }
    } else {
        $error = "Invalid tracking number format.";
    }
}
?>

<!DOCTYPE html>
<html>

<head> <!-- Begin of Head -->
    <meta charset="UTF-8" />
    <title>Track Your Pickup</title>
    
    <!-------------------------Link to external CSS----------->
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    
    <!-- Link to external JS -->
    <script type="text/javascript" src="js/header.js" defer></script>
    <script type="text/javascript" src="js/footer.js" defer></script>
    <script type="text/javascript" src="js/chat.js" defer></script>

  </head> <!-- End of Head -->


<body>
    <div id="footer"></div>

    <h4>Track Your Pickup</h4>

    <div class="form-group"> <!-- Application form -->
        <form method="POST">
            <table>
                <tr>
                    <td>
                        <label for="tracking">Tracking Number: </label>
                    </td>
                    <td>
                        <input type="text" name="tracking_number" required placeholder="e.g. PICK000123" />
                    </td>
                </tr>
                <td>
                    <td>
                        <div class="cf-turnstile" data-sitekey="0x4AAAAAABT_sdEEEltXHkt4"></div>
                        <div>
                            <button type="submit" style="width: 300px; height: 50px; font-size: 16px;">Click To Track</button>
                        </div>
                    </td>
                </td>

            </table>
       
        </form>

    </div>

    <?php if ($pickup): ?>
        <h3>Pickup Details:</h3>
        <ul>
            <li><small>Name:   <?= htmlspecialchars($pickup['name']) ?></small></li>
            <li><small>Email:  <?= htmlspecialchars($pickup['email']) ?></small></li>
            <li><small>Date:   <?= $pickup['pickup_date'] ?></small></li>
            <li><small>Time:   <?= $pickup['pickup_time'] ?></small></li>
            <li><small>Status: <?= $pickup['status'] ?></small></li>
        </ul>
    <?php elseif ($error): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    
    <div id="header"></div>

</body>

</html>
