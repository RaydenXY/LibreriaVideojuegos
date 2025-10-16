<?php
session_start();
require 'conexion.php';

if (!empty($_POST)) {

  $_SESSION['firstname'] = $_POST['firstname'];
  $_SESSION['lastname'] = $_POST['lastname'];
  $_SESSION['email'] = $_POST['email'];
  $_SESSION['userpass'] = $_POST['userpass'];
  $_SESSION['checkuserpass'] = $_POST['checkuserpass'];

  $email = $_SESSION['email'];

  $stmt = $conn->prepare("SELECT email FROM myguests WHERE email = :email");
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  $guest = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'El email es invalido';
    $_SESSION['email'] = '';
  } else if ($guest) {
    $_SESSION['error'] = 'El email ya está registrado';
    $_SESSION['email'] = '';
  }

  if ($_POST['userpass'] !== $_POST['checkuserpass']) {
    $_SESSION['error'] .= ' Las contraseñas no coinciden';
    $_SESSION['userpass'] = '';
    $_SESSION['checkuserpass'] = '';
  } else if (!preg_match("/^.{8,}$/", $_SESSION['userpass'])) {
    $_SESSION['error'] .= ' La contraseña debe tener 8 caracteres como mínimo';
    $_SESSION['userpass'] = '';
    $_SESSION['checkuserpass'] = '';
  } else if (!preg_match("/^(?=.*[a-z])$/", $_SESSION['userpass'])) {
    $_SESSION['error'] .= ' La contraseña debe tener al menos una letra minúscula';
    $_SESSION['userpass'] = '';
  } else if (!preg_match("/^(?=.*[A-Z])$/", $_SESSION['userpass'])) {
    $_SESSION['error'] .= ' La contraseña debe tener al menos una letra mayúscula';
    $_SESSION['userpass'] = '';
  } else {
    $hash = password_hash($_POST['userpass'], PASSWORD_DEFAULT);
  }

  if (empty($_POST['firstname'])) {
    $_SESSION['error'] .= ' El nombre no puede estar vacio';
    $_SESSION['firstname'] = '';
  }

  if (empty($_POST['lastname'])) {
    $_SESSION['error'] .= ' El apellido no puede estar vacio';
    $_SESSION['lastname'] = '';
  }

  if (!empty($_SESSION['error'])) {
    header('location: Formulario.php');
    exit;
  }

  $stmt = $conn->prepare("INSERT INTO MyGuests (firstname, lastname, email, password)
    VALUES (:firstname, :lastname, :email, :password)");
  $stmt->bindParam(':firstname', $_SESSION['firstname']);
  $stmt->bindParam(':lastname', $_SESSION['lastname']);
  $stmt->bindParam(':email', $_SESSION['email']);
  $stmt->bindParam(':password', $hash);
  $stmt->execute();

  $_SESSION['error'] = "Usuario registrado correctamente. Inicia sesión.";
  header('Location: login.php');
  exit;

}
?>