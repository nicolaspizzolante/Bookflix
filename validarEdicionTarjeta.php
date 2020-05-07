<?php
include 'db.php';
session_start();

$conexion = conectar();

$id = $_POST['ident'];
$numeroNuevo = isset($_POST['numeroTarjeta']) ? $_POST['numeroTarjeta'] : '';
$claveNueva = isset($_POST['codigoTarjeta']) ? $_POST['codigoTarjeta'] : '';
$nombreApellidoNuevo = isset($_POST['nombreTarjeta']) ? $_POST['nombreTarjeta'] : '';
$mesNuevo = isset($_POST['mes_vencimiento']) ? $_POST['mes_vencimiento'] : '';
$anioNuevo = isset($_POST['anio_vencimiento']) ? $_POST['anio_vencimiento'] : '';

if ((strlen($numeroNuevo) != 16) or (!preg_match('/^[0-9\s]+$/', $numeroNuevo))){
	$_SESSION['errores'] .= '<li>Ingrese un numero de tarjeta valido</li> ';
}

if (strlen($claveNueva) != 3){
	$_SESSION['errores'] .= '<li>Ingrese un codigo de tarjeta valido.</li>';
}
if(!preg_match('/^[A-Za-z\s]+$/',$nombreApellidoNuevo)){
    $_SESSION['errores'] .= '<li>Ingrese un nombre de tarjeta valido</li>';
}
if(!preg_match('/^[0-9\s]+$/', $mesNuevo)){
    $_SESSION['errores'] .= '<li>El mes de vencimiento debe ser un numero</li>';
}
if(!preg_match('/^[0-9\s]+$/', $anioNuevo)){
    $_SESSION['errores'] .= '<li>El año de vencimiento debe ser un numero</li>';

}

if ($_SESSION['errores']){
	header('Location: editar.php');
	exit;
}

if(!isset($_SESSION['errores'])){
	try{
		$sql = "UPDATE tarjetas SET numero = '$numeroNuevo' WHERE usuario_id = '$id'";
		$resultado = $conexion->query($sql);

		$sql = "UPDATE tarjetas SET codigo = '$claveNueva' WHERE usuario_id = '$id'";
		$resultado = $conexion->query($sql);

		$sql = "UPDATE tarjetas SET nombre_y_apellido = '$nombreApellidoNuevo' WHERE usuario_id = '$id'";
		$resultado = $conexion->query($sql);

		$sql = "UPDATE tarjetas SET mes_vencimiento = '$mesNuevo' WHERE usuario_id = '$id'";
		$resultado = $conexion->query($sql);

		$sql = "UPDATE tarjetas SET anio_vencimiento = '$anioNuevo' WHERE usuario_id = '$id'";
		$resultado = $conexion->query($sql);
		$_SESSION['exito'] = '<li>Los datos de la tarjeta fueron actualizados.</li>';
	}catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: muro.php');
	}
} else {
	header('Location: editar.php');
}

