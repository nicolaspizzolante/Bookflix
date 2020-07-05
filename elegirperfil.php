<?php 
include 'db.php';


session_start();

$db = conectar();
$_SESSION['usuario']['perfil_id'] = $_GET['id'];
$_SESSION['usuario']['perfil_nombre'] = $_GET['nombre'];

header("Location: index.php");

?>