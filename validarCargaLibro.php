<?php 
include 'db.php';
session_start();

$conexion = conectar();

$id = $_GET["id"];
$fecha_publicacion = $_POST["fechaPublicacion"];
$fecha_vencimiento = $_POST["fechaVencimiento"];




if ($_SESSION['errores']){
	header("Location: cargarLibro.php?id=$id");
	exit;
}else{

	$pdf = file_get_contents($_FILES['pdf']['tmp_name']);
	$pdf = addslashes($pdf); 

	$sql = "UPDATE libros SET subidos = 1 WHERE id = '$id'";
	$r = $conexion->query($sql);

	if($fecha_vencimiento == ''){
		$sql = "INSERT INTO libros_pdf (libro_id,pdf,fecha_publicacion) VALUES('$id','$pdf','$fecha_publicacion')";
	try {
		$resultado = $conexion->query($sql);
		$_SESSION['exito'] = '<li>Cargaste un libro.</li>';
		header('Location: verListadoLibros.php');
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: verListadoLibros.php');
	}

	}else{
		$sql = "INSERT INTO libros_pdf (libro_id,pdf,fecha_publicacion,fecha_vencimiento) 
		VALUES('$id','$pdf','$fecha_publicacion','$fecha_vencimiento')";
		try {
			$resultado = $conexion->query($sql);
			$_SESSION['exito'] = '<li>Cargaste un libro.</li>';
			header('Location: verListadoLibros.php');
		} catch(Exception $e) {
			$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
			header('Location: verListadoLibros.php');
		}
	}
}
