<?php
session_start();
require "conexion.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM videojuegos");
$stmt->execute();
$videojuegos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<body>
    <h1>Biblioteca de videojuegos</h1>
    <p><a href="FormularioVid.php">Añadir nuevo videojuego</a></p>

    <?php foreach ($videojuegos as $v): ?>
        <div style="margin-bottom:20px; border:1px solid #ccc; padding:10px; width:300px;">
            <img src="<?php echo $v['caratula']; ?>" width="150"><br>
            <strong><?php echo htmlspecialchars($v['vidname']); ?></strong><br>
            <em><?php echo htmlspecialchars($v['categoria']); ?></em><br>
            <a href="DetallesVid.php?id=<?php echo $v['id']; ?>">Ver detalles</a>
        </div>
    <?php endforeach; ?>
</body>
</html>
