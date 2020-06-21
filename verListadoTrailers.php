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
	$trailersPorPagina = 5;

	$inicio = ($pagina > 1) ? ($pagina * $trailersPorPagina - $trailersPorPagina) : 0;

	$id = $_SESSION['usuario']['id'];
	$sql = "SELECT * FROM trailers";
	$total_trailers = $conexion->query($sql);
	$total_trailers = $total_trailers->num_rows;
	
	$numero_paginas = ceil($total_trailers / $trailersPorPagina);

    $paginacion = !($total_trailers < $trailersPorPagina);
   
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
    <h1>Listado de trailers</h1>

	<!-- Libros publicados -->
	<div class="publicaciones">
		<?php
			$sql = "SELECT * FROM trailers 
							ORDER BY fecha DESC 
							LIMIT $inicio, $trailersPorPagina";

			$trailers = $conexion->query($sql);
			$msg = $trailers->num_rows;

			

			if($msg == 0){
				$_SESSION['usuario']['errores'] = 'No hay trailers para mostrar';
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

		<?php while ($trailer = $trailers->fetch_assoc()){ 
			?>
			<article class="libro">
				<?php $id_trailer = $trailer['id'];?>
				<div class="foto">
							
					<a class="foto-link" href="trailer.php?id=<?php echo $trailer['id_libro']; ?>">
                        <?php if($trailer['foto_video'] != ''){ ?>
		                <img id="" src="mostrarImagenTrailer.php?id=<?php echo $trailer['id_libro'] ?>">
	                    
						<?php }else {?>
							<img src="img/image.jpg">
						<?php } ?>
					</a>	
				</div>
				<div class="info">
					<div class="titulo">
						<h2> 
								
									<a href="trailer.php?id=<?php echo $trailer['id_libro']; ?>" class="titulo-libro"><?php echo $trailer['titulo']; ?></a>
									
						</h2>
					</div>





					<div><span>Fecha de subida: </span><span><?php echo $trailer['fecha']; ?></span>
				    </div>
                    <div class="descripcion-trailer">
                        <p><?php echo $trailer['descripcion']?></p>
                    </div>
					

					<div class="opciones">
							
	
                            <div class="input btn-trailer">
								<a href="trailer.php?id=<?php echo $trailer['id_libro']; ?>"><input type="button" value="Ver Trailer"></a>
							</div>

				</div>
				

		</article>
		<?php } ?>
						
	</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>



	<?php if($paginacion){?>
	<!-- paginacion -->
	<div class="paginacion">
		<ul>
			<?php if($pagina == 1){ ?>
				<li class="disabled">&laquo;</li>
			<?php } else { ?>
				<a href="verListadoTrailers.php?p=<?php echo $pagina - 1; ?>"><li>&laquo;</li></a>
			<?php } ?>

			<?php for($i = 1; $i<=$numero_paginas; $i++){ ?>
				<?php if ($i == $pagina){ ?>
					<a href="verListadoTrailers.php?p=<?php echo $i; ?>"><li class="actual"><?php echo $i; ?></li></a>
				<?php } else { ?>
					<a href="verListadoTrailers.php?p=<?php echo $i; ?>"><li><?php echo $i; ?></li></a>
				<?php } ?>
			<?php } ?>
			
			<?php if($pagina == $numero_paginas){ ?>
				<li class="disabled">&raquo;</li>
			<?php } else { ?>
				<a href="verListadoTrailers.php?p=<?php echo $pagina + 1; ?>"><li>&raquo;</li></a>
			<?php } ?>
		</ul>
	</div>
			<?php }?>
  </div>

		
<?php  include 'views/footer.php'; ?>