<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado() || !$autenticador->esAdmin()) {
		header('Location: login.php');
		exit;
	} 
	include 'views/header.php'; 

	$val = $_GET['validar']; 
?>
	
<div class="container">
	<h1>Alta de editorial</h1>
	<form action="validarAltaEditorial.php?validar=<?php echo $val?>" method="post" onsubmit="return validarAltaEditorial(this);" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo $_SESSION['usuario']['id']; ?>">
		<div class="input">
			<label for="nuevaEditorial">Nombre nueva editorial:</label>
			<input type="text" name="nuevaEditorial" placeholder="Nombre editorial">
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