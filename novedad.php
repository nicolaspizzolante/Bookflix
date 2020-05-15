<?php
    include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}

	include 'views/header.php';

    $id = $_GET['id']; //id de la novedad
    
	$db = conectar();
	$sql = "SELECT * FROM novedades WHERE id = '$id'";
	$resultado = $db->query($sql);

    $novedad = $resultado->fetch_assoc();
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

    <h2 id="titulo-novedad"><?php echo $novedad['titulo'] ?></h2>

	<p id="fecha-novedad"><?php echo $novedad['fecha']; ?></p>

	<img id="imagen-novedad" src="mostrarImagenNovedad.php?id=<?php echo $id ?>">

	<p id="descripcion-novedad"><?php echo $novedad['descripcion']?></p>
	
	<?php if($autenticador->esAdmin()) {?>
    	<a href="modificarnovedad.php?id=<?php echo $novedad['id'] ?>"><button id="btn-editar">Editar</button></a>
    	<button id="btn-borrar">Eliminar</button>
	<?php } ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

var idNovedad = <?php echo $id ?>;

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
				url: "eliminarnovedad.php?id=" + idNovedad,
				context: document.body
			}).done(() => {
				Swal.fire(
					'¡Borrado!',
					'La novedad fue borrada con exito',
					'success'
				).then(() =>{
					window.location.href = "verlistadonovedades.php";
				});
			});
		}
	})
});

</script>