<?php 
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if ($autenticador->estaLogeado()) {
		header('Location: index.php');
		exit;
	} 

	include 'views/header.php';
?>

	<div class="formulario_inicio">
		<h1>Iniciar Sesion</h1>
		<form action="validarLogin.php" method="post" onsubmit="return validarLogin(this);">
			<div class="input">
				<input type="text" name="email" placeholder="Email">
			</div>
			
			<div class="input">
				<input type="password" name="contrasenia" placeholder="Contraseña">
			</div>
			
			<div class="input">
				<input type="submit">
			</div>
		</form>
		
		<?php if (isset($_SESSION['errores'])){ ?>
			<ul id="errores" style="display:block;">
				<?php 
					echo $_SESSION['errores']; 
					unset($_SESSION['errores']);
				?>
			</ul>
		<?php } ?>

		<?php if (isset($_SESSION['exito'])){ ?>
			<ul id="exito" style="display:block;">
				<?php 
					echo $_SESSION['exito']; 
					unset($_SESSION['exito']);
				?>
			</ul>
		<?php } ?>

		<p>¿No tenes cuenta?</p>
		<div class="input registro">
			<a href="registrarse.php">Registrate</a>
		</div>
	</div>

<?php include 'views/footer.php'; ?>	