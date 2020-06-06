<?php
include 'db.php';
include 'validarPassword.php';
session_start();

$conexion = conectar();
$autor= trim($_POST["autor"]);

$aux =$_GET['validar'];
$idlibro = $_GET['idlibro'];

if (($autor == '') or (!preg_match('/^[A-Za-z\s]+$/',$autor))) {
	$_SESSION['errores'] .= '<li>Ingrese un nombre valido.</li>';
}
if ($_SESSION['errores']){
	if($aux == 1){
		header('Location: altaautor.php?validar=1&idlibro=' . $idlibro);
	}else{
		if($aux == 2){
			header('Location: altaautor.php?validar=2&idlibro=' . $idlibro);
		}else{
			header('Location: altaautor.php?validar=3&idlibro=' . $idlibro);
		}
	}
	exit;
}

// consulta para saber si el nombre de autor ya existe en la db
$sql = "SELECT id, nombre FROM autores WHERE nombre = '$autor'";
$resultado = $conexion->query($sql);
$usuario = $resultado->fetch_assoc();

if($usuario != null) {
	$_SESSION['errores'] .= '<li>El autor ya se encuentra cargado.</li>';
	header('Location: altaAutor.php?validar=1&idlibro=' . $idlibro);
} else {
	$sql = "INSERT INTO autores (nombre) VALUES('$autor')";
	
	try {
		// guardamos autor
		$resultado = $conexion->query($sql);

		if($aux == 1){
			$_SESSION['exito'] = '<li>Se cargo con exito el nuevo autor.</li>';
			header('Location: altaAutor.php?validar=1&idlibro=' . $idlibro);
		}else{
			if($aux == 2){
				$_SESSION['exito'] = '<li>Se cargo con exito el nuevo autor.</li>';
				header('Location: cargarMetadatos.php?id=' . $idlibro . '&from_alta_autor=1');
			}else{
				$_SESSION['exito'] = '<li>Se cargo con exito el nuevo autor.</li>';
				header('Location: modificarMetadatos.php?id=' . $idlibro . '&from_alta_autor=1');
			}
			
		}
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: altaAutor.php?validar=1&idlibro=' . $idlibro);
	}
	
}

if(!isset($_SESSION['errores'])){
	if($aux == 1){
		header('Location: altaautor.php?validar= 1&idlibro=' . $idlibro);
	}else{
		if($aux == 2){
			header('Location: cargarMetadatos.php?id=' . $idlibro . '&from_alta_autor=1');
		}else{
			header('Location: modificarMetadatos.php?id=' . $idlibro . '&from_alta_autor=1');
		}
	}
} else {
	if($aux == 1){
		header('Location: altaautor.php?validar= 1&idlibro=' . $idlibro . '&from_alta_autor=1');
	}else{
		if($aux == 2){
			header('Location: cargarMetadatos.php?id=' . $idlibro . '&from_alta_autor=1');
		}else{
			header('Location: modificarMetadatos.php?id=' . $idlibro . '&from_alta_autor=1');
		}
	}
}