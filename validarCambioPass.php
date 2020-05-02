<?php 
include 'db.php';
include 'validarPassword.php';
session_start();

$conexion = conectar();

$id = $_POST['id'];
$nueva_pass = $_POST['nueva_pass'];
$repetir = $_POST['repetir_pass'];

$errores = '';

if(!validar_password($nueva_pass,$errores)){ //si la contraseña nueva no cumple condiciones
	$_SESSION['errores'] = $errores;
	header('Location: cambiarcontrasenia.php');
	exit;
}

if ($nueva_pass != $repetir) {
	$_SESSION['errores'] = "<li>Repita bien la contraseña.</li>";
} else {
	$sql = "UPDATE usuarios SET contrasenia = '$nueva_pass' WHERE id = '$id'";
	$resultado = $conexion->query($sql);
	$_SESSION['exito'] = "<li>Cambio de contraseña con exito.</li>";
}

header('Location: cambiarcontrasenia.php');