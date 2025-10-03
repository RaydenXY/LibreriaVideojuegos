<?php
session_start();
require "conexion.php";
$vidname = isset($_SESSION['vidname']) ? $_SESSION['vidname'] : ' ';
$descrip = isset($_SESSION['descrip']) ? $_SESSION['descrip'] : ' ';
$desarrollador = isset($_SESSION['desarrollador']) ? $_SESSION['desarrollador'] : ' ';
$caratula = isset($_SESSION['caratula']) ? $_SESSION['caratula'] : ' ';
$categoria = isset($_SESSION['categoria']) ? $_SESSION['categoria'] : ' ';
$link = isset($_SESSION['link']) ? $_SESSION['link'] : ' ';  

if(isset($_SESSION['error'])){
    echo "error" . $_SESSION['error'];
    $_SESSION['error'] = null;
}
?>

<html>
    <body>
        <h1>Formulario de videojuegos</h1>
        <form action ="insertData.php" method= "POST">
            Titulo: <input type= "text" name= "vidname" value="<?php echo $vidname?>">
            Descripción: <input type= "text" name= "descrip" value="<?php echo $descrip?>">
            Desarrollado por: <input type= "text" name= "desarrollador" value="<?php echo $desarrollador?>">
            caratula: <input type = "text" name= "caratula" value="<?php echo $caratula?>">
            categoría: <input type = "text" name= "categoria" value="<?php echo $categoria?>">
            link: <input type = "text" name= "link" value="<?php echo $link?>">
            <input type = "submit" value= "Registrar">
        </form>
    </body>
</html>