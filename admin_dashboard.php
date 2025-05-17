<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !isset($_SESSION['dashboard_access'])  ) {
    header('Location: login.php');
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
<html>
<head> <!-- Begin of Head -->
    <meta charset="UTF-8" />
    <title>Admin Dashboard - Pickups </title>
    
    <!-------------------------Link to external CSS----------->
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    
    <!-- Link to external JS -->
    <script type="text/javascript" src="js/header.js" defer></script>
    <script type="text/javascript" src="js/footer.js" defer></script>
    <script type="text/javascript" src="js/chat.js" defer></script>

  </head> <!-- End of Head -->

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
<div id="footer"></div>
    <!-- <h1>Pickup Management Dashboard</h1> -->
    <h4>Pickup Schedule</h4>
    <table border="1" cellpadding="8">
        <tr>
            <th><small>ID</small></th>
            <th><small>Tracking</small></th>
            <th><small>Name</small></th>
            <th><small>Email</small></th>
            <th><small>Pickup Date</small></th>
            <th><small>Time</small></th>
            <th><small>Status</small></th>
            <th><small>Update</small></th>
        </tr>
        <?php while ($row = $resultPickups->fetch_assoc()): ?>
            <tr>
                <td><small><?= $row['id'] ?></small></td>
                <td><small><?= 'PICK' . str_pad($row['id'], 6, '0', STR_PAD_LEFT) ?></small></td>
                <td><small><?= htmlspecialchars($row['name']) ?></small></td>
                <td><small><?= htmlspecialchars($row['email']) ?></small></td>
                <td><small><?= $row['pickup_date'] ?></small></td>
                <td><small><?= $row['pickup_time'] ?></small></td>
                <td><small>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="pickup_id" value="<?= $row['id'] ?>">
                        <select name="status"><small>
                            <?php
                            $statuses = ['Scheduled', 'Out for Pickup', 'Completed', 'Cancelled'];
                            foreach ($statuses as $s):
                                $selected = $row['status'] === $s ? 'selected' : '';
                                echo "<option value=\"$s\" $selected>$s</option>";
                            endforeach;
                            ?>
                        </small></select>
                        <button type="submit" name="pickup_update_status">✔</button>
                    </form>
                </small></td>
                <td><small><a href="delete_pickup.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete pickup?')">Delete</a></small></td>
            </tr>
        <?php endwhile; ?>
    </table><br>

    <h4>Job Applications</h4>

    <table border="1" cellpadding="8">
        <tr>
            <th><small>ID</small></th>
            <th><small>Position</small></th>
            <th><small>First Name</small></th>
            <th><small>Last Name</small></th>
            <th><small>Phone</small></th>
            <th><small>Email</small></th>                
            <!-- <th>Message</th> -->
            <th><small>Resume</small></th>
            <th><small>Date</small></th>
            <th><small>Status</small></th>
            <th><small>Update</small></th>
        </tr>
        <?php while ($row = $resultJobApplications->fetch_assoc()): ?>
            <tr>
                <td><small><?= $row['id'] ?></small></td>
                <td><small><?= $row['position'] ?></small></td>
                <td><small><?= $row['first_name'] ?></small></td>
                <td><small><?= $row['last_name'] ?></small></td>
                <td><small><?= $row['phone_number'] ?></small></td>
                <td><small><?= htmlspecialchars($row['email']) ?></small></td>
                <!-- <td><?= htmlspecialchars($row['user_message']) ?></td>  -->
                 
                <!-- <td><a href="/cvs/htmlspecialchars($app['cv_filename'])<?= urlencode($app['cv_filename']) ?>" target="_blank">
                    View Resume 
                </a></td>  -->
                <td><small><?=htmlspecialchars($row['cv_filename']) ?></small></td> 
                <td><small><?= htmlspecialchars($row['submitted_at']) ?></small></td>
                
                
                <td><small>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="application_id" value="<?= $row['id'] ?>">
                        <select name="status">
                            <?php
                            $statuses = ['Pending', 'Confirmed', 'Declined'];
                            foreach ($statuses as $s):
                                $selected = $row['status'] === $s ? 'selected' : '';
                                echo "<option value=\"$s\" $selected>$s</option>";
                            endforeach;
                            ?>
                        </select>
                        <button type="submit" name="update_status">✔</button>
                    </form>
                </small></td>
                <td><small><a href="delete_application.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete pickup?')">Delete</a></small></td>
            </tr>
        <?php endwhile; ?>
    </table><br>

    <h4>Internal Management Options </h4>
    <div>
        <a href="logout.php"><button type="submit" value="logout" style="width: 100px; height: 50px; font-size: 16px;">Logout</button></a>
        <a href="https://telusidentity.telus.com/as/authorization.oauth2?response_type=code&scope=adc+openid&redirect_uri=https%3a%2f%2fsmarthome-security.telus.com%2fweb%2fapi%2fTokenSso.ashx%3fAuthProvider%3d9AE4AC54755C5EBFC489CB6CFAA69C1C&state=https%3a%2f%2fsmarthome-security.telus.com%2fweb%2fDetermineLandingPage.aspx&client_id=167c1141-d72c-4d39-8c72-1cebd10e0109&service_type=adc" target="_blank">
        <button type="submit" value="telus" style="width: 100px; height: 50px; font-size: 16px;">Telus</button></a>
        <a href="https://tcm900.pamit.net" target="_blank"><button type="submit" value="tcm900" style="width: 100px; height: 50px; font-size: 16px;">Tcm900</button></a> 
        <a href="https://dash.cloudflare.com/login" target="_blank"><button type="submit" value="cloudfare" style="width: 100px; height: 50px; font-size: 16px;">Cloudfare</button></a> 
        <a href="https://id.manulife.ca/member/signin?sign_out=sign-out&ui_locales=en-CA" target="_blank"><button type="submit" value="manulife" style="width: 100px; height: 50px; font-size: 16px;">Manulife</button></a>
        <a href="https://dashboard.tawk.to/login" target="_blank"><button type="submit" value="tawk" style="width: 100px; height: 50px; font-size: 16px;">Tawk</button></a> 
        <a href="arche.html" target="_blank"><button type="submit" value="tawk" style="width: 100px; height: 50px; font-size: 16px;">Arche</button></a> 
    </div>

    


    

    <!-- <div id="footer"></div> -->

    <div id="header"></div>

</body>
</html>
