<?php
    include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}
	
    include 'views/header.php';	
	$busqueda_sin_trim = $_GET['busqueda'];
	$busqueda = trim($busqueda_sin_trim);
    $conexion = conectar();

    //id del autor para usarlo en la consulta de la busqueda
	$sql = "SELECT id, nombre FROM autores WHERE nombre like '$busqueda%'";
	$autor = $conexion->query($sql)->fetch_assoc();
    $autor_id = $autor['id'];
    $autor_nombre = $autor['nombre'];
    

    //id del genero para usarlo en la consulta de la busqueda
	$sql = "SELECT id, nombre FROM generos WHERE nombre like '$busqueda%'";
	$genero = $conexion->query($sql)->fetch_assoc();
    $genero_id = $genero['id'];
    $genero_nombre = $genero['nombre'];
    

    //id de la editorial para usarlo en la consulta de la busqueda
	$sql = "SELECT id, nombre FROM editoriales WHERE nombre like '$busqueda%'";
	$editorial = $conexion->query($sql)->fetch_assoc();
    $editorial_id = $editorial['id'];
    $editorial_nombre = $editorial['nombre'];
    

   
	
	//para la paginacion 
    $pagina = isset($_GET['p']) ? (int)$_GET['p'] : 1; 
	$librosPorPagina = 4;

	$inicio = ($pagina > 1) ? ($pagina * $librosPorPagina - $librosPorPagina) : 0;
    
    $sql = "SELECT id, titulo, sinopsis, isbn, autor_id, genero_id, editorial_id, fecha_de_subida
					FROM libros
					WHERE titulo like '$busqueda%' or autor_id ='$autor_id' or genero_id = '$genero_id' or editorial_id = '$editorial_id'";
					
    $resultados = $conexion->query($sql);
	$total_libros = $resultados->num_rows;
	$numero_paginas = ceil($total_libros / $librosPorPagina);


	//paginacion
	$sql = "SELECT id, titulo, sinopsis, isbn, autor_id, genero_id, editorial_id, fecha_de_subida
					FROM libros
					WHERE titulo like '%$busqueda%' or autor_id ='$autor_id' or genero_id = '$genero_id' or editorial_id = '$editorial_id' LIMIT $inicio, $librosPorPagina";
					
    $resultados = $conexion->query($sql);
	
?>

<div class="container">
	<!-- Libros buscados -->
	<div class="publicaciones">
        <h2>Resultados de la busqueda (<?php echo $total_libros ?>):</h2>
		<?php if ($resultados->num_rows == 0) { ?>
 			<h3>No se encontraron resultados para: <?php echo $busqueda; ?></h3>
 		<?php } ?>
      <?php while ($libro = $resultados->fetch_assoc()){ ?>
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

						<div class="input">
							<a href="cargarLibro.php?id=<?php echo $id_libro;?>"><input type="button" value="Cargar libro"></a>
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
<?php if ($resultados->num_rows != 0) { ?>	
	<!-- paginacion -->
	<div class="paginacion">
		<ul>
			<?php if($pagina == 1){ ?>
				<li class="disabled">&laquo;</li>
			<?php } else { ?>
				<a href="buscar.php?p=<?php echo $pagina - 1; ?>&busqueda=<?php echo $busqueda; ?>"><li>&laquo;</li></a>
			<?php } ?>

			<?php for($i = 1; $i<=$numero_paginas; $i++){ ?>
				<?php if ($i == $pagina){ ?>
					<a href="buscar.php?p=<?php echo $i; ?>&busqueda=<?php echo $busqueda; ?>"><li class="actual"><?php echo $i; ?></li></a>
				<?php } else { ?>
					<a href="buscar.php?p=<?php echo $i; ?>&busqueda=<?php echo $busqueda; ?>"><li><?php echo $i; ?></li></a>
				<?php } ?>
			<?php } ?>
			
			<?php if($pagina == $numero_paginas){ ?>
				<li class="disabled">&raquo;</li>
			<?php } else { ?>
				<a href="buscar.php?p=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>"><li>&raquo;</li></a>
			<?php } ?>
		</ul>
	</div>
<?php }?>	
</div>    
<?php include 'views/footer.php'; ?>