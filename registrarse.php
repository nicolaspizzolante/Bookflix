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

	<h1>Registrarse</h1>
	<form action="validarRegistro.php" onsubmit="return validarRegistro(this);" method="post">
		
		<p class="p-registro">Datos Personales</p>	
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

		<p class="p-registro">Datos de la tarjeta</p>
		
		<div style="display:flex; flex-direction:row; width:250px;">
			<div class="input">
				<input style="width:175px; margin-right:5px;" type="text" name="numero_tarjeta" placeholder="Numero">
			</div>
			<div class="input" >
				<input style="width:70px;" type="text" name="codigo_tarjeta" placeholder="Codigo">
			</div>
		</div>

		<div style="display:flex; flex-direction:row; width:250px;">
			<div class="input">
				<input style="width:122.5px; margin-right:5px;" type="text" name="mes_vencimiento" placeholder="Mes vencimento">
			</div>

			<div class="input">
				<input style="width:122.5px;" type="text" name="anio_vencimiento" placeholder="Año vencimiento">
			</div>
		</div>

		<div class="input">
			<input type="text" name="nombre_tarjeta" placeholder="Nombre y apellido">
		</div>

		<div class="input">
			<input type="submit" value="Ok">
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