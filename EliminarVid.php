<?php
session_start();
require "conexion.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['id'])) {
    $_SESSION['error'] = "No se especificó ningún videojuego para eliminar.";
    header("Location: ListaVid.php");
    exit;
}

$id = (int) $_POST['id'];

// Verificar propiedad
$stmt = $conn->prepare("SELECT user_id, caratula FROM videojuegos WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$videojuego = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$videojuego) {
    $_SESSION['error'] = "El videojuego no existe o ya fue eliminado.";
    header("Location: ListaVid.php");
    exit;
}

if ($videojuego['user_id'] != $_SESSION['id']) {
    $_SESSION['error'] = "No tienes permiso para eliminar este videojuego.";
    header("Location: ListaVid.php");
    exit;
}

// --- ELIMINAR ARCHIVO DE IMAGEN (si no es default.jpg) ---
if ($videojuego['caratula'] !== "uploads/default.jpg" && file_exists($videojuego['caratula'])) {
    unlink($videojuego['caratula']); // elimina el archivo del disco
}

// --- ELIMINAR REGISTRO ---
$stmt = $conn->prepare("DELETE FROM videojuegos WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

$_SESSION['error'] = "Videojuego y su imagen fueron eliminados correctamente.";
header("Location: ListaVid.php");
exit;
?>
