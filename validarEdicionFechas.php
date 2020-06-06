<?php
include 'db.php';
session_start();

$conexion = conectar();

$id = $_GET['id'];
$fechaPublicacion = $_POST['nuevaFechaPublicacion'];
$fechaVencimiento = $_POST['nuevaFechaVencimiento'];


if($fechaPublicacion != ''){
	$sql = " UPDATE libros_pdf SET fecha_publicacion = '$fechaPublicacion' WHERE id = '$id'";
	try {
		$resultado = $conexion->query($sql);
		$_SESSION['exito'] = '<li>Actualizacion correcta.</li>';
		
		header("Location: verListadoLibros.php");
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: registrarse.php');
	}
}
if($fechaVencimiento != ''){
	$sql = " UPDATE libros_pdf SET fecha_vencimiento = '$fechaVencimiento' WHERE id = '$id'";
	try {
		$resultado = $conexion->query($sql);
		$_SESSION['exito'] = '<li>Actualizacion correcta.</li>';
		
		header("Location: verListadoLibros.php");
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: registrarse.php');
	}
}



