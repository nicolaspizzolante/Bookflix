<?php 
include 'db.php';
session_start();

$conexion = conectar();

$titulo = trim($_POST["titulo"]);
$descripcion = trim($_POST["descripcion"]);
$id_libro = $_POST['id_libro'];

if ($_SESSION['errores']){
	header('Location: cargarTrailer.php');
	exit;
}

// consulta para saber si el trailer ya existe en la db
$sql = "SELECT id FROM trailers WHERE titulo = '$titulo'";
$resultado = $conexion->query($sql);
$trailer = $resultado->fetch_assoc();

if($trailer != null) {
	$_SESSION['errores'] .= '<li>El trailer ya está cargado.</li>';
	header('Location: cargarTrailer.php'); 
}else{
  
$file = file_get_contents($_FILES['file']['tmp_name']);
$file = addslashes($file); 

$fecha = date("Y-m-d H:i:s");
$sql = "INSERT INTO trailers (titulo, descripcion, fecha, foto_video, id_libro) VALUES('$titulo','$descripcion','$fecha','$file', '$id_libro')";

try {
    $resultado = $conexion->query($sql);
    $_SESSION['exito'] = '<li>Cargaste una trailer.</li>';
	header('Location: cargarTrailer.php');
} catch(Exception $e) {
	$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
	header('Location: cargarTrailer.php');
}
}