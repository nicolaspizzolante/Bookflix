<?php 
include 'db.php';
session_start();

$conexion = conectar();

$id = $_GET["id"];
$fecha_publicacion = $_POST["fechaPublicacion"];
$fecha_vencimiento = $_POST["fechaVencimiento"];
$cantidad = isset($_POST['cantidadCapitulos']) ? $_POST['cantidadCapitulos'] : NULL;

if ($_SESSION['errores']){
	header("Location: cargarCapitulo.php?id=$id");
	exit;
} else {
	if($cantidad){
		$sql = "UPDATE libros SET capitulos = $cantidad WHERE id = '$id'";
		$r = $conexion->query($sql);
	}
	$pdf = file_get_contents($_FILES['pdf']['tmp_name']);
	$pdf = addslashes($pdf);

	//se obtiene la cantidad de capitulos subidos 
	$sql = "SELECT subidos,capitulos FROM libros WHERE id = '$id'";
	$r = $conexion->query($sql);
	$l = $r->fetch_assoc();

	if($fecha_vencimiento == ''){
		$sql = "INSERT INTO libros_pdf (libro_id,pdf,fecha_publicacion) VALUES('$id','$pdf','$fecha_publicacion')";
	} else {
		$sql = "INSERT INTO libros_pdf (libro_id,pdf,fecha_publicacion,fecha_vencimiento) 
		VALUES('$id','$pdf','$fecha_publicacion','$fecha_vencimiento')";
	}

	try {
		$resultado = $conexion->query($sql);
		$_SESSION['exito'] = '<li>Cargaste un capitulo.</li>';

		//Se incrementa en 1 la cantidad de subidos
		$inc = $l['subidos'] + 1;
		$sql = "UPDATE libros SET subidos = '$inc' WHERE id = '$id'";
		$r = $conexion->query($sql);
		
		if($inc == $l['capitulos']){
			header('Location: verListadoLibros.php');
		}else{
			header("Location: cargarCapitulo.php?id=$id");
		}
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: verListadoLibros.php');
	}
}
