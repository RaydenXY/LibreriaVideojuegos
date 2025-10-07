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

$stmt = $conn->prepare("SELECT user_id FROM videojuegos WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$videojuego = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$videojuego) {
    $_SESSION['error'] = "El videojuego no existe.";
    header("Location: ListaVid.php");
    exit;
}

if ($videojuego['user_id'] != $_SESSION['id']) {
    $_SESSION['error'] = "No tienes permiso para eliminar este videojuego.";
    header("Location: ListaVid.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM videojuegos WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

$_SESSION['success'] = "Videojuego eliminado correctamente.";
header("Location: ListaVid.php");
exit;
?>
