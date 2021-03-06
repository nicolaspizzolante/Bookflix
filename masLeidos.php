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


	
	$sql = "SELECT *, COUNT(*) FROM reportes_lecturas GROUP BY libro_id ORDER BY COUNT(*) DESC";
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
                <td style="text-align: center">Titulo del Libro</td>
                <td style="text-align: center">Lecturas sin finalizar</td>
				<td style="text-align: center">Lecturas finalizadas</td>
            </tr>
        <tbody>

	<?php
	

	while($h = $result->fetch_assoc()){
		$id = $h['libro_id'];
		$sql = "SELECT * FROM reportes_lecturas WHERE libro_id = '$id'";
		$res = $conexion->query($sql);
		//cuenta terminados para cada libro
		$terminados = 0;
		while($l = $res->fetch_assoc()){
			if($l['terminado']){
				$terminados = $terminados + 1;
			}
		}
		?>
		<?php /*$sql = "SELECT * FROM libros WHERE id = $id";
		$resultado = $conexion->query($sql);
		$lib = $resultado->fetch_assoc(); */
		?>
		
			<tr>
			<td><p><?php echo $h['nombre_libro']; ?></p></td>
			<td style="text-align:center;"><?php echo $h['COUNT(*)'] - $terminados?></td>
			<td style="text-align:center;"><?php echo $terminados?></td>
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