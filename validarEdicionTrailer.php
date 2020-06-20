<?php 
include 'db.php';
include 'validarPassword.php';
session_start();

$conexion = conectar();

$id = $_GET['id'];
$titulo = trim($_POST["titulo"]);
$descripcion = trim($_POST["descripcion"]);

$sql = "SELECT id_libro FROM trailers WHERE id= '$id'";
    $aux = $conexion->query($sql)->fetch_assoc();
   
    $id_libro= $aux['id_libro'];

if ($_SESSION['errores']){
	header('Location: verListadoLibros.php');
	exit;
}

//consulta para saber el trailer ya existe en la db
$sql = "SELECT id FROM trailers WHERE titulo = '$titulo' AND id <> '$id'";
$resultado = $conexion->query($sql);
$novedad = $resultado->fetch_assoc();

if($novedad!=null){
	$_SESSION['errores'] .= '<li>El trailer ya está cargado.</li>';
	$v = 1;
	header("Location: modificarTrailer.php?id=$id&titulo_trailer=$titulo&descripcionTrailer=$descripcion&verificar=$v"); 
}else{
    

	$sql = " UPDATE trailers SET titulo = '$titulo' WHERE id = '$id'";
$resultado = $conexion->query($sql);

$sql = "UPDATE trailers SET descripcion = '$descripcion' WHERE id = '$id'";
$resultado = $conexion->query($sql);

$file = file_get_contents($_FILES['file']['tmp_name']);
$file = addslashes($file); 


if($file != null){
	$sql = " UPDATE trailers SET foto_video = '$file' WHERE id = '$id'";
	$resultado = $conexion->query($sql);
}

try {
	// guardamos novedad
	//$resultado = $conexion->query($sql);
	$_SESSION['exito'] = '<li>Actualizacion correcta.</li>';
	
	header("Location: trailer.php?id=$id_libro");
} catch(Exception $e) {
	$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
	header('Location: registrarse.php');
}

}