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
	<div class = "h3Editar">
		<h3>Datos personales</h3>
	</div>
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

		<div class="h3Editar">
		<a href="cambiarcontrasenia.php" class="btn btn-success">Cambiar contraseña</a>
		</div>

		<div class="input">
			<input type="submit">
		</div>
	</form>
	<hr>
	<div class = "h3Editar">
		<h3>Datos de la tarjeta</h3>
	</div>
	
	<form action="validarEdicionTarjeta.php" method="post" onsubmit="return validarEditarTarjeta(this);" enctype="multipart/form-data">
		<input type="hidden" name="ident" value="<?php echo $_SESSION['usuario']['id']; ?>">
		<div class="input">
			<label for="numeroTarjeta">Cambiar numero:</label>
			<input type="text" name="numeroTarjeta" placeholder="Numero tarjeta">
		</div>
		
		<div class="input">
			<label for="codigoTarjeta">Cambiar codigo:</label>
			<input type="text" name="codigoTarjeta" placeholder="Clave tarjeta">
		</div>
		
		<div class="input">
			<label for="nombreTarjeta">Cambiar nombre:</label>
			<input type="text" name="nombreTarjeta" placeholder="Nombre y apellido">
		</div>
		<div style="display:flex; flex-direction:row; width:400px;">
		<label for="numeroTarjeta">Cambiar fecha:</label>
			<div class="input">
				<input style="width:122.5px; margin-right:5px; margin-left:5px" type="text" name="mes_vencimiento" placeholder="Mes vencimento">
			</div>

			<div class="input">
				<input style="width:122.5px;" type="text" name="anio_vencimiento" placeholder="Año vencimiento">
			</div>
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

	<?php if (isset($_SESSION['exito'])){ ?>
			<ul id="exito" style="display:block;">
				<?php 
					echo $_SESSION['exito']; 
					unset($_SESSION['exito']);
				?>
			</ul>
		<?php } ?>
</div>
	
<?php include 'views/footer.php'; ?>