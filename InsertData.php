<?php
session_start();
require 'conexion.php';

if(!empty($_POST)){

  $_SESSION['firstname'] = $_POST['firstname'];
  $_SESSION['lastname'] = $_POST['lastname'];
  $_SESSION['email'] = $_POST['email'];
  $_SESSION['userpass'] = $_POST['userpass'];
  $_SESSION['checkuserpass'] = $_POST['checkuserpass'];

  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {  
    $_SESSION['error'] = 'El email es invalido';
    $_SESSION['email']= '';
  }

  if($_POST['userpass'] !== $_POST['checkuserpass']){  
    $_SESSION['error'] .= ' Las contraseñas no coinciden';
    $_SESSION['userpass']= '';
    $_SESSION['checkuserpass']= '';
  }else{
    $hash= password_hash($_POST['userpass'], PASSWORD_DEFAULT);
  }

  if(empty($_POST['firstname'])){
    $_SESSION['error'] .= ' El nombre no puede estar vacio';
    $_SESSION['firstname'] = '';
  }

  if(empty($_POST['lastname'])){
    $_SESSION['error'] .= ' El apellido no puede estar vacio';
    $_SESSION['lastname'] = '';
  }

  if(isset($_SESSION['error']) && $_SESSION ['error'] != " "){
    header('location: Formulario.php');
    exit;
  }
  
  $stmt = $conn->prepare("INSERT INTO MyGuests (firstname, lastname, email, password)
    VALUES (:firstname, :lastname, :email, :password)");
    $stmt->bindParam(':firstname',  $_SESSION['firstname']);
    $stmt->bindParam(':lastname',  $_SESSION['lastname']);
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->bindParam(':password', $hash);
    $stmt->execute();
      
    echo "New records created successfully";

}
?>