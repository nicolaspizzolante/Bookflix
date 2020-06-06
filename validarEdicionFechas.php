<?php
include 'db.php';
session_start();

$conexion = conectar();

$id = $_GET['id'];
$fechaPublicacion = $_POST['nuevaFechaPublicacion'];
$fechaVencimiento = $_POST['nuevaFechaVencimiento'];

$bol = FALSE;
if($fechaPublicacion != ''){
	$bol = TRUE;
	$sql = " UPDATE libros_pdf SET fecha_publicacion = '$fechaPublicacion' WHERE id = '$id'";
}
if($fechaVencimiento != ''){
	$bol = TRUE;
	$sql = " UPDATE libros_pdf SET fecha_vencimiento = '$fechaVencimiento' WHERE id = '$id'";
}

if($bol){
	try {
		$resultado = $conexion->query($sql);
		$_SESSION['exito'] = '<li>Actualizacion correcta.</li>';
		
		
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: registrarse.php');
	}
}
header("Location: verListadoLibros.php");



