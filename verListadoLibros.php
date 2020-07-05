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
					<a href="libro.php?id=<?php echo $libro['id'] ?>" class="foto-link">
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
							<a href="libro.php?id=<?php echo $libro['id'] ?>"> <?= $libro['titulo'] ?> </a>
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

					<?php if($autenticador->esAdmin()){?>
					<div class="input">
						<button id="btn-borrar" onClick="confirmation('<?php echo $id_libro?>')">Eliminar libro</button>
					</div>
					<?php }?>
		</article>

		<?php } ?>
						
	</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
function confirmation($identLibro){
//$(".boton_eliminar").on("click", function(){
	Swal.fire({
		title: '¿Seguro?',
		text: "¡Esta acción no se puede revertir!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: '¡Sí, borrar!',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.value) {
			$.ajax({
				url: "borrarLibro.php?idLibro=" + $identLibro,
				context: document.body
			}).done(() => {
				Swal.fire(
					'¡Borrado!',
					'El libro fue borrado con exito',
					'success'
				).then(() =>{
					window.location.href = "verListadoLibros.php";
				});
			});
		}
	})
//});
}

</script>

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