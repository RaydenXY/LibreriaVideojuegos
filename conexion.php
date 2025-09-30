<?php
$user = "root";
$pass = "";
$servername = "localhost";
try {
  $conn = new PDO("mysql:host=$servername;dbname=myDB", $user, $pass);  
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>