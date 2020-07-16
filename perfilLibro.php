<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	date_default_timezone_set('America/Argentina/Buenos_Aires');

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
	
	$sql = "SELECT id,titulo FROM libros WHERE id = '$idlibro'";
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

	<?php if($libro_pdf->num_rows == 0){
		header("Location: libro.php?id=$idlibro");
	}
?>
	<h2><?php echo $libro['titulo']?> - Listado de capitulos</h2>
		<?php $indice = 0;?>
	<?php while ($cap = $libro_pdf->fetch_assoc()){?>

		<article class="capitulo">
					<div class="titulo">
						<h4>
						<div class="lista-capitulo">
						<?php 
						if(((substr($cap['fecha_publicacion'],0,16)<=date('Y-m-d H:i')) and ((substr($cap['fecha_vencimiento'],0,16) > date('Y-m-d H:i'))or (($cap['fecha_vencimiento'] == '0000-00-00 00:00:00')or($cap['fecha_vencimiento'] == ''))))){?>
							<a href="leerLibro.php?id=<?php echo $cap['id']?>">Capitulo <?php echo $indice+=1?></a>
							<?php if($autenticador->esAdmin()){?>
						
								<div class="boton-eliminar-capitulo">
						    <button id="btn-borrar" onClick="confirmation('<?php echo $cap['id']?>','<?php echo $libro['id']?>')">Eliminar</button>
						</div>
        			
						<?php }?>
							
						<?php }else if(!$autenticador->esAdmin()){?>
							<a style="text-decoration:line-through" href="#">Capitulo <?php echo $indice+=1?></a> No disponible
						<?php }else{?>
							<a style="text-decoration:line-through" href="leerLibro.php?id=<?php echo $cap['id']?>">Capitulo <?php echo $indice+=1?></a> <p> No disponible</p>
							<div class="boton-eliminar-capitulo-nodisp">
						    <button id="btn-borrar" onClick="confirmation('<?php echo $cap['id']?>','<?php echo $libro['id']?>')">Eliminar</button>
						</div>
						<?php }?>
						</div>
						</h4>
						
					</div>
		</article>
		<?php } }else{?>
			<h2>Capitulos del libro: <?php echo $libro['titulo']?> </h2>
			<h2>Seleccione capitulo para modificarle sus fechas</h2>
			<?php $indice = 0;?>
			<?php while ($cap = $libro_pdf->fetch_assoc()){?>
				<article class="capitulo">
						<div class="info">
							<div class="titulo">
								<h4>
								<a href="modificarFechasPublicacionVencimiento.php?id=<?php echo $cap['id']?>&idLibro=<?php echo $idlibro?>">Capitulo <?php echo $indice+=1?></a>
								</h4>
							</div>
						    </div>
						</div>
				</article>
		<?php }}?>	

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
function confirmation($identPDF, $identLibro){
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
				url: "borrarLibro.php?idLibro=" + $identLibro+"&pdf_id=" + $identPDF,
				context: document.body
			}).done(() => {
				Swal.fire(
					'¡Borrado!',
					'El libro fue borrado con exito',
					'success'
				).then(() =>{
					window.location.href = "perfilLibro.php?id=" + $identLibro+"&selector=1";
				});
			});
		}
	})
}
</script>

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