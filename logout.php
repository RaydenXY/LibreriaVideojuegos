<?php
session_start();

if (isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];

    $stmt = $conn->prepare("DELETE FROM tokens WHERE token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    // Eliminar la cookie
    setcookie("token", "", time() - 3600, "/");
}

session_unset();   
session_destroy(); 
header("Location: login.php");
exit;
?>