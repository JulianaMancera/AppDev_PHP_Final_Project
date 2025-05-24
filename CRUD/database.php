<?php
// database.php configuration
$host = 'localhost';
$port = '3306';
$dbname = 'application_information';
$username = 'root';
$password = '';

$dsn = "mysql:host={$host};port={$port};dbname={$dbname}";

    

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password);

    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "Database Connected...";
} catch (PDOException $e) {
    // If there is an error with the connection, catch it
    echo "Connection Failed: " . $e->getMessage();
}
?>