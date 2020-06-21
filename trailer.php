<?php
    include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}

	include 'views/header.php';

    $id = $_GET['id']; //id del libro al que pertenece el trailer
    
	$db = conectar();
	$sql = "SELECT * FROM trailers WHERE id_libro = '$id'";
	$resultado = $db->query($sql);

    $trailer = $resultado->fetch_assoc();
?>

<div class="container">
	
	<?php if(isset($_SESSION['exito'])){ ?>
		<ul id="exito" class="asd">
				<?php 
					echo $_SESSION['exito']; 
					unset($_SESSION['exito']);
				?>
		</ul>
	<?php } ?>

	
    <h2 id="titulo-novedad"><?php echo $trailer['titulo'] ?></h2>
	
	<div style="margin-bottom: 10px">
	<p id="fecha-novedad"><?php echo  date("d/m/Y H:i", strtotime($trailer['fecha'])) . "hs"; ?></p>
	</div>
	<?php if($trailer['foto_video'] != ''){ ?>
		<img id="imagen-novedad" src="mostrarImagenTrailer.php?id=<?php echo $trailer['id_libro'] ?>">
	<?php } ?>

	<article class="libro">
	<div class="info">
	<div class="titulo">
	<p id="descripcion-novedad"><?php echo $trailer['descripcion']?></p>
	</div>
	</div>
	</article>
	<div class="ops-trailer">

		<?php 
			$id_libro= $trailer['id_libro'];
			$sql= "SELECT id FROM libros WHERE id= '$id_libro'";
			$aux = $db->query($sql);
			
		?>
		<?php if($aux->num_rows > 0){?>
			<div class="input">
				<a href="libro.php?id=<?php echo $trailer['id_libro']?>"><input type="button" value="Ver libro"></a>
			</div>
		<?php } ?>

		<?php if($autenticador->esAdmin()) {?>
			
				<div class="input">
					<a href="modificarTrailer.php?id=<?php echo $trailer['id']?>&titulo_trailer=&descripcionTrailer=&verificar=<?php echo 0?>"><button id="btn-editar">Editar</button></a>
				</div>	
				
			<div class="input">
				<button id="btn-borrar">Eliminar</button>
			</div>	
		<?php } ?>
	</div>	
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

var idTrailer = <?php echo $id ?>;

$("#btn-borrar").on("click", function(){
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
				url: "eliminarTrailer.php?id=" + idTrailer,
				context: document.body
			}).done(() => {
				Swal.fire(
					'¡Borrado!',
					'El trailer fue borrado con exito',
					'success'
				).then(() =>{
					window.location.href = "verListadoLibros.php";
				});
			});
		}
	})
});

</script>