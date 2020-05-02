<?php
include 'db.php';
session_start();

$conexion = conectar();

$id = $_POST['id'];
$nuevaEditorial = isset($_POST['nuevaEditorial']) ? $_POST['nuevaEditorial'] : '';

if (($nuevaEditorial == '') or (!preg_match('/^[A-Za-z0-9\s]+$/',$nuevaEditorial))) {
	$_SESSION['errores'] .= '<li>El nombre no debe contener simbolos.</li>';
}

if ($_SESSION['errores']){
	header('Location: altaEditorial.php');
	exit;
}

// consulta para saber si el nombre de editorial ya existe en la db
$sql = "SELECT id, nombre FROM editoriales WHERE nombre = '$nuevaEditorial'";
$resultado = $conexion->query($sql);
$usuario = $resultado->fetch_assoc();

if($usuario != null) {
	$_SESSION['errores'] .= '<li>La editorial ya se encuentra cargada.</li>';
	header('Location: altaEditorial.php'); 
} else {
	$sql = "INSERT INTO editoriales (nombre) VALUES('$nuevaEditorial')";
	
	try {
		// guardamos editorial
		$resultado = $conexion->query($sql);
		$_SESSION['exito'] = '<li>Se cargo con exito la nueva editorial.</li>';

		
		header('Location: altaEditorial.php');
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: altaEditorial.php');
	}
	
}

if(!isset($_SESSION['errores'])){
	header('Location: altaEditorial.php');
} else {
	header('Location: altaEditorial.php');
}
