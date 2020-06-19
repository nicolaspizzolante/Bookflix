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
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	$conexion = conectar();

	$pagina = isset($_GET['p']) ? (int)$_GET['p'] : 1; 
	$librosPorPagina = 5;

	$inicio = ($pagina > 1) ? ($pagina * $librosPorPagina - $librosPorPagina) : 0;

	$id = $_SESSION['usuario']['id'];
	$sql = "SELECT * FROM libros";
	$total_libros = $conexion->query($sql);
	$total_libros = $total_libros->num_rows;
	
	$numero_paginas = ceil($total_libros / $librosPorPagina);

	$paginacion = !($total_libros < $librosPorPagina);
?>	
  <div class="container">
	<?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" style="display:block;">
			<?php 
				echo $_SESSION['errores']; 
				unset($_SESSION['errores']);
			?>
		</ul>
	<?php endif ?>

    <?php if (isset($_SESSION['exito'])): ?>
		<ul id="exito" style="display:block;">
			<?php 
				echo $_SESSION['exito']; 
				unset($_SESSION['exito']);
			?>
		</ul>
	<?php endif ?>

	<!-- Libros publicados -->
	<div class="publicaciones">
		<?php
			$sql = "SELECT * FROM libros 
							ORDER BY fecha_de_subida DESC 
							LIMIT $inicio, $librosPorPagina";

			$libros = $conexion->query($sql);
			$msg = $libros->num_rows;

			

			if($msg == 0){
				$_SESSION['usuario']['errores'] = 'No hay libros para mostrar';
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

		<?php while ($libro = $libros->fetch_assoc()){ 
			?>
			<article class="libro">
				<?php $id_libro = $libro['id'];?>
				<div class="foto">
					<!-- Consulta para saber si tiene imagen -->
					<?php 
						$sql = "SELECT id FROM libros WHERE id = $id_libro and imagen is not null and trim(imagen) <> ''";
						$imagen = $conexion->query($sql);
						$tieneImagen = $imagen->num_rows;
					?>		
					<a class="foto-link">
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
							<?php 
								if($libro['capitulos']<=1){
									$sql = "SELECT * FROM libros_pdf WHERE libro_id = '$id_libro'";
									$r = $conexion->query($sql);
									$l = $r->fetch_assoc();
								?> <?php if(($libro['subidos']>0)and (($autenticador->esAdmin()) or ((substr($l['fecha_publicacion'],0,16)<=date('Y-m-d H:i')) and (substr($l['fecha_vencimiento'],0,16) > date('Y-m-d H:i'))))){?>
									<a href="leerLibro.php?id=<?php echo $l['id']?>" class="titulo-libro"><?php echo $libro['titulo']; ?></a>
								<?php }else{
									if($libro['subidos']>0){?>
									<a href="#" class="titulo-libro"><?php echo $libro['titulo']; ?></a>
								<?php }}?>
									
								<?php }else{ if($libro['subidos']>0){?>
									<a href="perfilLibro.php?id=<?php echo $libro['id']?>&selector=<?php echo 1?>" class="titulo-libro"><?php echo $libro['titulo']; ?></a>
								<?php }else{?>
									<a href="#" class="titulo-libro"><?php echo $libro['titulo']; ?></a>
								<?php }?>

							
								<?php }?>
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
						
						<?php if($libro['subidos']==0){ //Si no se cargó nada?>

							<div class="input">
								<a href="cargarCapitulo.php?id=<?php echo $id_libro;?>"><input type="button" value="Cargar capitulo"></a>
							</div>

							<div class="input">
								<a href="cargarLibro.php?id=<?php echo $id_libro;?>"><input type="button" value="Cargar libro"></a>
							</div>
						<?php }elseif (($libro['capitulos'] > 1) and ($libro['capitulos'] > $libro['subidos'])) { ?>
							<div class="input">
								<a href="cargarCapitulo.php?id=<?php echo $id_libro;?>"><input type="button" value="Cargar capitulo"></a>
							</div>
						<?php }?>
						
							
				<?php
            if($libro['subidos'] >= 1){
                if($libro['capitulos'] == 1){ //El libro no es subido por capitulos
                    $sql = "SELECT id FROM libros_pdf WHERE libro_id = '$id_libro'";
                    $result = mysqli_query($conexion, $sql);
                    $pdf_id = $result->fetch_assoc();
                ?>
                    <div class="input">
                    <a href="modificarFechasPublicacionVencimiento.php?id=<?php echo $pdf_id['id']?>"><input type="button" value="Editar fechas"></a>
                    </div>
                <?php }else{?>
                    <div class="input">
                    <a href="perfilLibro.php?id=<?php echo $id_libro?>&selector=<?php echo 0?>"><input type="button" value="Editar fechas"></a>
                    </div>
                <?php }?>
            <?php }else{?>
                <div class="input">
                    <a href="#"><input type="button" value="Editar fechas"></a>
                    </div>
            <?php }?>



						<?php 
							//consulta para saber si tiene trailer
							$consulta = "SELECT * FROM trailers WHERE id_libro = $id_libro";
							$aux =  $conexion->query($consulta);
							
						?> 
						<?php if($aux->num_rows === 0){?>
							<div class="input">
								<a href="cargarTrailer.php?id_libro=<?php echo $id_libro;?>"><input type="button" value="Cargar Trailer"></a>
							</div>
						<?php }else{?>
							
							<div class="input ver-trailer">
								<a href="trailer.php?id=<?php echo $id_libro; ?>"><input type="button" value="Ver Trailer"></a>
							</div>
						<?php }?>	
							
					<!--
						<div class="input">
							<a href=""><input type="submit" value="Eliminar"></a>
						</div> 

						<div class="input">
							<a href=""><input type="submit" value="Subir"></a>
						</div>
					-->	
				<?php } //end if es admin?>


				</div>
				

		</article>
		<?php } ?>
						
	</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>

$('a[href*="#"]').on("click", function(){
	Swal.fire({
		title: 'Aviso',
		text: "Aún no se ha cargado este libro",
		confirmButtonColor: '#3085d6',
		confirmButtonText: 'Aceptar',
	})
});

</script>

	<?php if($paginacion){?>
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
			<?php }?>
  </div>

		
<?php  include 'views/footer.php'; ?>