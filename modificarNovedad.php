<?php

include 'autenticador.php';
$autenticador = new autenticador();

if (!$autenticador->estaLogeado() || !$autenticador->esAdmin()) {
    header('Location: login.php');
    exit;
}

include 'views/header.php';

$db = conectar();
$id = $_GET['id']; //id de novedad
$sql = "SELECT * FROM novedades WHERE id = '$id'";
$resultado = $db->query($sql);

$novedad = $resultado->fetch_assoc();

?>

<div class="container">
        <h1>Editar Novedad</h1>
        <form action="validarEdicionNovedad.php?id=<?php echo $id;?>" onsubmit="return validarNovedad(this);" method="post" enctype="multipart/form-data">
			<input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="input">
                <input type="text" name="titulo" placeholder="Titulo" value="<?php echo $novedad['titulo'] ?>">
            </div>

            <textarea class="input_publicar" name="descripcion" placeholder="Escriba una descripcion"><?php echo $novedad['descripcion'] ?></textarea>
            
            <div class="input">
                Adjuntar imagen o video: <input type="file" name="file" placeholder="Archivo adjunto">
            </div>

            <div class="input">
			    <input type="submit" value="Ok">
		    </div>
        </form>
    </div>
	

	<ul id="errores" style="display:none"></ul>

	<?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" style="display:block;">
			<?php 
				echo $_SESSION['errores']; 
				unset($_SESSION['errores']);
			?>
		</ul>
	<?php endif ?>

    <?php if (isset($_SESSION['exito'])): ?>
		<ul id="exito" style="display:block;">
			<?php 
				echo $_SESSION['exito']; 
				unset($_SESSION['exito']);
			?>
		</ul>
	<?php endif ?>

<?php include 'views/footer.php'; ?> 