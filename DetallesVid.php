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

$stmt = $conn->prepare("UPDATE estadisticas SET visualizaciones = visualizaciones + 1 WHERE videojuego_id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

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
    <strong>Link:</strong> <a href="<?php echo htmlspecialchars($videojuego['link']); ?>"
        target="_blank">Visitar</a><br><br>

    <div id="votos">
        <button onclick="votar(<?php echo $videojuego['id']; ?>, 'positivo')">👍 Me gusta</button>
        <button onclick="votar(<?php echo $videojuego['id']; ?>, 'negativo')">👎 No me gusta</button>
        <p id="resultadoVoto"></p>
    </div>

    <script>
        function votar(id, tipo) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "votar.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("resultadoVoto").innerHTML = this.responseText;
                }
            };
            xhr.send("id=" + id + "&tipo=" + tipo);
        }
    </script>

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