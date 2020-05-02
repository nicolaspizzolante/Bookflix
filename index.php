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

</div>

<?php include 'views/footer.php'; ?>