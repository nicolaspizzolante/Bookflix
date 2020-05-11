<?php
include 'db.php';
include 'validarPassword.php';
session_start();

$conexion = conectar();
$autor= trim($_POST["autor"]);

if (($autor == '') or (!preg_match('/^[A-Za-z\s]+$/',$autor))) {
	$_SESSION['errores'] .= '<li>Ingrese un nombre valido.</li>';
}
if ($_SESSION['errores']){
	header('Location: altaAutor.php');
	exit;
}

// consulta para saber si el nombre de editorial ya existe en la db
$sql = "SELECT id, nombre FROM autores WHERE nombre = '$autor'";
$resultado = $conexion->query($sql);
$usuario = $resultado->fetch_assoc();

if($usuario != null) {
	$_SESSION['errores'] .= '<li>El autor ya se encuentra cargado.</li>';
	header('Location: altaEditorial.php'); 
} else {
	$sql = "INSERT INTO autores (nombre) VALUES('$autor')";
	
	try {
		// guardamos autor
		$resultado = $conexion->query($sql);
		$_SESSION['exito'] = '<li>Se cargo con exito el nuevo autor.</li>';

		
		header('Location: cargarMetadatos.php');
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: altaAutor.php');
	}
	
}

if(!isset($_SESSION['errores'])){
	header('Location: cargarMetadatos.php');
} else {
	header('Location: altaAutor.php');
}