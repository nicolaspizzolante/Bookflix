<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}
	$conexion = conectar();

	include 'views/header.php';
?>

<?php
	$idlibro = $_GET['id'];

	$sql = "SELECT * FROM libros_pdf WHERE libro_id = '$idlibro'";
	$libro_pdf = $conexion->query($sql);
	
	$sql = "SELECT titulo FROM libros WHERE id = '$idlibro'";
	$resultado = $conexion->query($sql);
	$libro = $resultado->fetch_assoc();
?>
<style>
h2{
	color:#c6071b;
	text-align:center
}
</style>
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

	
		<?php } ?>	

	

<?php include 'views/footer.php'; ?>