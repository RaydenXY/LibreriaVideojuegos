<?php
session_start();
require "conexion.php";

if(!empty($_POST['email']) && !empty($_POST['userpass'])){

    $email = $_POST['email'];
    $password = $_POST['userpass'];

    // Buscar al usuario en la base de datos
    $stmt = $conn->prepare("SELECT * FROM MyGuests WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){
        // Verifica la contraseña usando password_verify
        if(password_verify($password, $user['password'])){
            // Login exitoso
            $_SESSION['loggedin'] = true;
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['email'] = $user['email'];

            header("Location: welcome.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Contraseña incorrecta";
        }
    } else {
        $_SESSION['login_error'] = "Usuario no encontrado";
    }
} else {
    $_SESSION['login_error'] = "Completa todos los campos";
}
//test
// Si hay error, vuelve a login
header("Location: login.php");
exit;
?>