<?php
include 'db.php';
session_start();
//$_SESSION['errores'] = '';
$conexion = conectar();

$id = $_GET['id'];
$fechaPublicacion = $_POST['nuevaFechaPublicacion'];
$fechaVencimiento = $_POST['nuevaFechaVencimiento'];

$bol = FALSE;
date_default_timezone_set('America/Argentina/Buenos_Aires');

$sql = "SELECT * FROM libros_pdf WHERE id=$id";
$resultado = $conexion->query($sql);
$libro_pdf = $resultado->fetch_assoc();


	var_dump(date('Y-m-d H:i:s'));
	var_dump($fechaPublicacion);
	$fechaPub = substr($fechaPublicacion,0,10);
	$fechaVenc = substr($fechaVencimiento,0,10);
	$horaPub = substr($fechaPublicacion,11,18);
	$horaVenc = substr($fechaVencimiento,11,18);
	$fechaHoraPub = $fechaPub.' '.$horaPub;
	$fechaHoraVenc = $fechaVenc.' '.$horaVenc;
	var_dump($fechaHoraPub);
	var_dump($fechaHoraVenc);
	$fechaPublicacionValida = (($fechaPublicacion != '') and ($fechaHoraPub > date('Y-m-d H:i:s')));
	$fechaVencimientoValida = (($fechaVencimiento != '')and ($fechaHoraVenc > $fechaHoraPub));
	var_dump($fechaVencimientoValida);
	var_dump($fechaPublicacionValida);
	if($fechaPublicacionValida and $fechaVencimientoValida){
		$sql = " UPDATE libros_pdf SET fecha_publicacion = '$fechaPublicacion', fecha_vencimiento = '$fechaVencimiento' WHERE id = '$id'";
		try {
			$resultado = $conexion->query($sql);
			$_SESSION['exito'] = '<li>Fechas actualizadas.</li>';	
			header("Location: modificarFechasPublicacionVencimiento.php?id=$id");
		} catch(Exception $e) {
			$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
			header('Location: registrarse.php');
		}
	}else{
		if(!$fechaPublicacionValida){
			$_SESSION['errores'] = '<li>La fecha de publicacion debe ser posterior a la actual.</li>';
		}
		if(!$fechaVencimientoValida){
			$_SESSION['errores'] .= '<li>La fecha de vencimiento debe ser posterior a fecha de publicacion.</li>';
		}
		header("Location: modificarFechasPublicacionVencimiento.php?id=$id");
	}

	/*if(($fechaPublicacion != '') and ($fechaHoraPub > date('Y-m-d H:i:s'))){
		$sql = " UPDATE libros_pdf SET fecha_publicacion = '$fechaPublicacion' WHERE id = '$id'";
		try {
			$resultado = $conexion->query($sql);
			$_SESSION['exito'] = '<li>Fecha de publicacion actualizada.</li>';
			
			
		} catch(Exception $e) {
			$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
			header('Location: registrarse.php');
		}
	}else{
		$_SESSION['errores'] = '<li>La fecha de publicacion debe ser posterior a la actual.</li>';
		header("Location: modificarFechasPublicacionVencimiento.php?id=$id");
	}
	if(($fechaVencimiento != '')and ($fechaHoraVenc > date('Y-m-d H:i:s')) and ($fechaHoraVenc > $fechaHoraPub)){
		$bol = TRUE;
		$sql = " UPDATE libros_pdf SET fecha_vencimiento = '$fechaVencimiento' WHERE id = '$id'";
		try {
			$resultado = $conexion->query($sql);
			$_SESSION['exito'] .= '<li>Fecha de vencimiento actualizada.</li>';
			
			
		} catch(Exception $e) {
			$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
			header('Location: registrarse.php');
		}
	}else{
		$_SESSION['errores'] .= '<li>La fecha de vencimiento debe ser posterior a fecha de publicacion.</li>';
		header("Location: modificarFechasPublicacionVencimiento.php?id=$id");
	}*/


//header("Location: modificarFechasPublicacionVencimiento.php?id=$id");



