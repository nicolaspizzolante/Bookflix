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

    $sql = "SELECT titulo, subidos, capitulos FROM libros WHERE id = $idlibro";
    $resultado = $db->query($sql);
    $libro = $resultado->fetch_assoc();

    date_default_timezone_set('America/Argentina/Buenos_Aires');
?>
    <div class="container">

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
    
        <h1>Cargar libro completo</h1>
    
        <p id="descripcion-novedad">Seleccione archivo pdf y fechas para el libro"<?php echo $libro['titulo'] ?>"</p>
        <form action="validarCargaLibro.php?id=<?php echo $idlibro ?>" onsubmit="return validarLibro(this)" method="post" enctype="multipart/form-data">
            <div>
                <p>Fecha de publicacion (*)</p>
                <input type="datetime-local" name="fechaPublicacion" step="1" min=<?php $diaAnterior= date('Y-m-d',strtotime(date('Y-m-d')."- 0 days")); echo $diaAnterior.'T00:00:00';?> max="2100-12-31" value="<?php $diaAnterior= date('Y-m-d',strtotime(date('Y-m-d')."- 0 days")); $hora= date('H:i:s'); echo $diaAnterior."T".$hora;?>">
            </div>
            <div>
                <p>Fecha de vencimiento</p>
                <input type="datetime-local" name="fechaVencimiento" step="1" min=<?php $diaAnterior= date('Y-m-d',strtotime(date('Y-m-d')."- 0 days")); echo $diaAnterior.'T00:00:00';?> max="2100-12-31" value="">
            </div>

            <div class="input">
                Ingrese PDF: <input type="file" name="pdf" placeholder="PDF"> (*)
            </div>

            <div style="margin-bottom: 22px; margin-top:12px">
            <p> (*) Campos obligatorios</p>
            </div>

            <div class="botones">
                <div class="input">
                    <input type="submit" value="Ok">
                </div>
                <a href="libro.php?id=<?php echo $idlibro?>" id="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
	
	<ul id="errores" style="display:none"></ul>

<?php include 'views/footer.php'; ?> 