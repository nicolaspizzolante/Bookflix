<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}

	include 'views/header.php';
?>

<?php
	$conexion = conectar();

	$pagina = isset($_GET['p']) ? (int)$_GET['p'] : 1; 
	$librosPorPagina = 5;

	$inicio = ($pagina > 1) ? ($pagina * $librosPorPagina - $librosPorPagina) : 0;

	$id = $_SESSION['usuario']['id'];
	$sql = "SELECT * FROM libros";
	$total_libros = $conexion->query($sql);
	$total_libros = $total_libros->num_rows;
	
	$numero_paginas = ceil($total_libros / $librosPorPagina);
?>	
  <div class="container">
	<!-- Libros publicados -->
	<div class="publicaciones">
		<?php
			$sql = "SELECT * FROM libros 
							ORDER BY fecha_de_subida DESC 
							LIMIT $inicio, $librosPorPagina";

			$libros = $conexion->query($sql);
			$msg = $libros->num_rows;

			if($msg == 0){
				$_SESSION['usuario']['errores'] = 'No hay publicaciones';
			}
		?>
		<h3>
			<?php if(isset($_SESSION['usuario']['errores'])){ ?>
				<h2 style="text-align:center; color:white;">
					<?php
						echo $_SESSION['usuario']['errores']; 
						$_SESSION['usuario']['errores'] = '';
					?>
				</h2>
			<?php } ?>
		</h3>

		<?php while ($libro = $libros->fetch_assoc()){ ?>
			<article class="libro">
				<?php $id_libro = $libro['id'];?>
				<div class="foto">
					<!-- Consulta para saber si tiene imagen -->
					<?php 
						$sql = "SELECT id FROM libros WHERE id = $id_libro and imagen is not null and trim(imagen) <> ''";
						$imagen = $conexion->query($sql);
						$tieneImagen = $imagen->num_rows;
					?>
		<!--perfilLibro.php?id=<?php //echo $libro['id']; ?>-->			
					<a href="#" class="foto-link">
						<?php if($tieneImagen){ ?>
							<img src="mostrarImagen.php?libro_id=<?php echo $id_libro?>">
						<?php } else {?>
							<img src="img/image.jpg">
						<?php } ?>
					</a>	
				</div>
				<div class="info">
					<div class="titulo">
						<h2>
						<!--perfilLibro.php?id=<?php// echo $id_libro;?>-->
							<a href="#" class="titulo-libro"><?php echo $libro['titulo']; ?></a>
						</h2>
					</div>

					<div><span>ISBN:</span><span><?php echo $libro['isbn']; ?></span>
				    </div>

					<?php 
						$autor_id = $libro['autor_id'];
						$consulta = "SELECT nombre FROM autores WHERE id = $autor_id";
						$aux =  $conexion->query($consulta);
					?>
					<div>
						<span><?php echo $aux->fetch_assoc()['nombre'];?></span>
					</div>

					<?php 
						$editorial_id = $libro['editorial_id'];
						$consulta = "SELECT nombre FROM editoriales WHERE id = $editorial_id";
						$aux =  $conexion->query($consulta)
					?>
					<div>
						<span><?php echo $aux->fetch_assoc()['nombre'];?></span>
					</div>	
					
					<?php 
						$genero_id = $libro['genero_id'];
						$consulta = "SELECT nombre FROM generos WHERE id = $genero_id";
						$aux =  $conexion->query($consulta);
					?>
					<div>
						<span><?php echo $aux->fetch_assoc()['nombre'];?></span>
					</div>	
					

				<?php if($autenticador->esAdmin()){ ?>

					<div><span>Fecha de subida: </span><span><?php echo $libro['fecha_de_subida']; ?></span>
				    </div>

					<div class="opciones">
						<!--
						<div class="input">
								<a href="perfilLibro.php?id=<?//php echo $id_libro;?>"><input type="submit" value="Ver"></a>
							</div>
						-->
						<div class="input">
							<a href="modificarMetadatos.php?id=<?php echo $id_libro;?>"><input type="submit" value="Editar"></a>
						</div>
					<!--
						<div class="input">
							<a href=""><input type="submit" value="Eliminar"></a>
						</div> 

						<div class="input">
							<a href=""><input type="submit" value="Subir"></a>
						</div>
					-->	
				<?php } ?>


				</div>
				

		</article>
		<?php } ?>
	</div>

	<!-- paginacion -->
	<div class="paginacion">
		<ul>
			<?php if($pagina == 1){ ?>
				<li class="disabled">&laquo;</li>
			<?php } else { ?>
				<a href="verListadoLibros.php?p=<?php echo $pagina - 1; ?>"><li>&laquo;</li></a>
			<?php } ?>

			<?php for($i = 1; $i<=$numero_paginas; $i++){ ?>
				<?php if ($i == $pagina){ ?>
					<a href="verListadoLibros.php?p=<?php echo $i; ?>"><li class="actual"><?php echo $i; ?></li></a>
				<?php } else { ?>
					<a href="verListadoLibros.php?p=<?php echo $i; ?>"><li><?php echo $i; ?></li></a>
				<?php } ?>
			<?php } ?>
			
			<?php if($pagina == $numero_paginas){ ?>
				<li class="disabled">&raquo;</li>
			<?php } else { ?>
				<a href="verListadoLibros.php?p=<?php echo $pagina + 1; ?>"><li>&raquo;</li></a>
			<?php } ?>
		</ul>
	</div>
  </div>

<?php include 'views/footer.php'; ?>