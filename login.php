<?php
session_start();
require "conexion.php";

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: ListaVid.php");
    exit;
}

if (isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];

    $stmt = $conn->prepare("SELECT t.user_id, m.firstname, m.lastname, m.email
                            FROM tokens t
                            JOIN MyGuests m ON t.user_id = m.id
                            WHERE t.token = :token AND t.expiracion > NOW()");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($datos) {
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $datos['user_id'];
        $_SESSION['firstname'] = $datos['firstname'];
        $_SESSION['lastname'] = $datos['lastname'];
        $_SESSION['email'] = $datos['email'];

        header("Location: ListaVid.php");
        exit;
    }
}


$error = '';
if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    $_SESSION['login_error'] = null;
}

if (isset($_SESSION['error'])) {
    echo "error" . $_SESSION['error'];
    $_SESSION['error'] = null;
}

?>

<html>

<head>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <h2>Login</h2>
    <?php if ($error)
        echo "<p style='color:red;'>$error</p>"; ?>
    <form action="checkLogin.php" method="POST">
        Email: <input type="text" name="email" value=""><br>
        Contraseña: <input type="password" name="userpass" value=""><br>
        <label>
            <input type="checkbox" name="recordar" value="1"> Recordar sesión
        </label>
        <input type="submit" value="Login">
    </form>
    <p>¿No tienes cuenta? <a href="Formulario.php">Regístrate aquí</a></p>
</body>

</html>