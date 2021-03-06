<?php 
include 'db.php';
include 'validarPassword.php';
session_start();

$conexion = conectar();

$id = $_GET['ident'];
$titulo = trim($_POST["titulo"]);
$isbn = trim($_POST["isbn"]);
$editorial_id = trim($_POST["editorial"]);
$genero_id = $_POST["genero"];
$autor_id = $_POST["autor"];
$sinopsis = $_POST["sinopsis"];

if ($_SESSION['errores']){
	header('Location: modificarMetadatos.php');
	exit;
}

//consulta para saber si el libro ya existe en la db
$sql = "SELECT id FROM libros WHERE isbn = '$isbn' AND id <> '$id'";
$resultado = $conexion->query($sql);
$libro = $resultado->fetch_assoc();

if($libro != null) {
	$_SESSION['errores'] .= '<li>El ISBN ya está cargado.</li>';
	header("Location: modificarMetadatos.php?id=$id"); 
} else {
	//Obtiene la foto 
	$imagen = file_get_contents($_FILES['foto']['tmp_name']);
	$imagen = addslashes($imagen); 

	$sql = " UPDATE libros SET titulo = '$titulo' WHERE id = '$id'";
	$resultado = $conexion->query($sql);

	$sql = "UPDATE libros SET autor_id = '$autor_id' WHERE id = '$id'";
	$resultado = $conexion->query($sql);

	$sql = " UPDATE libros SET genero_id = '$genero_id' WHERE id = '$id'";
	$resultado = $conexion->query($sql);

	$sql = " UPDATE libros SET isbn = '$isbn' WHERE id = '$id'";
	$resultado = $conexion->query($sql);

	$sql = " UPDATE libros SET editorial_id = '$editorial_id' WHERE id = '$id'";
	$resultado = $conexion->query($sql);

	$sql = " UPDATE libros SET sinopsis = '$sinopsis' WHERE id = '$id'";
	$resultado = $conexion->query($sql);


	if($imagen != null){
		$sql = " UPDATE libros SET imagen = '$imagen' WHERE id = '$id'";
		$resultado = $conexion->query($sql);
	}

	try {
        // guardamos usuario
        $resultado = $conexion->query($sql);
        $_SESSION['exito'] = '<li>Actualizacion correcta.</li>';
        
		header("Location: modificarMetadatos.php?id=$id");
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: registrarse.php');
	}
	
}