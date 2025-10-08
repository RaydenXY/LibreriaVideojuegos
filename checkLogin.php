<?php
session_start();
require "conexion.php";

if(!empty($_POST['email']) && !empty($_POST['userpass'])){

    $email = $_POST['email'];
    $password = $_POST['userpass'];

    
    $stmt = $conn->prepare("SELECT * FROM MyGuests WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){
        
        if(password_verify($password, $user['password'])){
            
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['email'] = $user['email'];

            header("Location: ListaVid.php");
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

// Si hay error, vuelve a login
header("Location: login.php");
exit;
?>