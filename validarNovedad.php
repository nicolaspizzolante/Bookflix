<?php 
include 'db.php';
session_start();

$conexion = conectar();

$titulo = trim($_POST["titulo"]);
$descripcion = trim($_POST["descripcion"]);

if ($_SESSION['errores']){
	header('Location: cargarNovedad.php');
	exit;
}

// consulta para saber si la novedad ya existe en la db
$sql = "SELECT id FROM novedades WHERE titulo = '$titulo'";
$resultado = $conexion->query($sql);
$novedad = $resultado->fetch_assoc();

if($novedad != null) {
	$_SESSION['errores'] .= '<li>La novedad ya está cargada.</li>';
	header('Location: cargarNovedad.php'); 
}else{

$file = file_get_contents($_FILES['file']['tmp_name']);
$file = addslashes($file); 

$fecha = date("Y-m-d H:i:s");
$sql = "INSERT INTO novedades (titulo, descripcion, fecha, foto_video) VALUES('$titulo','$descripcion','$fecha','$file')";

try {
    $resultado = $conexion->query($sql);
    $_SESSION['exito'] = '<li>Cargaste una novedad.</li>';
	header('Location: cargarNovedad.php');
} catch(Exception $e) {
	$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
	header('Location: cargarNovedad.php');
}
}