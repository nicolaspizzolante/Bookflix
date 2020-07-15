<?php 
session_start();
include 'db.php';

$db = conectar();
$id = $_GET['id'];
$usuario_id = $_GET['usuario_id'];

$sql = "SELECT count(*) as cant FROM perfiles WHERE usuario_id = $usuario_id";
$resultado = $db->query($sql);
$cant = $resultado->fetch_assoc()['cant'];

// si solo le queda uno no dejarlo borrar, devolverlo a administrarperfiles.php
if($cant == 1) {
	echo 'Solo te queda un perfil, no lo podes borrar';

    header("Location: administrarperfiles.php");
} else {
    $sql = "DELETE FROM perfiles WHERE id = $id";
    $db->query($sql);

    $sql = "UPDATE reportes_usuarios SET cant_perfiles_activos = cant_perfiles_activos -1 WHERE usuario_id = '$usuario_id'";
    $db->query($sql);
    
	$_SESSION['exito'] = '<li>Perfil borrado exitosamente.</li>';
    
    header("Location: perfiles.php");
}

?>