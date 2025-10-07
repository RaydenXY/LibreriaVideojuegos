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

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM videojuegos WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$videojuego = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$videojuego) {
    $_SESSION['error'] = "El videojuego no existe o fue eliminado.";
    header("Location: ListaVid.php");
    exit;
}

if ($videojuego['user_id'] != $_SESSION['id']) {
    $_SESSION['error'] = "No tienes permiso para editar este videojuego.";
    header("Location: ListaVid.php");
    exit;
}

// Si se envió el formulario
if (!empty($_POST)) {
    $vidname = $_POST['vidname'];
    $descrip = $_POST['descrip'];
    $desarrollador = $_POST['desarrollador'];
    $categoria = $_POST['categoria'];
    $link = $_POST['link'];

    $stmt = $conn->prepare("UPDATE videojuegos 
        SET vidname=:vidname, descrip=:descrip, desarrollador=:desarrollador, categoria=:categoria, link=:link 
        WHERE id=:id");

    $stmt->bindParam(':vidname', $vidname);
    $stmt->bindParam(':descrip', $descrip);
    $stmt->bindParam(':desarrollador', $desarrollador);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':link', $link);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $_SESSION['success'] = "Videojuego actualizado correctamente.";
    header("Location: DetallesVid.php?id=" . $id);
    exit;
}
?>

<html>
<body>
    <?php
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red;'>Error: " . $_SESSION['error'] . "</p>";
        $_SESSION['error'] = null;
    }
    ?>
    <h1>Editar videojuego</h1>

    <form method="POST">
        Título: <input type="text" name="vidname" value="<?php echo htmlspecialchars($videojuego['vidname']); ?>"><br><br>
        Descripción: <input type="text" name="descrip" value="<?php echo htmlspecialchars($videojuego['descrip']); ?>"><br><br>
        Desarrollador: <input type="text" name="desarrollador" value="<?php echo htmlspecialchars($videojuego['desarrollador']); ?>"><br><br>
        Categoría: <input type="text" name="categoria" value="<?php echo htmlspecialchars($videojuego['categoria']); ?>"><br><br>
        Link: <input type="text" name="link" value="<?php echo htmlspecialchars($videojuego['link']); ?>"><br><br>
        <input type="submit" value="Guardar cambios">
    </form>

    <p><a href="DetallesVid.php?id=<?php echo $videojuego['id']; ?>">⬅ Cancelar</a></p>
</body>
</html>

