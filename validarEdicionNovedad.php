<?php 
include 'db.php';
include 'validarPassword.php';
session_start();

$conexion = conectar();

$id = $_GET['id'];
$titulo = trim($_POST["titulo"]);
$descripcion = trim($_POST["descripcion"]);

if ($_SESSION['errores']){
	header('Location: modificarMetadatos.php');
	exit;
}

//consulta para saber si la novedad ya existe en la db
$sql = "SELECT id FROM novedades WHERE titulo = '$titulo' AND id <> '$id'";
$resultado = $conexion->query($sql);
$novedad = $resultado->fetch_assoc();

if($novedad!=null){
	$_SESSION['errores'] .= '<li>La novedad ya está cargada.</li>';
	$v = 1;
	header("Location: modificarNovedad.php?id=$id&tituloNovedad=$titulo&descripcionNovedad=$descripcion&verificar=$v"); 
}else{

	$sql = " UPDATE novedades SET titulo = '$titulo' WHERE id = '$id'";
$resultado = $conexion->query($sql);

$sql = "UPDATE novedades SET descripcion = '$descripcion' WHERE id = '$id'";
$resultado = $conexion->query($sql);

$file = file_get_contents($_FILES['file']['tmp_name']);
$file = addslashes($file); 


if($file != null){
	$sql = " UPDATE novedades SET foto_video = '$file' WHERE id = '$id'";
	$resultado = $conexion->query($sql);
}

try {
	// guardamos novedad
	//$resultado = $conexion->query($sql);
	$_SESSION['exito'] = '<li>Actualizacion correcta.</li>';
	
	header("Location: novedad.php?id=$id");
} catch(Exception $e) {
	$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
	header('Location: registrarse.php');
}

}

