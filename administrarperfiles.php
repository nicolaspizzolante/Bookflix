<?php
    include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}

	include 'views/header.php';
    
    $usuario_id = $_SESSION['usuario']['id'];

	// traigo todos los perfiles del usuario
	$db = conectar();
	$sql = "SELECT * FROM perfiles WHERE usuario_id = '$usuario_id'";
    $resultado = $db->query($sql);

	$cant_perfiles = 0;
?>

<style>

.perfiles {
	display: flex;
	justify-content: center;
	flex-direction: column;
}

.perfil {
	width: 500px;
	padding: 20px;
	background: #151515;
	display: flex;
	justify-content: space-between;
	align-items:center;
	border-radius:3px;
	margin-bottom: 10px;
}

a {
	color: white !important;
}

</style>

<div class="container">

	<h1>Tus perfiles</h1>

	<div class="perfiles">
		<?php while ($perfil = $resultado->fetch_assoc()){ ?>
			<div class="perfil">
				<h3> <?= $perfil['nombre'] ?></h3>

				<button id="<?= $perfil['id'] ?>" class="btn-eliminar">Eliminar</button>

			</div>
		<?php $cant_perfiles++; } ?>
	</div>

	<?php if(($_SESSION['usuario']['es_premium'] && $cant_perfiles < 4) || (!$_SESSION['usuario']['es_premium'] && $cant_perfiles < 2)) { ?>
		<a href="altaperfil.php"><button class="btn-success">Crear nuevo perfil</button></a>
	<?php } ?>

	<?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" class="errores_mensaje" style="display:block;">
			<?php 
				echo $_SESSION['errores']; 
				unset($_SESSION['errores']);
			?>
		</ul>
	<?php endif ?>
</div>

<script>

	let cantPerfiles = <?= $cant_perfiles ?>;
	
	if(cantPerfiles == 1){
		$('.btn-eliminar').hide();
	}

	$('.btn-eliminar').on('click', function(){
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
					url: "bajaperfil.php?id=" + $(this)[0].id + "&usuario_id=<?=$_SESSION['usuario']['id'] ?>"
				}).done(() => {
					$(this).parent().fadeOut();
					Swal.fire(
						'¡Borrado!',
						'El perfil fue borrado con exito',
						'success'
					).then(() =>{
						window.location.href = "perfiles.php";
					});
				});
			}
		})
	});

</script>

<?php include 'views/footer.php'; ?>
