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
	<h2><?php echo $libro['titulo']?></h2>
	
		<?php $indice = 0;?>
	<?php while ($cap = $libro_pdf->fetch_assoc()){?>
	<ul>
		<li><a href="leerLibro.php?id='<?php echo $cap['id']?>'">Capitulo <?php echo $indice+=1?></a></li>
	</ul>
		<?php } ?>	

	

<?php include 'views/footer.php'; ?>