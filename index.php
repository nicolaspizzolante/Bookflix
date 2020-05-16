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
	$novedadesPorPagina = 5;

	$inicio = ($pagina > 1) ? ($pagina * $novedadesPorPagina - $novedadesPorPagina) : 0;

	$id = $_SESSION['usuario']['id'];
	$sql = "SELECT * FROM novedades";
	$total_novedades = $conexion->query($sql);
	$total_novedades = $total_novedades->num_rows;
	
	$numero_paginas = ceil($total_novedades / $novedadesPorPagina);
?>	



<div class="container">
	<!-- Novedades publicadas -->
	<div class="publicaciones">
		<?php
			$sql = "SELECT * FROM novedades 
							ORDER BY fecha DESC 
							LIMIT $inicio, $novedadesPorPagina";

			$novedades = $conexion->query($sql);
			$msg = $novedades->num_rows;

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
		<h2>Ultimas novedades</h2>
</div>

				<!-- VER NOVEDADES -->
	<?php while ($novedad = $novedades->fetch_assoc()){ ?>
			<article class="libro">
				<?php $id_novedad = $novedad['id'];?>
					<a href="novedad.php?id=<?php echo $novedad['id']; ?>"></a>	
				<div class="info">
					<div class="titulo">
						<h2>
							<a href="novedad.php?id=<?php echo $novedad['id']; ?>" class="titulo-libro"><?php echo $novedad['titulo']; ?></a>
						</h2>
					</div>

					<div><span></span><span><?php echo $novedad['descripcion']; ?></span>
				    </div>



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
				<a href="index.php?p=<?php echo $pagina - 1; ?>"><li>&laquo;</li></a>
			<?php } ?>

			<?php for($i = 1; $i<=$numero_paginas; $i++){ ?>
				<?php if ($i == $pagina){ ?>
					<a href="index.php?p=<?php echo $i; ?>"><li class="actual"><?php echo $i; ?></li></a>
				<?php } else { ?>
					<a href="index.php?p=<?php echo $i; ?>"><li><?php echo $i; ?></li></a>
				<?php } ?>
			<?php } ?>
			
			<?php if($pagina == $numero_paginas){ ?>
				<li class="disabled">&raquo;</li>
			<?php } else { ?>
				<a href="index.php?p=<?php echo $pagina + 1; ?>"><li>&raquo;</li></a>
			<?php } ?>
		</ul>
	</div>
  </div>

<?php include 'views/footer.php'; ?>