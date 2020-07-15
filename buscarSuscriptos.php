<?php


include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}
include 'views/header.php';

$conexion = conectar();


$fechaDesde = $_POST['fechaDesde'];
$fechaHasta = $_POST['fechaHasta'];

date_default_timezone_set('America/Argentina/Buenos_Aires');



if(($fechaHasta == '') || ($fechaDesde == '')){
	$_SESSION['errores'] = "<li>Las fechas no pueden estar vacias</li>";
	header("Location: suscriptosEntreFechas.php");
}elseif ($fechaDesde>$fechaHasta) {
	$_SESSION['errores'] = "<li>La primera fecha debe ser anterior a la segunda</li>";
	header("Location: suscriptosEntreFechas.php");
}else{
	$sql = "SELECT * FROM reportes_usuarios WHERE fecha >= '$fechaDesde' and fecha <= '$fechaHasta'";
	$resultado = $conexion->query($sql);
	if($resultado->num_rows == 0){
		$_SESSION['errores'] = "<li>No existen usuarios registrados entre las fechas ingresadas</li>";
		header("Location: suscriptosEntreFechas.php");
	}else{?>
	<div class="container">
	<!-- Libros publicados -->
	<div class="publicaciones">

	<table class = "table table-striped table-dark">
	<div style="text-align:center"><h1> Usuarios suscriptos entre el <?php echo $fechaDesde?> y el <?php echo $fechaHasta?></h1></div>
            <tr>
                <td style="text-align: center">Nombre del usuario</td>
				<td style="text-align: center">Tipo de plan</td>
				<td style="text-align: center">Perfiles activos</td>
                <td style="text-align: center">Fecha de suscripcion</td>
            </tr>
        <tbody>
	<?php
		while($usuarios = $resultado->fetch_assoc()){
			?>
			<tr>
				<td><p> <?php echo $usuarios['nombre_usuario']?></p></td>
				<td><p><?php if($usuarios['es_premium']){
					echo "Premium";
					}else{
						echo "Basico";
					}?>
				</p></td>
				<td><p> <?php echo $usuarios['cant_perfiles_activos']?></p></td>
				<td style="text-align:center;"><?php echo $usuarios['fecha']?></td>
			</tr>
		
		<?php
		}
		?>
		</table>
	</div>
	<div class="editar-perfil">
	<a href="suscriptosEntreFechas.php"><input type="button" value="Volver"></a>
</div>
	</div>
	<?php
	}

}

include 'views/footer.php';
?>