<?php 
session_start();
include 'db.php';

$db = conectar();
$id = $_GET['id'];
$usuario_id = $_GET['usuario_id'];

$sql = "SELECT count(*) as cant FROM perfiles WHERE usuario_id = $usuario_id";
$resultado = $db->query($sql);
$cant = $resultado->fetch_assoc()['cant'];

// contar perfiles, si solo le queda uno no dejarlo borrar, devolverlo a administrarperfiles.php
if($cant == 1) {
	$_SESSION['errores'] = '<li>Solo te queda un perfil, no lo podes borrar.</li>';

    header("Location: administrarperfiles.php");
} else {
    $sql = "DELETE FROM perfiles WHERE id = $id";
    $db->query($sql);
    
	$_SESSION['exito'] = '<li>Perfil borrado exitosamente.</li>';
    
    header("Location: perfiles.php");
}

?>