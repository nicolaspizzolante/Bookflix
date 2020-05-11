<?php 
include 'db.php';
include 'validarPassword.php';
session_start();

$conexion = conectar();

$email = trim($_POST["email"]);
$nombre = trim($_POST["nombre"]);
$apellido = trim($_POST["apellido"]);
$contrasenia = $_POST["contrasenia"];
$confirmar = $_POST["confirmar_pass"];
$numero_tarjeta = trim($_POST["numero_tarjeta"]);
$codigo_tarjeta = trim($_POST["codigo_tarjeta"]);
$mes_vencimiento = trim($_POST["mes_vencimiento"]);
$anio_vencimiento = trim($_POST["anio_vencimiento"]);
$nombre_tarjeta = $_POST["nombre_tarjeta"];

if (($email == '') or (!preg_match('[@]',$email))) {
	$_SESSION['errores'] .= '<li>Ingrese una direccion de email valida.</li>';
}

if (($nombre == '') or (!preg_match('/^[A-Za-z\s]+$/',$nombre))) {
	$_SESSION['errores'] .= '<li>Ingrese un nombre valido.</li>';
}

if (($apellido == '') or (!preg_match('/^[A-Za-z\s]+$/',$apellido))) {
	$_SESSION['errores'] .= '<li>Ingrese un apellido valido.</li>';
}

if ((strlen($numero_tarjeta) != 16) or (!preg_match('/^[0-9\s]+$/', $numero_tarjeta))){
	$_SESSION['errores'] .= '<li>Ingrese un numero de tarjeta valido desde</li> ';
}

if (strlen($codigo_tarjeta) != 3){
	$_SESSION['errores'] .= '<li>Ingrese un codigo de tarjeta valido.</li>';
}

$error_password = '';
if (!validar_password($contrasenia, $error_password)){
	$_SESSION['errores'] .= $error_password;
} else {
	if ($contrasenia != $confirmar){
		$_SESSION['errores'] .= '<li>Las contraseñas deben coincidir.</li>';
	}
}

if ($_SESSION['errores']){
	header('Location: registrarse.php');
	exit;
}

// consulta para saber si el email de usuario ya existe en la db
$sql = "SELECT id, email FROM usuarios WHERE email = '$email'";
$resultado = $conexion->query($sql);
$usuario = $resultado->fetch_assoc();

//consulta para saber si el numero de tarjeta ya existe en la bd
$sql = "SELECT id, numero FROM tarjetas WHERE numero = '$numero_tarjeta'";
$resultado = $conexion->query($sql);
$tarjeta = $resultado->fetch_assoc();


if(($usuario != null) or ($tarjeta != null)) {
	if($usuario != null){
		$_SESSION['errores'] .= '<li>El email ya existe.</li>';
		header('Location: registrarse.php'); 
	}
	if($tarjeta != null){
		$_SESSION['errores'] .= '<li>La tarjeta ya existe.</li>';
		header('Location: registrarse.php'); 
	}
} else {
	$sql = "INSERT INTO usuarios (apellido, nombre, email, contrasenia,esAdministrador) VALUES('$apellido', '$nombre', '$email', '$contrasenia','0')";
	
	try {
		// guardamos usuario
		$resultado = $conexion->query($sql);
		$_SESSION['exito'] = '<li>Te registraste con exito.</li>';
		$_SESSION['id'] = mysqli_insert_id($conexion);
		
		$id = $_SESSION['id'];

		// guardamos tarjeta
		$sql_tarjeta = "INSERT INTO tarjetas (numero, codigo, mes_vencimiento, anio_vencimiento, nombre_y_apellido, usuario_id) VALUES('$numero_tarjeta', '$codigo_tarjeta', '$mes_vencimiento', '$anio_vencimiento', '$nombre_tarjeta', '$id')";
		$resultado = $conexion->query($sql_tarjeta);
		
		header('Location: index.php');
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: registrarse.php');
	}
	
}