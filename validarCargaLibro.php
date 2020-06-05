<?php 
include 'db.php';
session_start();

$conexion = conectar();

$id = $_GET["id"];

if ($_SESSION['errores']){
	header('Location: verListadoLibros.php');
	exit;
}

$pdf = file_get_contents($_FILES['pdf']['tmp_name']);
$pdf = addslashes($pdf); 

$sql = " UPDATE libros SET pdf = '$pdf' WHERE id = '$id'";

try {
    $resultado = $conexion->query($sql);
    $_SESSION['exito'] = '<li>Cargaste un libro.</li>';
	header('Location: verListadoLibros.php');
} catch(Exception $e) {
	$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
	header('Location: verListadoLibros.php');
}
