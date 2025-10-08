<?php
session_start();

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
    header("Location: FormularioVid.php");
    exit;
}


$error = '';
if(isset($_SESSION['login_error'])){
    $error = $_SESSION['login_error'];
    $_SESSION['login_error'] = null;
}
?>

<html>
<body>
    <h2>Login</h2>
    <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
    <form action="checkLogin.php" method="POST">
        Email: <input type="text" name="email" value=""><br>
        Contraseña: <input type="password" name="userpass" value=""><br>
        <input type="submit" value="Login">
    </form>
    <p>¿No tienes cuenta? <a href="Formulario.php">Regístrate aquí</a></p>
</body>
</html>