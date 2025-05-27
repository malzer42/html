<!-- arche_finance_admins_login.php -->
<?php
session_start();
require 'db_arche.php'; // Your DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Correct login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: arche_track_rdv.php");
        // header("Location: arche.php");
        exit;
    } else {
        $error = "Administrateur ou Mot de passe invalide";
    }
}
?>
<!DOCTYPE html>
<html>

<head> <!-- Begin of Head -->
    <meta charset="UTF-8" />
    <title>Administrateur RDV Login </title>
    
    <!-------------------------Link to external CSS----------->
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    
    <!-- Link to external JS -->
    <script type="text/javascript" src="js/arche_header.js" defer></script>
    <script type="text/javascript" src="js/arche_footer.js" defer></script>
    <script type="text/javascript" src="js/chat.js" defer></script>

  </head> <!-- End of Head -->



<body>
  <div id="arche_footer"></div>
  <br><br>

  <h3>Administrateur RDV de l'Arche de Dieu </h3><br><br>
  <!-- <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?> -->

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
      <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </form>

  </div>
  <div id="arche_header"></div>
</body>
</html>