<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}

	include 'views/header.php';
?>

<?php
		$conexion = conectar();
		$idlibro = $_GET['id'];
		var_dump($idlibro);
		?>

<?php include 'views/footer.php'; ?>