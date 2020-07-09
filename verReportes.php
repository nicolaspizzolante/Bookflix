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
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	$conexion = conectar();

	?>
	<h3>Que reporte desea obtener?</h3>
	<a href="masLeidos.php">Libros en orden de lecturas</a>
	<a href="suscriptosEntreFechas.php">Usuarios suscriptos entre fechas</a>
		
<?php  include 'views/footer.php'; ?>