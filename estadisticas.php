<?php
session_start();
require "conexion.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];

$stmt = $conn->prepare("SELECT foto FROM myguests WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$myguests = $stmt->fetch(PDO::FETCH_ASSOC);

$fotoPerfil = !empty($myguests['foto']) ? $myguests['foto'] : 'defaultUser.jpg';

$stmt = $conn->prepare("SELECT v.vidname, e.visualizaciones, e.voto_positivo, e.voto_negativo
                        FROM videojuegos v
                        LEFT JOIN estadisticas e ON v.id = e.videojuego_id
                        WHERE v.user_id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$estadisticas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Estadísticas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <nav>
        <div class="nav-left">
            <a href="FormularioVid.php">Añadir nuevo videojuego</a>
            <a href="ListaVid.php">Volver a la lista de juegos</a>
        </div>
        <div class="nav-right">
            <a href="perfil.php">Volver al perfil</a>
            <a href="logout.php">Cerrar sesión</a>
            <img src="<?php echo $fotoPerfil; ?>" alt="Foto de perfil" class="perfil-img">
        </div>
    </nav>

    <div class="container">
        <h1>Mis estadísticas</h1>
        <?php if (empty($estadisticas)): ?>
            <p>No tienes videojuegos registrados aún.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Videojuego</th>
                    <th>Visualizaciones</th>
                    <th>Votos positivos</th>
                    <th>Votos negativos</th>
                </tr>
                <?php foreach ($estadisticas as $e): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($e['vidname']); ?></td>
                        <td><?php echo (int)$e['visualizaciones']; ?></td>
                        <td><?php echo (int)$e['voto_positivo']; ?></td>
                        <td><?php echo (int)$e['voto_negativo']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
