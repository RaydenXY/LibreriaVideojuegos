<?php
session_start();
require 'conexion.php';

if (!empty($_POST)) {

    $_SESSION['vidname'] = $_POST['vidname'];
    $_SESSION['descrip'] = $_POST['descrip'];
    $_SESSION['desarrollador'] = $_POST['desarrollador'];
    $_SESSION['caratula'] = $_POST['caratula'];
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
        $_SESSION['error'] .= ', El desarrollador es invalido';
        $_SESSION['desarrollador'] = '';
    }

    if (empty($_POST['caratula'])) {
        $_SESSION['error'] .= ', El desarrollador es invalido';
        $_SESSION['caratula'] = '';
    }

    if (empty($_POST['categoria'])) {
        $_SESSION['error'] .= ', El desarrollador es invalido';
        $_SESSION['categoria'] = '';
    }

    if (empty($_POST['link'])) {
        $_SESSION['error'] .= ', El desarrollador es invalido';
        $_SESSION['link'] = '';
    }

    if (isset($_SESSION['error']) && $_SESSION['error'] != " ") {
        header('location: Formulario.php');
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO MyGuests (vidname, descrip, desarrollador, caratula, categoria, link)
    VALUES (:vidname, :descrip, :desarrollador, :caratula, :categoria, :link)");
    $stmt->bindParam(':vidname', $_SESSION['vidname']);
    $stmt->bindParam(':descrip', $_SESSION['descrip']);
    $stmt->bindParam(':desarrollador', $_SESSION['desarrollador']);
    $stmt->bindParam(':caratula', $_SESSION['vidname']);
    $stmt->bindParam(':categoria', $_SESSION['vidname']);
    $stmt->bindParam(':link', $_SESSION['vidname']);
    $stmt->execute();

    echo "New records created successfully";

}
?>