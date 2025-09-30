<?php
session_start();

// Solo accesible si estamos logeados
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("Location: login.php");
    exit;
}

// Mostrar nombre del usuario
$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];
?>

<html>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($firstname . " " . $lastname); ?>!</h1>
    <p>Esta es tu página protegida.</p>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>