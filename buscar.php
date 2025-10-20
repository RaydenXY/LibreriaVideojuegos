<?php
require "conexion.php";

$q = $_GET['q'] ?? '';

if ($q === '') {
    exit(''); // si no hay texto, no mostramos nada
}

$stmt = $conn->prepare("SELECT id, vidname, categoria, caratula FROM videojuegos WHERE vidname LIKE :texto ORDER BY vidname ASC");
$busqueda = '%' . $q . '%';
$stmt->bindParam(':texto', $busqueda);
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($resultados)) {
    echo "<p>No se encontraron resultados.</p>";
} else {
    foreach ($resultados as $v) {
        echo "<div class='videojuego'>";
        echo "<img src='" . htmlspecialchars($v['caratula']) . "' width='150'><br>";
        echo "<strong>" . htmlspecialchars($v['vidname']) . "</strong><br>";
        echo "<em>" . htmlspecialchars($v['categoria']) . "</em><br>";
        echo "<a href='DetallesVid.php?id=" . $v['id'] . "'>Ver detalles</a>";
        echo "</div>";
    }
}
?>
