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
			<input type="text" name="nombre" placeholder="Nombre" value="<?php if(isset($_GET['nombre'])){ echo $_GET['nombre'];} ?>">
		</div>
		
		<div class="input">
			<input type="text" name="apellido" placeholder="Apellido" value="<?php if(isset($_GET['apellido'])){ echo $_GET['apellido'];} ?>">
		</div>

		<div class="input">
			<input type="text" name="email" placeholder="Email" value="<?php if(isset($_GET['email'])){ echo $_GET['email'];} ?>">
		</div>
		
		
		<div class="input">
			<input type="password" name="contrasenia" placeholder="Contraseña" value="<?php if(isset($_GET['contrasenia'])){ echo $_GET['contrasenia'];} ?>">
		</div>

		<div class="input">
			<input type="password" name="confirmar_pass" placeholder="Confirmar" value="<?php if(isset($_GET['confirmar'])){ echo $_GET['confirmar'];} ?>">
		</div>

		<p class="p-registro">Datos de la tarjeta</p>
		
		<div style="display:flex; flex-direction:row; width:250px;">
			<div class="input">
				<input style="width:175px; margin-right:5px;" type="text" name="numero_tarjeta" placeholder="Numero" value="<?php if(isset($_GET['numero_tarjeta'])){ echo $_GET['numero_tarjeta'];} ?>">
			</div>
			<div class="input" >
				<input style="width:70px;" type="text" name="codigo_tarjeta" placeholder="Codigo" value="<?php if(isset($_GET['codigo_tarjeta'])){ echo $_GET['codigo_tarjeta'];} ?>">
			</div>
		</div>

		<div style="display:flex; flex-direction:row; width:250px;">
			<div class="input">
				<input style="width:122.5px; margin-right:5px;" type="text" name="mes_vencimiento" placeholder="Mes vencimento" value="<?php if(isset($_GET['mes_vencimiento'])){ echo $_GET['mes_vencimiento'];} ?>">
			</div>

			<div class="input">
				<input style="width:122.5px;" type="text" name="anio_vencimiento" placeholder="Año vencimiento" value="<?php if(isset($_GET['anio_vencimiento'])){ echo $_GET['anio_vencimiento'];} ?>">
			</div>
		</div>

		<div class="input">
			<input type="text" name="nombre_tarjeta" placeholder="Nombre y apellido" value="<?php if(isset($_GET['nombre_tarjeta'])){ echo $_GET['nombre_tarjeta'];} ?>">
		</div>

		<div class="input">
			<input id="submit-btn" type="submit" value="Ok">
			<a href="login.php" id="btn-cancelar">Cancelar</a>
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


<script>

	if(window.location.href.indexOf("?") !== 0){
		history.pushState({}, null, "registrarse.php");
	}

</script>