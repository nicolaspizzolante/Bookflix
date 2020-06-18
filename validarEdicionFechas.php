<?php
include 'db.php';
session_start();

$conexion = conectar();

$id = $_GET['id'];
$fechaPublicacion = $_POST['nuevaFechaPublicacion'];
$fechaVencimiento = $_POST['nuevaFechaVencimiento'];

$bol = FALSE;
date_default_timezone_set('America/Argentina/Buenos_Aires');

$sql = "SELECT * FROM libros_pdf WHERE id=$id";
$resultado = $conexion->query($sql);
$libro_pdf = $resultado->fetch_assoc();


	//var_dump($fechaPublicacion);
	$fechaPub = substr($fechaPublicacion,0,10);
	$fechaVenc = substr($fechaVencimiento,0,10);
	$horaPub = substr($fechaPublicacion,11,5);
	$horaVenc = substr($fechaVencimiento,11,5);
	if($fechaPub == ''){
		$fechaHoraPub = $fechaPub.$horaPub;
	}else{
		$fechaHoraPub = $fechaPub.' '.$horaPub;
	}
	if($fechaVenc == ''){
		$fechaHoraVenc = $fechaVenc.$horaVenc;
	}else{
		$fechaHoraVenc = $fechaVenc.' '.$horaVenc;
	}
	

	if(substr($libro_pdf['fecha_publicacion'],0,16) == $fechaHoraPub){
		$fechaPublicacionValida = TRUE;
	}else{
		$fechaPublicacionValida = (($fechaHoraPub != '') and ($fechaHoraPub >= date('Y-m-d H:i')));
	}
	
	if((substr($libro_pdf['fecha_vencimiento'],0,16) == $fechaHoraVenc) and ($fechaHoraVenc > $fechaHoraPub)){
		$fechaVencimientoValida = TRUE;
	}else{
		$fechaVencimientoValida = ((($fechaHoraVenc != '')and ($fechaHoraVenc > $fechaHoraPub)) or ($fechaHoraVenc==''));
	}
	
	if($fechaPublicacionValida and $fechaVencimientoValida){
		if($fechaHoraVenc==' '){
			$sql = " UPDATE libros_pdf SET fecha_publicacion = '$fechaPublicacion' WHERE id = '$id'";
			try {
				$resultado = $conexion->query($sql);
				$_SESSION['exito'] = '<li>Fechas actualizadas.</li>';	
				header("Location: modificarFechasPublicacionVencimiento.php?id=$id");
			} catch(Exception $e) {
				$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
				header('Location: registrarse.php');
			}
		}else{
			$sql = " UPDATE libros_pdf SET fecha_publicacion = '$fechaPublicacion', fecha_vencimiento = '$fechaVencimiento' WHERE id = '$id'";
			try {
				$resultado = $conexion->query($sql);
				$_SESSION['exito'] = '<li>Fechas actualizadas.</li>';	
				header("Location: modificarFechasPublicacionVencimiento.php?id=$id");
			} catch(Exception $e) {
				$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
				header('Location: registrarse.php');
			}
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




