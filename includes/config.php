<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";

try {
  $conn = new PDO("mysql:host=$servername;dbname=2025_itutrustning;charset=utf8mb4", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully<br>";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

if (!isset($_SESSION)) {
    session_start();
}

// Include the USER class file
require_once 'class.user.php';

$user = new USER($conn);
?>
