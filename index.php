<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}
	
	include 'views/header.php';	
?>
	
<div class="container">
	<?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" class="errores_mensaje">
			<?php 
			  echo $_SESSION['errores']; 
			  unset($_SESSION['errores']);
			?>
		</ul>
	<?php endif ?>

	<!-- VER NOVEDADES -->
	<h2>Ultimas novedades</h2>
	<?php 
		$conexion = conectar();

		$sql = "SELECT id, titulo FROM novedades";
		$novedades = $conexion->query($sql);
		$novedades = $novedades->fetch_all(MYSQLI_ASSOC);
		foreach ($novedades as $novedad){
			echo '<p><a href=novedad.php?id=' . $novedad['id'] .'>' . $novedad['titulo']. '</a></p>';
		}
	?>
</div>

<?php include 'views/footer.php'; ?>