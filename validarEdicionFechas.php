<?php
include 'db.php';
session_start();

$conexion = conectar();

$id = $_GET['id']; //id de libro completo o capitulo
$idLibro = $_GET['idLibro']; //id metadatos
$fechaPublicacion = $_POST['nuevaFechaPublicacion'];
$fechaVencimiento = $_POST['nuevaFechaVencimiento'];
$checkPublicacion = isset($_POST['checkFechaPublicacion']);
$checkVencimiento = isset($_POST['checkFechaVencimiento']);


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
	
	if(((substr($libro_pdf['fecha_vencimiento'],0,16) == $fechaHoraVenc) and ($fechaHoraVenc > $fechaHoraPub))){
		$fechaVencimientoValida = TRUE;
	}else{
		$fechaVencimientoValida = ((($fechaHoraVenc != '')and ($fechaHoraVenc>date('Y-m-d H:i'))and ($fechaHoraVenc > $fechaHoraPub)) or ($fechaHoraVenc==''));
	}
	
	if($fechaPublicacionValida and $fechaVencimientoValida){
		if((!$checkVencimiento) && (!$checkPublicacion)){ //ninguno de los checkbox esta marcado
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
		}else{//algunos de los checkbox está marcado
			$sql = "SELECT * FROM libros_pdf WHERE libro_id = '$idLibro'";
			$libro_pdf = $conexion->query($sql);
			if($checkPublicacion && $checkVencimiento){
				while ($cap = $libro_pdf->fetch_assoc()){
					$ident = $cap['id'];
					$sql2 = "UPDATE libros_pdf SET fecha_publicacion = '$fechaPublicacion', fecha_vencimiento = '$fechaVencimiento' WHERE id = '$ident'";
					try {
						$resultado = $conexion->query($sql2);
						$_SESSION['exito'] = '<li>Fechas actualizadas.</li>';	
						header("Location: modificarFechasPublicacionVencimiento.php?id=$id");
					} catch(Exception $e) {
						$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
						header('Location: registrarse.php');
					}
				}
			}else{
				if($checkPublicacion){ //actualizo todos los capitulos con la misma fecha de publicacion
					while ($cap = $libro_pdf->fetch_assoc()){
						$ident = $cap['id'];
						$sql2 = "UPDATE libros_pdf SET fecha_publicacion = '$fechaPublicacion' WHERE id = '$ident'";
						try {
							$resultado = $conexion->query($sql2);
						} catch(Exception $e) {
							$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
							header('Location: registrarse.php');
						}
					}
					if($fechaHoraVenc!=' '){
						$sql = " UPDATE libros_pdf SET fecha_vencimiento = '$fechaVencimiento' WHERE id = '$id'";
						try {
							$resultado = $conexion->query($sql);
							$_SESSION['exito'] = '<li>Fechas actualizadas.</li>';	
							header("Location: modificarFechasPublicacionVencimiento.php?id=$id");
						} catch(Exception $e) {
							$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
							header('Location: registrarse.php');
						}
					}
				}
				if($checkVencimiento){ //actualizo todos los capitulos con la misma fecha de vencimiento
					while ($cap = $libro_pdf->fetch_assoc()){
						$ident = $cap['id'];
						$sql2 = "UPDATE libros_pdf SET fecha_vencimiento = '$fechaVencimiento' WHERE id = '$ident'";
						try {
							$resultado = $conexion->query($sql2);
						} catch(Exception $e) {
							$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
							header('Location: registrarse.php');
						}
					}
					$sql = " UPDATE libros_pdf SET fecha_publicacion = '$fechaPublicacion' WHERE id = '$id'";
					try {
						$resultado = $conexion->query($sql);
						$_SESSION['exito'] = '<li>Fechas actualizadas.</li>';	
						header("Location: modificarFechasPublicacionVencimiento.php?id=$id");
					} catch(Exception $e) {
						$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
						header('Location: registrarse.php');
					}
					
					
				}
			}
		}
	}else{
		if(!$fechaPublicacionValida){
			$_SESSION['errores'] = '<li>La fecha de publicacion debe ser posterior a la actual.</li>';
		}
		if(!$fechaVencimientoValida){
			$_SESSION['errores'] .= '<li>La fecha de vencimiento debe ser posterior a fecha de publicacion y a la actual.</li>';
		}
		header("Location: modificarFechasPublicacionVencimiento.php?id=$id");
	}




