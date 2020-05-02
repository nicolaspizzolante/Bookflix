<?php 
include 'autenticador.php';
		
$email = $_POST["email"];
$contrasenia = $_POST["contrasenia"];

$autenticador = new autenticador();

try {
	$autenticador->loginUser($email, $contrasenia);
} catch (Exception $e){
	$_SESSION['errores'] = '<li>Datos incorrectos.</li>';
	header('Location: login.php');
}

header('Location: index.php');
