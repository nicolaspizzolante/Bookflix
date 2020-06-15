<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}
	$conexion = conectar();
	//Recibe 1 si muestra listado para lectura, o 0 si muestra listado para cambiar fechas de publicacion/vencimiento
	$selector = $_GET['selector'];

	$idlibro = $_GET['id'];

	$sql = "SELECT * FROM libros_pdf WHERE libro_id = '$idlibro'";
	$libro_pdf = $conexion->query($sql);
	
	$sql = "SELECT titulo FROM libros WHERE id = '$idlibro'";
	$resultado = $conexion->query($sql);
	$libro = $resultado->fetch_assoc();

	include 'views/header.php';
?>

<style>
h2{
	color:#c6071b;
	text-align:center
}
</style>

	<?php if($selector == 1){?>

	<h2><?php echo $libro['titulo']?> - Listado de capitulos</h2>
		<?php $indice = 0;?>
	<?php while ($cap = $libro_pdf->fetch_assoc()){?>

		<article class="capitulo">
				<div class="info">
					<div class="titulo">
						<h4>
						<a href="leerLibro.php?id='<?php echo $cap['id']?>'">Capitulo <?php echo $indice+=1?></a>
						</h4>
					</div>
				    </div>
				</div>
		</article>

	
		<?php } }else{?>
			<h2>Capitulos del libro: <?php echo $libro['titulo']?> - Seleccione para editar fechas de publicacion/vencimiento</h2>
			<?php $indice = 0;?>
			<?php while ($cap = $libro_pdf->fetch_assoc()){?>
				<article class="capitulo">
						<div class="info">
							<div class="titulo">
								<h4>
								<a href="modificarFechasPublicacionVencimiento.php?id=<?php echo $cap['id']?>">Capitulo <?php echo $indice+=1?></a>
								</h4>
							</div>
						    </div>
						</div>
				</article>
		<?php }}?>	

	

<?php include 'views/footer.php'; ?>