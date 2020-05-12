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
    <h1>Alta autor</h1>
    <form action="validarAutor.php?validar=<?php echo $val?>" onsubmit= "return validarAutor(this);" method= "post">
        <div class="input">
            <input type="text" name="autor" placeholder="Nuevo Autor">
        </div>
        <div class="input">
			<input type="submit">
		</div>
    </form>

    <ul id="errores" style="display:none"></ul>

	<?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" style="display:block;">
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