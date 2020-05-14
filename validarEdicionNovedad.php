<?php 
include 'db.php';
include 'validarPassword.php';
session_start();

$conexion = conectar();

$id = $_GET['id'];
$titulo = trim($_POST["titulo"]);
$descripcion = trim($_POST["descripcion"]);

if ($_SESSION['errores']){
	header('Location: modificarMetadatos.php');
	exit;
}

//consulta para saber si el libro ya existe en la db
$sql = "SELECT id FROM libros WHERE isbn = '$isbn' AND id <> '$id'";
$resultado = $conexion->query($sql);
$libro = $resultado->fetch_assoc();
