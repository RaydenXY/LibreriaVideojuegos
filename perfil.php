<?php
session_start();
require "conexion.php";

$id = $_SESSION['id'];

$stmt = $conn->prepare('SELECT * FROM videojuegos where user_id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();
$videojuegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT foto FROM myguests WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$myguests = $stmt->fetch(PDO::FETCH_ASSOC);

$fotoPerfil = !empty($myguests['foto']) ? $myguests['foto'] : 'defaultUser.jpg';


?>
<html>

<head>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <nav>
        <div class="nav-left">
            <a href="FormularioVid.php">Añadir nuevo videojuego</a>
        </div>
        <div class="nav-right">
            <a href="perfil.php">Editar perfil</a>
            <a href="logout.php">Cerrar sesión</a>
            <img src="<?php echo $fotoPerfil; ?>" alt="Foto de perfil" class="perfil-img">
        </div>
    </nav>
    <h1>Bienvenido a tu perfil, <?php echo $_SESSION['firstname']; ?>!</h1>
    <p></p>
    <hr>
    <h1>Lista de tus videojuegos</h1>

    <?php foreach ($videojuegos as $v): ?>        
        <div style="margin-bottom:20px; border:1px solid #ccc; padding:10px; width:300px;">
            <img src="<?php if($v['user_id'] == $id){echo $v['caratula'];} ?>" width="150"><br>
            <strong><?php echo htmlspecialchars($v['vidname']); ?></strong><br>
            <em><?php echo htmlspecialchars($v['categoria']); ?></em><br>
            <a href="DetallesVid.php?id=<?php echo $v['id']; ?>">Ver detalles</a>
        </div>
        
    <?php endforeach; ?>
</body>

</html>