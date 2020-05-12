<?php
include 'db.php';
session_start();

$conexion = conectar();

$aux =$_GET['validar'];

$id = $_POST['id'];
$nuevoGenero = isset($_POST['nuevoGenero']) ? $_POST['nuevoGenero'] : '';

if (($nuevoGenero == '') or (!preg_match('/^[A-Za-z0-9\s]+$/',$nuevoGenero))) {
	$_SESSION['errores'] .= '<li>El nombre no puede ser vacio o contener caracteres especiales.</li>';
}

if ($_SESSION['errores']){
	if($aux == 1){
		header('Location: altagenero.php?validar= 1');
	}else{
		if($aux == 2){
			header('Location: altagenero.php?validar=2');
		}else{
			header('Location: altagenero.php?validar=3');
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
	header('Location: altaGenero.php'); 
} else {
	$sql = "INSERT INTO generos (nombre) VALUES('$nuevoGenero')";
	
	try {
		// guardamos genero
		$resultado = $conexion->query($sql);
		if($aux == 1){
			$_SESSION['exito'] = '<li>Se cargo con exito el nuevo genero.</li>';
			header('Location: altagenero.php?validar=1');
		}else{
			if($aux == 2){
				$_SESSION['exito'] = '<li>Se cargo con exito el nuevo genero.</li>';
				header('Location: cargarMetadatos.php?validar=2');
			}else{
				$_SESSION['exito'] = '<li>Se cargo con exito el nuevo genero.</li>';
				header('Location: modificarMetadatos.php?validar=3');
			}
			
		}
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: altaGenero.php?validar=1');
	}
	
}

if(!isset($_SESSION['errores'])){
	if($aux == 1){
		header('Location: altagenero.php?validar= 1');
	}else{
		if($aux == 2){
			header('Location: cargarMetadatos.php?validar=2');
		}else{
			header('Location: modificarMetadatos.php?validar=3');
		}
	}
} else {
	if($aux == 1){
		header('Location: altagenero.php?validar= 1');
	}else{
		if($aux == 2){
			header('Location: cargarMetadatos.php?validar=2');
		}else{
			header('Location: modificarMetadatos.php?validar=3');
		}
	}
}
