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
	<h2 style="text-align:center;margin:10px;color:#E51F1F">Que reporte desea obtener?</h2>

	<article class="libro">
				<div class="info">
					<div class="titulo">
						<h3 style="margin-left: 520px">
						<a href="masLeidos.php">Libros en orden de lecturas</a>
						</h3>
					</div>
				</div>
		</article>

		<article class="libro">
				<div class="info">
					<div class="titulo">
						<h3 style="margin-left: 520px">
						<a href="suscriptosEntreFechas.php" style="align-text:center">Usuarios suscriptos entre fechas</a>
						</h3>
					</div>
				</div>
		</article>

	
	
	
		
<?php  include 'views/footer.php'; ?>