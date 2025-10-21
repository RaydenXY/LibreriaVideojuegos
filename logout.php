<?php
session_start();
require "conexion.php";

if (isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];

    $stmt = $conn->prepare("DELETE FROM tokens WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    
    setcookie("token", "", time() - 3600, "/");
}

session_unset();   
session_destroy(); 
header("Location: login.php");
exit;
?>