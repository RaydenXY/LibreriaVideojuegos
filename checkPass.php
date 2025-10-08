<?php
$q = $_GET['q'];
$response = "";

// Reglas
$length = strlen($q) >= 8;
$upper = preg_match('/[A-Z]/', $q);
$lower = preg_match('/[a-z]/', $q);
$number = preg_match('/[0-9]/', $q);

// Generamos sugerencias con color
$response .= $length ? "<p style='color:green'>Al menos 8 caracteres</p>" : "<p style='color:red'>Al menos 8 caracteres</p>";
$response .= $upper ? "<p style='color:green'>Una letra mayúscula</p>" : "<p style='color:red'>Una letra mayúscula</p>";
$response .= $lower ? "<p style='color:green'>Una letra minúscula</p>" : "<p style='color:red'>Una letra minúscula</p>";
$response .= $number ? "<p style='color:green'>Un número</p>" : "<p style='color:red'>Un número</p>";

// Resultado final
if ($length && $upper && $lower && $number) {
  $response .= "<p style='color:green'><strong>Contraseña segura </strong></p>";
} else {
  $response .= "<p style='color:red'><strong>Contraseña débil </strong></p>";
}

echo $response;
?>
