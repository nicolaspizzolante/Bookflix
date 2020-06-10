<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado() || !$autenticador->esAdmin()) {
		header('Location: login.php');
		exit;
	} 
    include 'views/header.php';

    $db = conectar();

    $idlibro = $_GET["id"];

    $sql = "SELECT titulo FROM libros WHERE id = $idlibro";
    $resultado = $db->query($sql);
    $libro = $resultado->fetch_assoc();

?>
    <div class="container">
        <h1>Cargar libro completo</h1>
        <p id="descripcion-novedad">Seleccione pdf para el libro "<?php echo $libro['titulo'] ?>"</p>
        <form action="validarCargaLibro.php?id=<?php echo $idlibro?>" onsubmit="return validarLibro(this)" method="post" enctype="multipart/form-data">
            

            <div>
                <p>Fecha de publicacion</p>
                <input type="datetime-local" name="fechaPublicacion" step="1" min="2020-06-01" max="2100-12-31" value="">
            </div>
            <div>
                <p>Fecha de vencimiento</p>
                <input type="datetime-local" name="fechaVencimiento" step="1" min="2020-06-01" max="2100-12-31" value="">
            </div>

          <div class="input">
                Ingrese PDF: <input type="file" name="pdf" placeholder="PDF">
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