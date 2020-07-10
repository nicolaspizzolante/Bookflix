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

				<div class="botones">
					<button class="btn-eliminar"><a href="bajaperfil.php?id=<?= $perfil['id'] ?>&usuario_id=<?= $perfil['usuario_id'] ?>">Eliminar</a></button>
				</div>
			</div>
		<?php } ?>
	</div>

	<button class="btn-success"><a href="altaperfil.php">Crear nuevo perfil</a></button>


	<?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" class="errores_mensaje" style="display:block;">
			<?php 
				echo $_SESSION['errores']; 
				unset($_SESSION['errores']);
			?>
		</ul>
	<?php endif ?>
</div>

<?php include 'views/footer.php'; ?>
