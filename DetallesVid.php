<?php
session_start();
require "conexion.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No se especificó ningún ID.";
    header("Location: ListaVid.php");
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM videojuegos WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$videojuego = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$videojuego) {
    $_SESSION['error'] = "El videojuego no existe o fue eliminado.";
    header("Location: ListaVid.php");
    exit;
}
?>
<html>
    <head>
        <link rel="stylesheet" href="css/styles.css">
    </head>
<body>
    <?php
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red;'>Error: " . $_SESSION['error'] . "</p>";
        $_SESSION['error'] = null;
    }
    ?>
    <h1><?php echo htmlspecialchars($videojuego['vidname']); ?></h1>

    <img src="<?php echo $videojuego['caratula']; ?>" width="200"><br><br>
    <strong>Descripción:</strong> <?php echo htmlspecialchars($videojuego['descrip']); ?><br><br>
    <strong>Desarrollador:</strong> <?php echo htmlspecialchars($videojuego['desarrollador']); ?><br><br>
    <strong>Categoría:</strong> <?php echo htmlspecialchars($videojuego['categoria']); ?><br><br>
    <strong>Link:</strong> <a href="<?php echo htmlspecialchars($videojuego['link']); ?>" target="_blank">Visitar</a><br><br>

    <?php if ($videojuego['user_id'] == $_SESSION['id']): ?>
        <form action="EliminarVid.php" method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?php echo $videojuego['id']; ?>">
            <input type="submit" value="Eliminar">
        </form>

        <form action="EditarVid.php" method="GET" style="display:inline;">
            <input type="hidden" name="id" value="<?php echo $videojuego['id']; ?>">
            <input type="submit" value="Editar">
        </form>
        <p><a href="perfil.php">Ir a tu perfil</a></p>
    <?php endif; ?>

    <p><a href="ListaVid.php">⬅ Volver a la lista</a></p>
</body>
</html>
