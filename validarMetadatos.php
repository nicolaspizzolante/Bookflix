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
$sinopsis = trim($_POST["sinopsis"]);
$cantidadCapitulos = ($_POST['cantCapitulos']);

if ($_SESSION['errores']){
	header("Location: cargarmetadatos.php?titulo=$titulo&isbn=$isbn&sinopsis=$sinopsis&id_autor=$autor_id&id_editorial=$editorial_id&id_genero=$genero_id");
	exit;
}

// consulta para saber si el libro ya existe en la db
$sql = "SELECT id FROM libros WHERE isbn = '$isbn'";
$resultado = $conexion->query($sql);
$libro = $resultado->fetch_assoc();

if($libro != null) {
	$_SESSION['errores'] .= '<li>El ISBN ya está cargado.</li>';
	header("Location: cargarmetadatos.php?titulo=$titulo&isbn=$isbn&sinopsis=$sinopsis&id_autor=$autor_id&id_editorial=$editorial_id&id_genero=$genero_id"); 
} else {
	//Obtiene la foto 
	$imagen = file_get_contents($_FILES['foto']['tmp_name']);
	$imagen = addslashes($imagen); 


    $fecha = date("Y-m-d H:i:s");
	$sql = "
        INSERT 
        INTO libros (titulo, isbn, autor_id, editorial_id, genero_id, fecha_de_subida,imagen,pdf,sinopsis,capitulos) 
        VALUES('$titulo', '$isbn', '$autor_id', '$editorial_id', '$genero_id', '$fecha','$imagen','$pdf','$sinopsis','$cantidadCapitulos')
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