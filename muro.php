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
<h1>hola mundo</h1>
	<!-- Datos del usuario loggeado -->
	<div class="datos">
		<div class="nombre"><?php echo $_SESSION['usuario']['nombre']; ?></div>
		<div class="apellido"><?php echo $_SESSION['usuario']['apellido']; ?></div>
	</div>

	<?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" class="errores_mensaje" style="display:block;">
			<?php 
				echo $_SESSION['errores']; 
				unset($_SESSION['errores']);
			?>
		</ul>
	<?php endif ?>

</div>

<?php include 'views/footer.php'; ?>