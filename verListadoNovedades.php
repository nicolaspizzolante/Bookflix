<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado() || !$autenticador->esAdmin()) {
		header('Location: login.php');
		exit;
	}

	include 'views/header.php';
?>

<?php
	$conexion = conectar();

	$pagina = isset($_GET['p']) ? (int)$_GET['p'] : 1; 
	$novedadesPorPagina = 15;

	$inicio = ($pagina > 1) ? ($pagina * $novedadesPorPagina - $novedadesPorPagina) : 0;

	$id = $_SESSION['usuario']['id'];
	$sql = "SELECT id FROM novedades";
	$totalNovedades = $conexion->query($sql);
	$totalNovedades = $totalNovedades->num_rows;
	
	$numero_paginas = ceil($totalNovedades / $novedadesPorPagina);
?>	
  <div class="container">
	<!-- Libros publicados -->
	<div class="publicaciones">
		<?php
			$sql = "SELECT id, titulo, fecha FROM novedades 
							ORDER BY fecha DESC 
							LIMIT $inicio, $novedadesPorPagina";

			$novedades = $conexion->query($sql);
			$msg = $novedades->num_rows;

			if($msg == 0){
				$_SESSION['usuario']['errores'] = 'No hay novedades para mostrar';
			?>	
			<?php } else {?>			

        <table class = "table table-striped table-dark">
            <tr>
                <td style="text-align: center">Titulo</td>
                <td style="text-align: center">Fecha</td>
            </tr>
        <tbody>
            <?php while ($novedad = $novedades->fetch_assoc()){ ?>
            <tr>
                <td ><a href="novedad.php?id=<?php echo $novedad['id']; ?>"><?php echo $novedad['titulo']; ?></a></td>
                <td style="text-align: center"><?php echo date("d/m/Y", strtotime($novedad['fecha'])); ?></td>
            </tr>
            <?php } ?>
        </tbody>
	    </table>
		
	</div>
			
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
		

	<!-- paginacion -->
	<div class="paginacion">
		<ul>
			<?php if($pagina == 1){ ?>
				<li class="disabled">&laquo;</li>
			<?php } else { ?>
				<a href="verListadonovedades.php?p=<?php echo $pagina - 1; ?>"><li>&laquo;</li></a>
			<?php } ?>

			<?php for($i = 1; $i<=$numero_paginas; $i++){ ?>
				<?php if ($i == $pagina){ ?>
					<a href="verListadonovedades.php?p=<?php echo $i; ?>"><li class="actual"><?php echo $i; ?></li></a>
				<?php } else { ?>
					<a href="verListadonovedades.php?p=<?php echo $i; ?>"><li><?php echo $i; ?></li></a>
				<?php } ?>
			<?php } ?>
			
			<?php if($pagina == $numero_paginas){ ?>
				<li class="disabled">&raquo;</li>
			<?php } else { ?>
				<a href="verListadonovedades.php?p=<?php echo $pagina + 1; ?>"><li>&raquo;</li></a>
			<?php } ?>
		</ul>
	</div>
  </div>

  <?php }?>

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

  </div>
  </div>



<?php include 'views/footer.php' ?>