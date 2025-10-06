<?php
session_start();
require 'conexion.php';

if (!empty($_POST)) {

    $_SESSION['vidname'] = $_POST['vidname'];
    $_SESSION['descrip'] = $_POST['descrip'];
    $_SESSION['desarrollador'] = $_POST['desarrollador'];
    $_SESSION['categoria'] = $_POST['categoria'];
    $_SESSION['link'] = $_POST['link'];    

    if (empty($_POST['vidname'])) {
        $_SESSION['error'] = 'El Titulo no puede estar vacio';
        $_SESSION['vidname'] = '';
    }

    if (empty($_POST['descrip'])) {
        $_SESSION['error'] .= ', La descripción no puede estar vacio';
        $_SESSION['descrip'] = '';
    }

    if (empty($_POST['desarrollador'])) {
        $_SESSION['error'] .= ', El desarrollador no puede estar vacio';
        $_SESSION['desarrollador'] = '';
    }

    if (empty($_POST['categoria'])) {
        $_SESSION['error'] .= ', La categoria no puede estar vacia';
        $_SESSION['categoria'] = '';
    }

    if (empty($_POST['link'])) {
        $_SESSION['error'] .= ', El desarrollador es invalido';
        $_SESSION['link'] = '';
    }

    
    $carpeta = "uploads/";
    $ruta_archivo = "";

    if (!empty($_FILES['caratula']['name'])) {
        
        $nombre_tmp = $_FILES['caratula']['tmp_name'];
        $nombre_original = $_FILES['caratula']['name'];

        
        $tipo = mime_content_type($nombre_tmp);

        
        if (in_array($tipo, ['image/jpeg', 'image/png'])) {
            $nombre_archivo = time() . "_" . basename($nombre_original);
            $ruta_archivo = $carpeta . $nombre_archivo;
            move_uploaded_file($nombre_tmp, $ruta_archivo);
        } else {
            $_SESSION['error'] .= 'Formato de imagen no permitido (solo JPG o PNG). ';
            $_SESSION['caratula'] = '';
        }
    } else {
        
        $ruta_archivo = $carpeta . "default.jpg";
    }
    
    if (!empty($_SESSION['error'])) {
        header('Location: FormularioVid.php');
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO videojuegos (vidname, descrip, desarrollador, caratula, categoria, link, user_id)
    VALUES (:vidname, :descrip, :desarrollador, :caratula, :categoria, :link, :user_id)");
    $stmt->bindParam(':vidname', $_SESSION['vidname']);
    $stmt->bindParam(':descrip', $_SESSION['descrip']);
    $stmt->bindParam(':desarrollador', $_SESSION['desarrollador']);
    $stmt->bindParam(':caratula', $ruta_archivo);
    $stmt->bindParam(':categoria', $_SESSION['categoria']);
    $stmt->bindParam(':link', $_SESSION['link']);
    $stmt->bindParam(':user_id', $_SESSION['id']);
    $stmt->execute();

    echo "New records created successfully";

}
?>