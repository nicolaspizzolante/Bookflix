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
				
					
					<div class="publicaciones" style="width:100%;">
						
					<a href=""><?php echo $libro['titulo']; ?></a>
					<p><?php echo $libro['isbn']; ?></p>
					<?php 
						$autor = $libro['autor_id'];
						$consulta = "SELECT nombre FROM autores WHERE id = $autor";
						$aux =  ($conexion->query($consulta));
						echo($aux->fetch_row())[0];
						?>	
					<p><?php 
						$editorial = $libro['editorial_id'];
						$consulta = "SELECT nombre FROM editoriales WHERE id = $editorial";
						$aux =  ($conexion->query($consulta));
						echo($aux->fetch_row())[0];
						?></p>
					<p><?php 
						$genero = $libro['genero_id'];
						$consulta = "SELECT nombre FROM generos WHERE id = $genero";
						$aux =  ($conexion->query($consulta));
						echo($aux->fetch_row())[0];
					
					?><p>
					<hr>

				</div>
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


		

<?php include 'views/footer.php'; ?>