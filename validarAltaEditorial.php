<?php
include 'db.php';
session_start();

$conexion = conectar();

$aux =$_GET['validar'];
$idlibro = $_GET['idlibro'];

$id = $_POST['id'];
$nuevaEditorial = isset($_POST['nuevaEditorial']) ? $_POST['nuevaEditorial'] : '';

if (($nuevaEditorial == '') or (!preg_match('/^[A-Za-z0-9\s]+$/',$nuevaEditorial))) {
	$_SESSION['errores'] .= '<li>El nombre no puede ser vacio o contener caracteres especiales.</li>';
}

if ($_SESSION['errores']){
	if($aux == 1){
		header('Location: altaeditorial.php?validar=1&idlibro=' . $idlibro);
	}else{
		if($aux == 2){
			header('Location: altaeditorial.php?validar=2&idlibro=' . $idlibro);
		}else{
			header('Location: altaeditorial.php?validar=3&idlibro=' . $idlibro);
		}
	}
	exit;
}

// consulta para saber si el nombre de editorial ya existe en la db
$sql = "SELECT id, nombre FROM editoriales WHERE nombre = '$nuevaEditorial'";
$resultado = $conexion->query($sql);
$usuario = $resultado->fetch_assoc();

if($usuario != null) {
	$_SESSION['errores'] .= '<li>La editorial ya se encuentra cargada.</li>';
	header('Location: altaeditorial.php?validar=1&idlibro=' . $idlibro); 
} else {
	$sql = "INSERT INTO editoriales (nombre) VALUES('$nuevaEditorial')";
	
	try {
		// guardamos editorial
		$resultado = $conexion->query($sql);
		if($aux == 1){
			$_SESSION['exito'] = '<li>Se cargo con exito la nueva editorial.</li>';
			header('Location: altaeditorial.php?validar=1&idlibro=' . $idlibro);
		}else{
			if($aux == 2){
				$_SESSION['exito'] = '<li>Se cargo con exito la nueva editorial.</li>';
				header('Location: cargarMetadatos.php?id=' . $idlibro . '&from_alta_editorial=1');
			}else{
				$_SESSION['exito'] = '<li>Se cargo con exito la nueva editorial.</li>';
				header('Location: modificarMetadatos.php?id=' . $idlibro . '&from_alta_editorial=1');
			}
			
		}
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: altaeditorial.php?validar=1&idlibro=' . $idlibro );
	}
	
}

if(!isset($_SESSION['errores'])){
	if($aux == 1){
		header('Location: altaeditorial.php?validar=1&idlibro=' . $idlibro);
	}else{
		if($aux == 2){
			header('Location: cargarMetadatos.php?id=' . $idlibro . '&from_alta_editorial=1');
		}else{
			header('Location: modificarMetadatos.php?id=' . $idlibro . '&from_alta_editorial=1');
		}
	}
} else {
	if($aux == 1){
		header('Location: altaeditorial.php?validar=1&idlibro=' . $idlibro);
	}else{
		if($aux == 2){
			header('Location: cargarMetadatos.php?id=' . $idlibro . '&from_alta_editorial=1');
		}else{
			header('Location: modificarMetadatos.php?id=' . $idlibro . '&from_alta_editorial=1');
		}
	}
}
