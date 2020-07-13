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

	$user_profile_id = $_SESSION['usuario']['perfil_id'];

	
	$sql = "SELECT *, COUNT(*) FROM historial GROUP BY libro_id ORDER BY COUNT(*) DESC";
	$result = $conexion->query($sql);
	$msg = $result->num_rows;
	if($msg == 0){
		$_SESSION['usuario']['errores'] = 'Ningun suscriptor ha realizado lecturas';
	}else{
	?>

<div class="container">
	<!-- Libros publicados -->
	<div class="publicaciones">
	<div style="text-align:center"><h1> Listado de libros ordenados por lecturas </h1></div>
	<table class = "table table-striped table-dark">
            <tr>
                <td style="text-align: center">Libro</td>
                <td style="text-align: center">Lecturas</td>
            </tr>
        <tbody>

	<?php

	while($h = $result->fetch_assoc()){
		$id = $h['libro_id'];
		//$sql = "SELECT DISTINCT * FROM libros WHERE id = '$id'";
		//var_dump($r->fetch_assoc()['id']);
		?>
		<tr>
			<td><a href="libro.php?id=<?php echo $id?>"><?php echo $autenticador->retornarTitulo($id); ?></a></td>
			<td style="text-align:center;"><?php echo $h['COUNT(*)']?></td>
		</tr>
		<?php 
	}
?>	
</tbody>
</table>
	</div>
	<div class="editar-perfil">
	<a href="verReportes.php"><input type="button" value="Volver"></a>
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
		
<?php  include 'views/footer.php'; ?>