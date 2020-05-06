<?php 
include 'db.php';
include 'validarPassword.php';
session_start();

$conexion = conectar();

$titulo = trim($_POST["titulo"]);
$isbn = trim($_POST["isbn"]);
$editorial_id = trim($_POST["editorial"]);
$genero_id = $_POST["genero"];
$autor_id = $_POST["autor"];

if ($_SESSION['errores']){
	header('Location: cargarmetadatos.php');
	exit;
}

// consulta para saber si el libro ya existe en la db
$sql = "SELECT id FROM libros WHERE isbn = '$isbn'";
$resultado = $conexion->query($sql);
$libro = $resultado->fetch_assoc();

if($libro != null) {
	$_SESSION['errores'] .= '<li>El libro ya está cargado.</li>';
	header('Location: cargarmetadatos.php'); 
} else {
	$sql = "INSERT INTO libros (titulo, isbn, autor_id, editorial_id, genero_id) VALUES('$titulo', '$isbn', '$autor_id', '$editorial_id', '$genero_id')";
	try {
		// guardamos usuario
        $_SESSION['exito'] = '<li>Cargaste un libro.</li>';
        
		header('Location: cargarmetadatos.php');
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: registrarse.php');
	}
	
}