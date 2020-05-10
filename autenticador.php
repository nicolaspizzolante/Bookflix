<?php
include 'db.php';
session_start();

class Autenticador {

	//chequea si un usuario esta logeado
	function estaLogeado(){
		return isset($_SESSION['usuario']);
	}

	function esAdmin(){
		return $_SESSION['usuario']['es_admin'];
	}

	function cerrarSesion(){
		unset($_SESSION['usuario']);
	}

	function loginUser ($email, $contrasenia){
		$conexion = conectar();

		$sql = "SELECT id, email, apellido, nombre, es_admin
						FROM usuarios
						WHERE email = '$email' AND contrasenia = '$contrasenia'";
		
		$resultado = $conexion->query($sql);

		//Si el usuario existe, asignarlo a la variable "usuario" de la variable $_SESSION
		if($usuario = $resultado->fetch_assoc()){
			$_SESSION['usuario'] = $usuario;
		} else {
			throw new Exception("Credenciales inválidas", 1);
		}
	}

	function estaAutorizado($idMensaje, $idUsuario){
		$conexion = conectar();

		$sql = "SELECT usuarios_id FROM mensaje WHERE id = '$idMensaje'";
		
		$mensaje = $conexion->query($sql);
		$usuarios_id = $mensaje->fetch_assoc();
		$usuarios_id = $usuarios_id['usuarios_id'];

		//Si el id del autor del mensaje coincide con el id logeado, autorizar el borrado
		if ($idUsuario == $usuarios_id) {
			//primero se borran todos los likes del mensaje
			$sql = "DELETE FROM me_gusta WHERE mensaje_id = '$idMensaje'";
			$resultado = $conexion->query($sql);

			//se borra el mensaje
			$sql = "DELETE FROM mensaje WHERE id = '$idMensaje'";
			$resultado = $conexion->query($sql);
		} else {
			throw new Exception("El usuario no puede realizar esta accion", 1);
		}
	}
}