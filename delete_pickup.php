<?php
// Connect to your database
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get the pickup ID from the query string
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $pickupId = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM pickups WHERE id = ?");
    if ($stmt->execute([$pickupId])) {
        echo "Pickup deleted successfully.";
        // Optionally redirect:
        // header("Location: dashboard.php");
    } else {
        echo "Failed to delete pickup.";
    }
} else {
    echo "Invalid pickup ID.";
}
?>
