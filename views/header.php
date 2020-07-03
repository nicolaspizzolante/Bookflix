<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/estilos.css">
	<link rel="stylesheet" href="fontawesome/css/all.min.css">
	<script src="https://kit.fontawesome.com/c7cd9c5077.js" crossorigin="anonymous"></script>
	<title>Bookflix</title>
</head>
<body>
<?php if($autenticador->estaLogeado()){ ?>
	<div class="header">
		<div class="container">
			<ul class="nav">
				<li>
					<?php if($autenticador->esAdmin()){ ?>
						<a href="index.php" id="nav-logo"><img src="./img/logoadmin.png" alt=""></a>
					<?php }else{?>
						<a href="index.php" id="nav-logo"><img src="./img/logo.png" alt=""></a>
					<?php }?>
				</li>
				<li id="form-buscar">
					<form action="buscar.php" id="formulario-buscar" onsubmit="return validarBusqueda(this);">
						<input id="buscar" placeholder="Buscar... " type="text" class="buscar" name="busqueda" value="<?php if(isset($_GET['busqueda'])){ echo $_GET['busqueda'];} ?>">
					</form>
				</li> 
				<li>
					<a href="muro.php">Perfil</a>
				</li>
				
				<?php if(!$autenticador->esAdmin()){ ?>
				<li>
					<a href="verListadoTrailers.php">Ver trailers</a>
				</li>
				<li><a href="historial.php">Historial</a></li>
				<li><a href="verListadoLibros.php">Ver listado de libros</a></li>
				<?php } ?>
				<?php if($autenticador->esAdmin()){ ?>
					<li><a href="">Opciones de Admin</a>
						<ul class="dropdown-1">
							<li><a href="historial.php">Historial</a></li>
							<li><a href="cargarTrailer.php">Cargar Trailer</a></li>
							<li><a href="cargarmetadatos.php">Cargar Metadatos</a></li>
							<li><a href="cargarnovedad.php?tituloNovedad=<?php echo ''?>&descripcionNovedad=<?php echo ''?>">Cargar Novedad</a></li>
							<li><a href="altaautor.php?validar=<?php echo 1?>&idlibro=<?php echo 0?>">Cargar Autor</a></li>
							<li><a href="altaeditorial.php?validar=<?php echo 1?>&idlibro=<?php echo 0?>">Cargar Editorial</a></li>
							<li><a href="altagenero.php?validar=<?php echo 1?>&idlibro=<?php echo 0?>">Cargar Genero</a></li>
							<li><a href="verlistadolibros.php">Ver Listado de Libros</a></li>
							<li><a href="verlistadonovedades.php">Ver Listado de Novedades</a></li>
							<li><a href="verListadoTrailers.php">Ver Listado de Trailers</a></li>
						</ul>
					</li>
				<?php } ?>
				<li>
					<a href="cerrarsesion.php">Cerrar Sesion</a>
				</li>
			</ul>
		</div>
	</div>
<?php }// var_dump($_SESSION); ?>