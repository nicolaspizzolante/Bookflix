<?php 
include 'db.php';

session_start();

$conexion = conectar();
$usuario_id = $_SESSION['usuario']['id'];
$nombre = trim($_POST["nombre"]);

// consulta para saber si el libro ya existe en la db
$sql = "SELECT nombre FROM perfiles WHERE usuario_id = '$usuario_id' AND nombre = '$nombre'";
$resultado = $conexion->query($sql);

// si ya existe un perfil con ese nombre devuelvo error
if($resultado->num_rows){
    $_SESSION['errores'] .= '<li>Nombre de perfil existente.</li>';
	header("Location: altaperfil.php");
} else {
    $sql = "INSERT INTO perfiles (nombre, usuario_id) VALUES('$nombre', '$usuario_id')";
    
    try {
        // guardamos usuario
        $resultado = $conexion->query($sql);

        $sql = "UPDATE reportes_usuarios SET cant_perfiles_activos = cant_perfiles_activos +1 WHERE usuario_id = '$usuario_id'";
        $resultado = $conexion->query($sql);

        $_SESSION['exito'] = '<li>Perfil creado exitosamente.</li>';
        
		header('Location: perfiles.php');
	} catch(Exception $e) {
		$_SESSION['errores'] = '<li>Error de la base de datos.</li>';
		header('Location: login.php');
	}

}
