<?php
session_start();
require "conexion.php";

if (!empty($_POST['email']) && !empty($_POST['userpass'])) {

    $email = $_POST['email'];
    $password = $_POST['userpass'];


    $stmt = $conn->prepare("SELECT * FROM MyGuests WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        if (password_verify($password, $user['password'])) {

            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['email'] = $user['email'];

            if (isset($_POST['recordar'])) {
                $token = bin2hex(random_bytes(32));
                $expiracion = date('Y-m-d H:i:s', time() + (7 * 24 * 60 * 60));

                $stmt = $conn->prepare("INSERT INTO tokens (user_id, token, expiracion) VALUES (:user_id, :token, :expiracion)");
                $stmt->bindParam(':user_id', $user['id']);
                $stmt->bindParam(':token', $token);
                $stmt->bindParam(':expiracion', $expiracion);
                $stmt->execute();

                setcookie("token", $token, time() + (7 * 24 * 60 * 60), "/");
            }

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

header("Location: login.php");
exit;
?>