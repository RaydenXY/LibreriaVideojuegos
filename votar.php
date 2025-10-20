<?php
session_start();
require "conexion.php";

if (!isset($_SESSION['id'])) {
    exit("Debes iniciar sesión para votar.");
}

$id_usuario = $_SESSION['id'];


$id_videojuego = $_POST['id'];
$tipo = $_POST['tipo'];


$stmt = $conn->prepare("SELECT * FROM votos WHERE user_id = :user_id AND videojuego_id = :videojuego_id");
$stmt->bindParam(':user_id', $id_usuario);
$stmt->bindParam(':videojuego_id', $id_videojuego);
$stmt->execute();
$voto_existente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($voto_existente) {
    echo "Ya has votado este videojuego.";
    exit();
}


$stmt = $conn->prepare("INSERT INTO votos (user_id, videojuego_id, tipo) VALUES (:user_id, :videojuego_id, :tipo)");
$stmt->bindParam(':user_id', $id_usuario);
$stmt->bindParam(':videojuego_id', $id_videojuego);
$stmt->bindParam(':tipo', $tipo);
$stmt->execute();


if ($tipo == "positivo") {
    $stmt = $conn->prepare("UPDATE estadisticas SET voto_positivo = voto_positivo + 1 WHERE videojuego_id = :videojuego_id");
} else {
    $stmt = $conn->prepare("UPDATE estadisticas SET voto_negativo = voto_negativo + 1 WHERE videojuego_id = :videojuego_id");
}
$stmt->bindParam(':videojuego_id', $id_videojuego);
$stmt->execute();

$stmt = $conn->prepare("SELECT voto_positivo, voto_negativo FROM estadisticas WHERE videojuego_id = :videojuego_id");
$stmt->bindParam(':videojuego_id', $id_videojuego);
$stmt->execute();
$datos = $stmt->fetch(PDO::FETCH_ASSOC);

echo "👍 " . $datos['voto_positivo'] . " | 👎 " . $datos['voto_negativo'];
?>
