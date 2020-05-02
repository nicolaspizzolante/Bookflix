<?php
include 'db.php';
session_start();

$conexion = conectar();

$id = $_POST['id'];
$email = isset($_POST['email']) ? $_POST['email'] : '';
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$apellido = isset($_POST['apellido']) ? $_POST['apellido'] : '';
$numero_tarjeta = isset($_POST["numero_tarjeta"]) ? $_POST['numero_tarjeta'] : '' ;
$codigo_tarjeta = isset($_POST["codigo_tarjeta"]) ? $_POST['codigo_tarjeta'] : '';
$mes_vencimiento = isset($_POST["mes_vencimiento"]) ? $_POST['mes_vencimiento'] : '';
$anio_vencimiento = isset($_POST["anio_vencimiento"]) ? $_POST['anio_vencimiento'] : '';
$nombre_tarjeta = isset($_POST["nombre_tarjeta"]) ? $_POST['nombre_tarjeta'] : '';

if (($email == '') or (!preg_match('[@]',$email))) {
	$_SESSION['errores'] .= '<li>Ingrese una direccion de email valida.</li>';
} else {
	$sql = "UPDATE usuarios SET email = '$email' WHERE id = '$id'";
	$resultado = $conexion->query($sql);
	$_SESSION['usuario']['email'] = $email;
}

if (($nombre == '') or (!preg_match('/^[A-Za-z\s]+$/',$nombre))) {
	$_SESSION['errores'] .= '<li>Ingrese un nombre valido.</li>';
} else {
	$sql = "UPDATE usuarios SET nombre = '$nombre' WHERE id = '$id'";
	$resultado = $conexion->query($sql);
	$_SESSION['usuario']['nombre'] = $nombre;
}

if (($apellido == '') or (!preg_match('/^[A-Za-z\s]+$/',$apellido))) {
	$_SESSION['errores'] .= '<li>Ingrese un apellido valido.</li>';
} else {
	$sql = "UPDATE usuarios SET apellido = '$apellido' WHERE id = '$id'";
	$resultado = $conexion->query($sql);
	$_SESSION['usuario']['apellido'] = $apellido;
}

if($_FILES['foto_de_perfil']['name'] != ''){
	$arreglo = explode('/', $_FILES['foto_de_perfil']['type']);

	$foto_contenido = file_get_contents($_FILES['foto_de_perfil']['tmp_name']);
	$foto_contenido = addslashes($foto_contenido); 

	$sql = "UPDATE usuarios SET foto_contenido = '$foto_contenido', foto_tipo = '$arreglo[1]' WHERE id = '$id'";
	$resultado = $conexion->query($sql);
}

if(!isset($_SESSION['errores'])){
	header('Location: muro.php');
} else {
	header('Location: editar.php');
}
