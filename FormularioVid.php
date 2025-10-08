<?php

session_start();
require "conexion.php";
$vidname = isset($_SESSION['vidname']) ? $_SESSION['vidname'] : '';
$descrip = isset($_SESSION['descrip']) ? $_SESSION['descrip'] : '';
$desarrollador = isset($_SESSION['desarrollador']) ? $_SESSION['desarrollador'] : '';
$categoria = isset($_SESSION['categoria']) ? $_SESSION['categoria'] : '';
$link = isset($_SESSION['link']) ? $_SESSION['link'] : '';  

if(isset($_SESSION['error'])){
    echo "error" . $_SESSION['error'];
    $_SESSION['error'] = null;
}

?>

<html>
    <body>
        <h1>Formulario de videojuegos</h1>
        <form action ="InsertVid.php" method="POST" enctype="multipart/form-data">
            Titulo: <input type= "text" name= "vidname" value="<?php echo $vidname?>"><br><br>
            Descripción: <input type= "text" name= "descrip" value="<?php echo $descrip?>"><br><br>
            Desarrollado por: <input type= "text" name= "desarrollador" value="<?php echo $desarrollador?>"><br><br>
            caratula: <input type = "file" name= "caratula"><br><br>
            categoría: <input type = "text" name= "categoria" value="<?php echo $categoria?>"><br><br>
            link: <input type = "text" name= "link" value="<?php echo $link?>"><br><br>
            <input type = "submit" value= "Registrar">
            <br><br>
            <a href="ListaVid.php">⬅ Volver a la lista de videojuegos</a>
        </form>
    </body>
</html>