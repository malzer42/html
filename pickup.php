<?php

// Enable error reporting (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Dompdf\Dompdf;
    
require 'vendor/autoload.php';

require_once 'vendor/autoload.php';
session_start();
unset($_SESSION['dashboard_access']);

require __DIR__ . '/vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// MySQL connection variables
require 'db.php';


// Create a database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$submitted = false;
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and get POST data
  $name = $conn->real_escape_string($_POST["name"]);
  $address = $conn->real_escape_string($_POST["address"]);
  $phone = $conn->real_escape_string($_POST["phone"]);
  $email = $_POST['user_email'] ?? '';
  $pickup_date = $_POST["pickup_date"];
  $pickup_time = $_POST["pickup_time"];
  $package_details = $conn->real_escape_string($_POST["package_details"]);

  // Insert into database
  $stmt = $conn->prepare("INSERT INTO pickups (name, address, phone, email, pickup_date, pickup_time, package_details) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssss", $name, $address, $phone, $email, $pickup_date, $pickup_time, $package_details);
  $stmt->execute();
  $pickup_id = $stmt->insert_id; // Get the auto-increment ID
  $stmt->close();

  $tracking_number = 'PICK' . str_pad($pickup_id, 6, '0', STR_PAD_LEFT);


  // Generate a PDF receipt
  $receiptHtml = "
    <h4>Rapix Inc.</h4>
    <p><small>4541 Rue Noel, Terrebonne</small></p>
    <p><small>J7M 0H8</small></p>
    <p><small>Qc, Canada</small></p>
    <br>

    <h4>Pickup Receipt</h4>
    <p><small>Tracking Number: $tracking_number </small></p>
    <p><small>Name: $name </small></p>
    <p><small>Email: {$_POST['user_email']} </small></p>
    <p><small>Date: $pickup_date</small></p>
    <p><small>Time: $pickup_time </small></p>
    <p><small>Thank you for scheduling with Rapix Delivery Service.</small></p>
  ";

  $dompdf = new Dompdf();
  $dompdf->loadHtml($receiptHtml);
  $dompdf->setPaper('A4', 'portrait');
  $dompdf->render();

  // Save PDF to a temp file
  $pdfOutput = $dompdf->output();
  $pdfPath = __DIR__ . "/pickup_receipt_" . time() . "_$name.pdf";
  file_put_contents($pdfPath, $pdfOutput);
    
  // Send confirmation email
  $mail = new PHPMailer(true);
    
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'pmulamba@gmail.com';
    $mail->Password = $_ENV['EMAIL_PASS'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    
    $mail->setFrom($_ENV['EMAIL_USER'], 'Rapix Inc.');
    $mail->addAddress($_ENV['EMAIL_RECEIVER'], $name); // Client email from form
    $mail->addReplyTo($email, "$name $tracking_number");
    
    if (!empty($_ENV['EMAIL_RECEIVER'])) {
      $mail->addCC($_ENV['EMAIL_RECEIVER']); // optional internal copy
    }
    
    $mail->isHTML(true);
    $mail->Subject = 'Pickup Confirmation';
    $mail->Body = "
            <h3>Hi $name,</h3>
            <p>Your pickup has been scheduled.</p>
            <ul>
            <li><strong>Date:</strong> $pickup_date</li>
            <li><strong>Time:</strong> $pickup_time</li>
            <li><strong>Tracking Number:</strong> $tracking_number</li>
            </ul>
            <p>Thank you for using Rapix Delivery!</p>
    ";
    
    $mail->addAttachment($pdfPath, "pickup_receipt.pdf");
    
    $mail->send();
    $success = "Pickup scheduled and tracking number sent.";
    unlink($pdfPath); // delete the file after sending

  } catch (Exception $e) {
    
    $error = "Email could not be sent" . $mail->ErrorInfo;
  }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Schedule a Package Pickupr</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
  <!-- Link to external JS -->
  <script type="text/javascript" src="js/header.js" defer></script>
  <script type="text/javascript" src="js/footer.js" defer></script>
  <script type="text/javascript" src="js/chat.js" defer></script>
  <script type="text/javascript" src="https://kit.fontawesome.com/b99e675b6e.js"></script>

  <style>
    .container {
      flex-direction: row;
      display: flex;
      height: 100vh; /* Full height of the viewport */
    }
    
    .left {
      flex: 3; /* 70% */
      background-color: white;  
    }
    .right {
      flex: 7; /* 70% */
      background-color: white; 
    }
    /* Responsive stacking for screens narrower than 768px */
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }
      .left, .right {
        height: 50vh; /* Half height each */
      }
    }

  </style>

  <style>
    .message { color: green; font-weight: bold; text-align: cbter;}
    .error { color: red; font-weight: bold; }
  </style>

