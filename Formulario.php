<?php
session_start();
require "conexion.php";
$firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : ' ';
$lastname = isset($_SESSION['lastname']) ? $_SESSION['lastname'] : ' ';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : ' ';
$firstpass = isset($_SESSION['userpass']) ? $_SESSION['userpass'] : ' ';
$secondpass = isset($_SESSION['checkuserpass']) ? $_SESSION['checkuserpass'] : ' ';

if (isset($_SESSION['error'])) {
    echo "error" . $_SESSION['error'];
    $_SESSION['error'] = null;
}
?>

<html>

<body>
    <form action="insertData.php" method="POST">
        Nombre: <input type="text" name="firstname" value="<?php echo $firstname ?>">
        Apellido: <input type="text" name="lastname" value="<?php echo $lastname ?>">
        Email: <input type="text" name="email" value="<?php echo $email ?>">
        Contraseña: <input type="text" name="userpass" value="<?php echo $firstpass ?>">
        RepContraseña: <input type="text" name="checkuserpass" value="<?php echo $secondpass ?>">
        <input type="submit" value="Registrar">
    </form>

    <br>
    <hr>
    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</body>

</html>