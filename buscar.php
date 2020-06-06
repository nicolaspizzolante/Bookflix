<?php
    include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}
	
    include 'views/header.php';	
    $busqueda = $_GET['busqueda'];
    $conexion = conectar();

    //id del autor para usarlo en la consulta de la busqueda
	$sql = "SELECT id, nombre FROM autores WHERE nombre like '%$busqueda%'";
	$autor = $conexion->query($sql)->fetch_assoc();
    $autor_id = $autor['id'];
    $autor_nombre = $autor['nombre'];
    var_dump($autor_nombre);

    //id del genero para usarlo en la consulta de la busqueda
	$sql = "SELECT id, nombre FROM generos WHERE nombre like '%$busqueda%'";
	$genero = $conexion->query($sql)->fetch_assoc();
    $genero_id = $genero['id'];
    $genero_nombre = $genero['nombre'];
    var_dump($genero_id);

    //id de la editorial para usarlo en la consulta de la busqueda
	$sql = "SELECT id, nombre FROM editoriales WHERE nombre like '%$busqueda%'";
	$editorial = $conexion->query($sql)->fetch_assoc();
    $editorial_id = $editorial['id'];
    $editorial_nombre = $editorial['nombre'];
    var_dump($editorial_id);

    //busco por nombre
    $sql = "SELECT id, titulo, sinopsis, isbn, autor_id,    genero_id, editorial_id
					FROM libros 
                    WHERE titulo like '%$busqueda%'";
    $resultados= $conexion->query($sql);
    
    
    $sql = "SELECT id, titulo, sinopsis, isbn, autor_id, genero_id, editorial_id
					FROM libros
                    WHERE titulo like '%$busqueda%' or autor_id ='$autor_id' or genero_id = '$genero_id' or editorial_id = '$editorial_id'";
    $resultados = $conexion->query($sql);
    $total_libros = $resultados->num_rows;
    var_dump('A ver');
    var_dump($total_libros);    
?>

<div class="container">
	<!-- Libros buscados -->
	<div class="publicaciones">
        <h2>Resultados de la busqueda</h2>
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
</div>    