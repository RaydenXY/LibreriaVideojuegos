<?php
session_start();
require 'conexion.php';

if (!empty($_POST)) {

    $_SESSION['vidname'] = $_POST['vidname'];
    $_SESSION['descrip'] = $_POST['descrip'];
    $_SESSION['desarrollador'] = $_POST['desarrollador'];
    $_SESSION['categoria'] = $_POST['categoria'];
    $_SESSION['link'] = $_POST['link'];
    $_SESSION['error'] = '';

    if (empty($_SESSION['vidname'])) {
        $_SESSION['error'] .= ' El título no puede estar vacío.';
    }

    if (strlen($_SESSION['descrip']) < 10) {
        $_SESSION['error'] .= ' La descripción debe tener al menos 10 caracteres.';
    }

    if (strlen($_SESSION['desarrollador']) < 3) {
        $_SESSION['error'] .= ' El nombre del desarrollador debe tener al menos 3 caracteres.';
    }

    if (empty($_SESSION['categoria'])) {
        $_SESSION['error'] .= ' Debes indicar una categoría.';
    }


    if (!filter_var($_SESSION['link'], FILTER_VALIDATE_URL)) {
        $_SESSION['error'] .= ' El enlace no tiene un formato válido.';
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

    $id_videojuego = $conn->lastInsertId();

    $stmt = $conn->prepare("INSERT INTO estadisticas (videojuego_id, visualizaciones, voto_positivo, voto_negativo)
                        VALUES (:videojuego_id, 0, 0, 0)");
    $stmt->bindParam(':videojuego_id', $id_videojuego);
    $stmt->execute();

    $_SESSION['error'] = "Videojuego añadido correctamente.";
    header("Location: ListaVid.php");
    exit;
    
}
?>