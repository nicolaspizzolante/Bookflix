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

	<h1>Registrarse:</h1>
	<form action="validarRegistro.php" onsubmit="return validarRegistro(this);" method="post">
		
		<div class="input">
			<input type="text" name="nombre" placeholder="Nombre">
		</div>
		
		<div class="input">
			<input type="text" name="apellido" placeholder="Apellido">
		</div>

		<div class="input">
			<input type="text" name="email" placeholder="Email">
		</div>
		
		<div class="input">
			<input type="password" name="contrasenia" placeholder="Contraseña">
		</div>

		<div class="input">
			<input type="password" name="confirmar_pass" placeholder="Confirmar">
		</div>

		<div class="input">
			<input type="number" name="numero_tarjeta" placeholder="Numero de tarjeta">
		</div>

		<div class="input">
			<input type="text" name="codigo_tarjeta" placeholder="Codigo de tarjeta">
		</div>

		<div class="input">
			<input type="text" name="mes_vencimiento" placeholder="Mes">
		</div>

		<div class="input">
			<input type="text" name="anio_vencimiento" placeholder="Año">
		</div>

		<div class="input">
			<input type="text" name="nombre_tarjeta" placeholder="Nombre y apellido de tarjeta">
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

</div>
	
<?php include 'views/footer.php'; ?> 