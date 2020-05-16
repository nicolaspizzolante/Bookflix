<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado() || !$autenticador->esAdmin()) {
		header('Location: login.php');
		exit;
	} 
    include 'views/header.php';

    $db = conectar();

?>
    <div class="container">
        <h1>Cargar Novedad</h1>
        <form action="validarNovedad.php" onsubmit="return validarNovedad(this);" method="post" enctype="multipart/form-data">
        
            <div class="input">
                <input type="text" name="titulo" placeholder="Titulo">
            </div>

            <textarea class="input_publicar" name="descripcion" placeholder="Escriba una descripcion"></textarea>

            <div class="input">
                Adjuntar imagen o video: <input type="file" name="file" placeholder="Archivo adjunto">
            </div>

            <div class="botones">
                <div class="input">
                    <input type="submit" value="Ok">
                </div>
                <a href="index.php" id="btn-cancelar">Cancelar</a>
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