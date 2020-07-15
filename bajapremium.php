<?php 
include 'db.php';

session_start();

$conexion = conectar();
$usuario_id = $_SESSION['usuario']['id'];

// consulta para saber si el libro ya existe en la db
$sql = "UPDATE usuarios SET es_premium = 0 WHERE id = $usuario_id";
$resultado = $conexion->query($sql);

$sql = "UPDATE reportes_usuarios SET es_premium = 0 WHERE usuario_id = $usuario_id";
$resultado = $conexion->query($sql);

$_SESSION['usuario']['es_premium'] = 0;

header("Location: perfiles.php");