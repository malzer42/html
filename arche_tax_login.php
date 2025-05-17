<?php
// Enable error reporting (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// MySQL connection variables

// Now access env variables
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$dbuser = $_ENV['DB_USER'];
$dbpass = $_ENV['DB_PASS'];



$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $conn->real_escape_string($_POST["username"]);
    $password = $_POST["password"];

    $result = $conn->query("SELECT * FROM admins WHERE username = '$username'");
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin["password"])) {
            $_SESSION["admin_logged_in"] = true;
            $_SESSION['dashboard_access'] = true;
            header('Location: arche_tax.php');
            //header("Location: view_pickups.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>

<head> <!-- Begin of Head -->
    <meta charset="UTF-8" />
    <title>Impots </title>
    
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
  <div id="arche_footer"></div>
  <h3>Adminitration des impots </h3>
  <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>

  <div class="form-group"> <!-- Application form -->

    <form method="post">
      <table>

        <tr>
          <td>
            <label for="username">Administrateur:</label>
          </td>
          <td>
            <input type="text" name="username" required>
          </td>
        </tr>

        <tr>
          <td>
            <label for="password">Mot de Passe:</label>
          </td>
          <td>
            <input type="password" name="password" required>
          </td>
        </tr>

        <td>
            <td>
                <div class="cf-turnstile" data-sitekey="0x4AAAAAABT_sdEEEltXHkt4"></div>
                <div>
                  <input type="submit" value="Soumettre">
                
                </div>
            </td>
        </td>

      </table>
    </form>

  </div>
  <div id="arche_header"></div>
</body>
</html>
