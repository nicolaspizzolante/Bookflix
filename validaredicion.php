<?php
include 'db.php';
session_start();

$conexion = conectar();

$id = $_POST['id'];
$email = isset($_POST['email']) ? $_POST['email'] : '';
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$apellido = isset($_POST['apellido']) ? $_POST['apellido'] : '';

$sql = "SELECT id FROM usuarios WHERE email = '$email' AND id <> '$id'";
$resultado = $conexion->query($sql);
$user = $resultado->fetch_assoc();

if (($email == '') or (!preg_match('[@]',$email))) {
	$_SESSION['errores'] .= '<li>Ingrese una direccion de email valida.</li>';
}else{
	if($user != null){
		$_SESSION['errores'] .= '<li>El email ya se encuentra registrado.</li>';
	}else{
		$sql = "UPDATE usuarios SET email = '$email' WHERE id = '$id'";
		$resultado = $conexion->query($sql);
		$_SESSION['usuario']['email'] = $email;
	}
}


if (($nombre == '') or (!preg_match('/^[A-Za-z\s]+$/',$nombre))) {
	$_SESSION['errores'] .= '<li>Ingrese un nombre valido.</li>';
} else {
	$sql = "UPDATE usuarios SET nombre = '$nombre' WHERE id = '$id'";
	$resultado = $conexion->query($sql);
	$_SESSION['usuario']['nombre'] = $nombre;
}

if (($apellido == '') or (!preg_match('/^[A-Za-z\s]+$/',$apellido))) {
	$_SESSION['errores'] .= '<li>Ingrese un apellido valido.</li>';
} else {
	$sql = "UPDATE usuarios SET apellido = '$apellido' WHERE id = '$id'";
	$resultado = $conexion->query($sql);
	$_SESSION['usuario']['apellido'] = $apellido;
}

if(!isset($_SESSION['errores'])){
	header('Location: muro.php');
} else {
	header('Location: editar.php');
}
