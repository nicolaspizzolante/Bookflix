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
	<h1>Editar el Perfil</h1>
	<form action="validarEdicion.php" method="post" onsubmit="return validarEditar(this);" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo $_SESSION['usuario']['id']; ?>">
		<div class="input">
			<label for="email">Cambiar email:</label>
			<input type="text" name="email" placeholder="Email" value="<?php echo $_SESSION['usuario']['email']; ?>">
		</div>
		
		<div class="input">
			<label for="nombre">Cambiar nombre:</label>
			<input type="text" name="nombre" placeholder="Nombre" value="<?php echo $_SESSION['usuario']['nombre']; ?>">
		</div>
		
		<div class="input">
			<label for="apellido">Cambiar apellido:</label>
			<input type="text" name="apellido" placeholder="Apellido" value="<?php echo $_SESSION['usuario']['apellido']; ?>">
		</div>

		<div class="input">
			<input type="submit">
		</div>
	</form>

	<?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" class="asd" style="display:block;">
			<?php 
				echo $_SESSION['errores']; 
				unset($_SESSION['errores']);
			?>
		</ul>
	<?php endif ?>
</div>
	
<?php include 'views/footer.php'; ?>