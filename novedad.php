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
    <h2><?php echo $novedad['titulo'] ?></h2>

    <p><?php echo $novedad['descripcion']?></p>

	<?php if($autenticador->esAdmin()) {?>
    	<button><a href="modificarnovedad.php?id=<?php echo $novedad['id'] ?>">Editar</a></button>
    	<button><a href="eliminarnovedad.php?id=<?php echo $novedad['id'] ?>">Eliminar</a></button>
	<?php } ?>
</div>