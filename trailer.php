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
		<img id="imagen-novedad" src="mostrarImagenNovedad.php?id=<?php echo $id ?>">
	<?php } ?>

	<article class="libro">
	<div class="info">
	<div class="titulo">
	<p id="descripcion-novedad"><?php echo $trailer['descripcion']?></p>
	</div>
	</div>
	</article>

	<?php if($autenticador->esAdmin()) {?>
    	
    	<button id="btn-borrar">Eliminar</button>
	<?php } ?>
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