</head>


<body>
  
  <div id="footer"></div>

  <main>
  <?php if ($submitted): ?>
    <h2>Pickup Scheduled Successfully!</h2>
    <p>Thank you, <?= htmlspecialchars($name) ?>. Your pickup has been scheduled.</p>
  <?php else: ?>    
  

  <div class="container">
    <div class="left">
      
    

    <h4><i>Schedule a Package Pickup</i></h4>
    
    <section>

    
    <div class="form-group"> <!-- Pickup schedule -->

      <form method="post" action="">

        <table>
          <tr>
            <td>
              <label for="name">Name:</label>
            </td>
            <td>
              <input type="text" name="name" id="name"  placeholder="Your Name"required>
            </td>
       
          </tr>
          
          <tr>
            <td>
              <label for="address">Address:</label>
            </td>
            <td>
              <textarea name="address" id="address" rows="4"  placeholder="Pickup Address "  style="width: 295px; height: 50px; font-size: 16px;"  required></textarea>
            </td>
          </tr>

          <tr>
            <td>
              <label for="phone">Phone:</label>
            </td>
            <td>
              <input type="tel" name="phone" id="phone" pattern="^\+?[0-9\s\-\(\)]{7,15}$" placeholder="+1 (123) 456-7890"
              required>
            </td>
          </tr>

          <tr>
            <td>
              <label for="email">Email: </label>
            </td>
            <td>
              <input type="email" name="user_email"  placeholder="Your Email"required>
            </td>
          </tr>

          <tr>
            <td>
              <label for="pickup_date">Date:</label>
            </td>
            <td>
              <input type="date" name="pickup_date" id="pickup_date" required>
            </td>
          </tr>

          <tr>
            <td>
              <label for="pickup_time">Time:</label>
            </td>
            <td>
              <input type="time" name="pickup_time" id="pickup_time" required>
            </td>
          
          </tr>

          <tr>
            <td>
              <label for="package_details">Details:</label>
            </td>
            <td>
              <textarea name="package_details" id="package_details"  placeholder="Package Details" rows="4" style="width: 295px; height: 20px; font-size: 16px;"  required></textarea>
            </td>
          </tr>

          <td>
            <td>
            <div class="cf-turnstile" data-sitekey="0x4AAAAAABT_sdEEEltXHkt4"></div>
            <div>
              <input type="submit" value="Schedule Pickup">
            </div>
            </td>
          </td>

        </table>

      </form>

      <?php endif; ?>


    </div>

    </section>

    </div>
    <div class="right">
      
      <h2><i></i></h2>

      <div class="rounded-lg overflow-hidden shadow-lg">
          <iframe src="https://www.google.com/maps?q=45.75376356276172,
                              -73.69801357411401&hl=es;z=14&output=embed" width="100%" height="700" style="border:0;"
              allowfullscreen="" loading="lazy">
                
          </iframe>
        </div>
    
    </div>
  </div>

  </main>

  <?php if ($success): ?>
    <div class="message"><?= htmlspecialchars($success) ?></div>
  <?php elseif ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div id="header"></div>
</body>
</html>

<?php $conn->close(); ?>



