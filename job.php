<?php
// Enable error reporting (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
unset($_SESSION['dashboard_access']);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure PHPMailer is installed via Composer
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database configuration
    $host = $_ENV['DB_HOST'];
    $dbname = $_ENV['DB_NAME'];
    $username = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        $error = "Database connection failed: " . $conn->connect_error;
    } else {
        $uploads_dir = 'cvs/';
        if (!is_dir($uploads_dir)) mkdir($uploads_dir);

        $position = $_POST['position'] ?? '';
        $first_name = $_POST['first_name'] ?? '';
        $last_name  = $_POST['last_name'] ?? '';
        $phone_number = $_POST['phone_number'] ?? '';
        $email = $_POST['user_email'] ?? '';
        $country = $_POST['country'] ?? '';
        $user_message = $_POST['user_message'] ?? '';

        $cv_file = $_FILES['cv'];
        $cv_filename = basename($cv_file['name']);
        $target = $uploads_dir . $cv_filename;

        // File type validation (optional)
        $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($cv_file['type'], $allowed_types)) {
            $error = "Only PDF, DOC, or DOCX files are allowed.";
        } elseif (move_uploaded_file($cv_file['tmp_name'], $target)) {
            // Save to database
            $stmt = $conn->prepare("INSERT INTO applications (position, first_name, last_name, phone_number, email, country, user_message, cv_filename) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $position, $first_name, $last_name, $phone_number, $email, $country, $user_message, $cv_filename);
            $stmt->execute();
            $stmt->close();

            $success = "Application submitted successfully.";

            // Optional: send email (PHPMailer integration here if needed)

            // Email HR
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'pmulamba@gmail.com'; // Replace
                $mail->Password = $_ENV['EMAIL_PASS']; //'lbtq ienr hrjp qvde';    // App password, not your Gmail password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom($_ENV['EMAIL_USER'], 'Career Portal'); // pmulamba@gmail.com
                $mail->addAddress($_ENV['EMAIL_RECEIVER'], 'HR Department'); // Change to your HR address
                $mail->addReplyTo($email, "$first_name $last_name");

                $mail->Subject = "New Application: $position";
                $mail->Body = "Name: $first_name $last_name\nEmail: $email\nPhone: $phone_number\nMessage: $user_message";
                $mail->addAttachment($target, $cv_filename);

                $mail->send();
                $success = "Application submitted and email sent to HR.";
            } catch (Exception $e) {
                $error = "Application saved, but email failed: " . $mail->ErrorInfo;
            }



        } else {
            $error = "Failed to upload CV.";
        }
        $conn->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Career</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
  <!-- Link to external JS -->
  <script type="text/javascript" src="js/header.js" defer></script>
  <script type="text/javascript" src="js/footer.js" defer></script>
  <script type="text/javascript" src="js/chat.js" defer></script>
  <script type="text/javascript" src="https://kit.fontawesome.com/b99e675b6e.js"></script>

  <style>
    .message { color: green; font-weight: bold; text-align: cbter;}
    .error { color: red; font-weight: bold; }
  </style>

</head>

<body>

    <div id="footer"></div>

    <main>

        <h1><i>Join Rapix!</i></h1>

        <section>

        <div>

            <h4>Apply for a Job</h4>

            

        </div>

        <div class="form-group"> <!-- Application form -->

        <form action="job.php" method="POST" id="careerForm" enctype="multipart/form-data">

          <table>
            <tr>
              <td>
                <label for="position">Position: </label>
              </td>
              <td>
                <select name="position" id="position" >
                  <option value="account">Account</option>
                  <option value="business">Business Analyst</option>
                  <option value="dispatcher">Dispatcher</option>
                  <option value="driver">Driver</option>
                  <option value="developper">Software developper</option>
                  <option value="cservice">Customer service</option>
                  <option value="reception">Receptionist</option>
                  <option>Other</option>
                </select>
              </td>
            </tr>

            <tr>
              <td>
                <label for="first_name">First Name: </label>
              </td>
              <td>
                <input type="text" name="first_name" placeholder="Your First Name" required>
              </td>
            </tr>

            <tr>
              <td>
                <label for="last_name">Last Name: </label>
              </td>
              <td>
                <input type="text" name="last_name" placeholder="Your Last Name" required>
              </td>
            </tr>

            <tr>
              <td>
                <label for="phone">Phone: </label>
              </td>
              <td>
                <input type="tel" name="phone_number" pattern="^\+?[0-9\s\-\(\)]{7,15}$" placeholder="+1 (123) 456-7890"
                  required>
              </td>
            </tr>

            <tr>
              <td>
                <label for="email">Email: </label>
              </td>
              <td>
                <input type="email" name="user_email" placeholder="Your Email" required>
              </td>
            </tr>

            <!--tr>
              <td>
                <label for="gender">Gender: </label>
              </td>
              <td>
                <input type="radio" value="Male" name="gender">Male
                <input type="radio" value="Female" name="gender">Female
              </td>
            </tr -->

            <tr>
              <td>
                <label for="country">Country: </label>
              </td>
              <td>
                <select name="country" id="country">
                  <option value="canada">Canada</option>
                  <option value="usa">USA</option>
                  <option value="rdc">Dem. Rep. Congo</option>
                  <option value="sa">South Africa</option>
                  <option value="other">Other</option>
                </select>
              </td>
            </tr>

            <tr>
              <td>
                <label for="user_message">Message: </label>
              </td>
              <td>
                <textarea name="user_message" placeholder="Your Message"  style="width: 300px; height: 50px; font-size: 16px;" ></textarea>
              </td>
            </tr>

            <tr>
              <td>
                <label for="cv">CV (pdf, doc, docx): </label>
              </td>
              <td>
                <input type="file" name="cv" accept=\".pdf, .doc, .docx" style="width: 300px; height: 50px; font-size: 16px;"
                  required />
              </td>
            </tr>

            <td>
              <td>
                <div class="cf-turnstile" data-sitekey="0x4AAAAAABT_sdEEEltXHkt4"></div>
                <div>
                  <button type="submit" value="Submit Application" style="width: 200px; height: 50px; font-size: 16px;">Submit</button>
                  <input type="reset" value="Reset" style="width: 100px; height: 50px; font-size: 16px;">

                </div>

              </td>
            </td>

          </table>

        </form>

      </div>

    </section>

</main>


<?php if ($success): ?>
    <div class="message"><?= htmlspecialchars($success) ?>
        
    </div>
<?php elseif ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?>

    </div>

<?php endif; ?>

<div id="header"></div>


  
</body>
</html>