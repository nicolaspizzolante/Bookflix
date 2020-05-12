<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/estilos.css">
	<link rel="stylesheet" href="fontawesome/css/all.min.css">
	<title>Bookflix</title>
</head>
<body>
<?php if($autenticador->estaLogeado()){ ?>
	<div class="header">
		<div class="container">
			<ul class="nav">
				<li>
					<a href="index.php" id="nav-logo"><img src="./img/logo.png" alt=""></a>
				</li>
				<li id="form-buscar">
					<form action="buscar.php" onsubmit="return validarBusqueda(this);">
						<input placeholder="Buscar... " type="text" class="buscar" name="busqueda">
					</form>
				</li>
				<li>
					<a href="muro.php">Perfil</a>
				</li>
				<?php if($autenticador->esAdmin()){ ?>
					<li><a href="">Opciones Adm</a>
						<ul class="dropdown-1">
							<li><a href="cargarmetadatos.php">Cargar Metadatos</a></li>
							<li><a href="cargarnovedad.php">Cargar Novedad</a></li>
							<li><a href="altaautor.php?validar=<?php echo 1?>">Cargar Autor</a></li>
							<li><a href="altaeditorial.php?validar=<?php echo 1?>">Cargar Editorial</a></li>
							<li><a href="altagenero.php?validar=<?php echo 1?>">Cargar Genero</a></li>
							<li><a href="verlistadolibros.php">Ver Listado de Libros</a></li>
						</ul>
					</li>
				<?php } ?>
				<li>
					<a href="cerrarsesion.php">Cerrar Sesion</a>
				</li>
			</ul>
		</div>
	</div>
<?php } //var_dump($_SESSION); ?>