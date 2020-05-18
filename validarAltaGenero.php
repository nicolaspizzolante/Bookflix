<?php
include 'db.php';
session_start();

$conexion = conectar();

$aux =$_GET['validar'];
$idlibro = $_GET['idlibro'];

$id = $_POST['id'];
$nuevoGenero = isset($_POST['nuevoGenero']) ? $_POST['nuevoGenero'] : '';

if (($nuevoGenero == '') or (!preg_match('/^[A-Za-z0-9\s]+$/',$nuevoGenero))) {
	$_SESSION['errores'] .= '<li>El nombre no puede ser vacio o contener caracteres especiales.</li>';
}

if ($_SESSION['errores']){
	if($aux == 1){
		header('Location: altagenero.php?validar= 1&idlibro=' . $idlibro);
	}else{
		if($aux == 2){
			header('Location: altagenero.php?validar=2&idlibro=' . $idlibro);
		}else{
			header('Location: altagenero.php?validar=3&idlibro=' . $idlibro);
		}
	}
	exit;
}

// consulta para saber si el nombre de genero ya existe en la db
$sql = "SELECT id, nombre FROM generos WHERE nombre = '$nuevoGenero'";
$resultado = $conexion->query($sql);
$usuario = $resultado->fetch_assoc();

if($usuario != null) {
	$_SESSION['errores'] .= '<li>El genero ya se encuentra cargado.</li>';
	header('Location: altaGenero.php?validar=1&idlibro=' . $idlibro); 
} else {
	$sql = "INSERT INTO generos (nombre) VALUES('$nuevoGenero')";
	
	try {
		// guardamos genero
		$resultado = $conexion->query($sql);
		if($aux == 1){
			$_SESSION['exito'] = '<li>Se cargo con exito el nuevo genero.</li>';
			header('Location: altagenero.php?validar=1&idlibro=' . $idlibro);
		}else{
			if($aux == 2){
				$_SESSION['exito'] = '<li>Se cargo con exito el nuevo genero.</li>';
				header('Location: cargarMetadatos.php?id' . $idlibro);
			}else{
				$_SESSION['exito'] = '<li>Se cargo con exito el nuevo genero.</li>';
				header('Location: modificarMetadatos.php?id=' . $idlibro);
			}
			
		}
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: altaGenero.php?validar=1&idlibro=' . $idlibro);
	}
	
}

if(!isset($_SESSION['errores'])){
	if($aux == 1){
		header('Location: altagenero.php?validar= 1&idlibro=' . $idlibro);
	}else{
		if($aux == 2){
			header('Location: cargarMetadatos.php?id=' . $idlibro);
		}else{
			header('Location: modificarMetadatos.php?id=' . $idlibro);
		}
	}
} else {
	if($aux == 1){
		header('Location: altagenero.php?validar= 1&idlibro=' . $idlibro);
	}else{
		if($aux == 2){
			header('Location: cargarMetadatos.php?id=' . $idlibro);
		}else{
			header('Location: modificarMetadatos.php?id=' . $idlibro);
		}
	}
}
