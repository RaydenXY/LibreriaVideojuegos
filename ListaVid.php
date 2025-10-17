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

$id = $_SESSION['id'];

/*$stmt = $conn->prepare("SELECT foto FROM myguests WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$myguests = $stmt->fetch(PDO::FETCH_ASSOC);*/

?>
<html>
<head>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav>
        <a href="FormularioVid.php">Añadir nuevo videojuego</a>
        <a href="logout.php">Cerrar sesión</a>
        
    </nav>
    <h1>Bienvenido, <?php echo $_SESSION['firstname']; ?>!</h1>
    <p></p>
    <hr>
    <h1>Biblioteca de videojuegos</h1>
    <?php
    if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
        echo "<p style='color:red;'>" . $_SESSION['error'] . "</p>";
        $_SESSION['error'] = null;
    }
    ?>

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