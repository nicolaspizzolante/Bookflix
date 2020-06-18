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
						
						<?php if(($autenticador->esAdmin()) or (($cap['fecha_publicacion']<=date('Y-m-d H:i')) and (($cap['fecha_vencimiento'] > date('Y-m-d H:i'))or ($cap['fecha_vencimiento'] == '0000-00-00 00:00:00')))){?>
							<a href="leerLibro.php?id='<?php echo $cap['id']?>'">Capitulo <?php echo $indice+=1?></a>
						<?php }else {?>
							<a href="#">Capitulo <?php echo $indice+=1?></a>
						<?php }?>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>

$('a[href*="#"]').on("click", function(){
	Swal.fire({
		title: 'Aviso',
		text: "El capitulo no está disponible",
		confirmButtonColor: '#3085d6',
		confirmButtonText: 'Aceptar',
	})
});

</script>

<?php include 'views/footer.php'; ?>