<?php
session_start();
require "conexion.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No se especificó ningún videojuego.";
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

if ($videojuego['user_id'] != $_SESSION['id']) {
    $_SESSION['error'] = "No tienes permiso para editar este videojuego.";
    header("Location: ListaVid.php");
    exit;
}

// --- PROCESAR FORMULARIO ---
if (!empty($_POST)) {
    $vidname = $_POST['vidname'];
    $descrip = $_POST['descrip'];
    $desarrollador = $_POST['desarrollador'];
    $categoria = $_POST['categoria'];
    $link = $_POST['link'];

    $_SESSION['error'] = '';
    if (empty($vidname)) $_SESSION['error'] .= " El título no puede estar vacío.";
    if (empty($descrip)) $_SESSION['error'] .= " La descripción no puede estar vacía.";
    if (empty($desarrollador)) $_SESSION['error'] .= " El desarrollador no puede estar vacío.";
    if (empty($categoria)) $_SESSION['error'] .= " La categoría no puede estar vacía.";
    if (empty($link)) $_SESSION['error'] .= " El enlace no puede estar vacío.";

    $carpeta = "uploads/";
    $ruta_nueva = $videojuego['caratula']; // por defecto, la actual

    // --- SI SUBE NUEVA IMAGEN ---
    if (!empty($_FILES['caratula']['name'])) {
        $nombre_tmp = $_FILES['caratula']['tmp_name'];
        $nombre_original = $_FILES['caratula']['name'];
        $tipo = mime_content_type($nombre_tmp);

        if (in_array($tipo, ['image/jpeg', 'image/png'])) {
            // Eliminar la antigua si no es la default
            if ($videojuego['caratula'] !== "uploads/default.jpg" && file_exists($videojuego['caratula'])) {
                unlink($videojuego['caratula']);
            }

            // Guardar la nueva
            $nombre_archivo = time() . "_" . basename($nombre_original);
            $ruta_nueva = $carpeta . $nombre_archivo;
            move_uploaded_file($nombre_tmp, $ruta_nueva);
        } else {
            $_SESSION['error'] .= " Formato de imagen no permitido (solo JPG o PNG).";
        }
    }

    if (!empty($_SESSION['error'])) {
        header("Location: EditarVid.php?id=$id");
        exit;
    }

    $stmt = $conn->prepare("UPDATE videojuegos 
        SET vidname=:vidname, descrip=:descrip, desarrollador=:desarrollador, categoria=:categoria, link=:link, caratula=:caratula
        WHERE id=:id");
    $stmt->bindParam(':vidname', $vidname);
    $stmt->bindParam(':descrip', $descrip);
    $stmt->bindParam(':desarrollador', $desarrollador);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':link', $link);
    $stmt->bindParam(':caratula', $ruta_nueva);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $_SESSION['error'] = "Videojuego actualizado correctamente.";
    header("Location: DetallesVid.php?id=$id");
    exit;
}
?>

<html>
    <head>
        <link rel="stylesheet" href="css/styles.css">
    </head>
<body>
    <?php
    if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
        echo "<p style='color:red;'>" . $_SESSION['error'] . "</p>";
        $_SESSION['error'] = null;
    }
    ?>

    <h1>Editar videojuego</h1>

    <form method="POST" enctype="multipart/form-data">
        Título: <input type="text" name="vidname" value="<?php echo htmlspecialchars($videojuego['vidname']); ?>"><br><br>
        Descripción: <input type="text" name="descrip" value="<?php echo htmlspecialchars($videojuego['descrip']); ?>"><br><br>
        Desarrollador: <input type="text" name="desarrollador" value="<?php echo htmlspecialchars($videojuego['desarrollador']); ?>"><br><br>
        Categoría: <input type="text" name="categoria" value="<?php echo htmlspecialchars($videojuego['categoria']); ?>"><br><br>
        Link: <input type="text" name="link" value="<?php echo htmlspecialchars($videojuego['link']); ?>"><br><br>
        Carátula actual:<br>
        <img src="<?php echo $videojuego['caratula']; ?>" width="150"><br><br>
        Cambiar carátula: <input type="file" name="caratula"><br><br>
        <input type="submit" value="Guardar cambios">
    </form>

    <p><a href="DetallesVid.php?id=<?php echo $videojuego['id']; ?>">⬅ Cancelar</a></p>
</body>
</html>