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
	$_SESSION['errores'] .= '<li>El ISBN ya está cargado.</li>';
	header('Location: cargarmetadatos.php'); 
} else {
	//Obtiene la foto 
	$imagen = file_get_contents($_FILES['foto']['tmp_name']);
	$imagen = addslashes($imagen); 

	//$pdf = file_get_contents($_FILES['pdf']['tmp_name']);
	//$pdf = addslashes($pdf); 

    $fecha = date("Y-m-d H:i:s");
	$sql = "
        INSERT 
        INTO libros (titulo, isbn, autor_id, editorial_id, genero_id, fecha_de_subida,imagen,pdf) 
        VALUES('$titulo', '$isbn', '$autor_id', '$editorial_id', '$genero_id', '$fecha','$imagen','$pdf')
    ";
	try {
        // guardamos usuario
        $resultado = $conexion->query($sql);
        $_SESSION['exito'] = '<li>Carga de metadatos correcta.</li>';
        
		header('Location: cargarmetadatos.php');
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: registrarse.php');
	}
	
}