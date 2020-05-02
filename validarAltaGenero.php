<?php
include 'db.php';
session_start();

$conexion = conectar();

$id = $_POST['id'];
$nuevoGenero = isset($_POST['nuevoGenero']) ? $_POST['nuevoGenero'] : '';

if (($nuevoGenero == '') or (!preg_match('/^[A-Za-z0-9\s]+$/',$nuevoGenero))) {
	$_SESSION['errores'] .= '<li>El nombre no puede ser vacio o contener caracteres especiales.</li>';
}

if ($_SESSION['errores']){
	header('Location: altaGenero.php');
	exit;
}

// consulta para saber si el nombre de genero ya existe en la db
$sql = "SELECT id, nombre FROM generos WHERE nombre = '$nuevoGenero'";
$resultado = $conexion->query($sql);
$usuario = $resultado->fetch_assoc();

if($usuario != null) {
	$_SESSION['errores'] .= '<li>El genero ya se encuentra cargado.</li>';
	header('Location: altaGenero.php'); 
} else {
	$sql = "INSERT INTO generos (nombre) VALUES('$nuevoGenero')";
	
	try {
		// guardamos genero
		$resultado = $conexion->query($sql);
		$_SESSION['exito'] = '<li>Se cargo con exito el nuevo genero.</li>';

		
		header('Location: altaGenero.php');
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: altaGenero.php');
	}
	
}

if(!isset($_SESSION['errores'])){
	header('Location: altaGenero.php');
} else {
	header('Location: altaGenero.php');
}
