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

    <h1>Crear Perfil</h1>
    <form action="validarPerfil.php" method= "post">
        <div class="input">
		<label>Nombre nuevo perfil:</label>
            <input type="text" name="nombre" placeholder="Nombre">
        </div>
        <div class="input">
			<input type="submit" value="OK">
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