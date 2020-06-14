<?php 
    include 'autenticador.php';
    $autenticador = new autenticador();

    if (!$autenticador->estaLogeado() || !$autenticador->esAdmin()) {
        header('Location: login.php');
        exit;
    } 
    include 'views/header.php';

    $db = conectar();
    $id_libro = (isset($_GET['id_libro'])) ? $_GET['id_libro'] : '';
    $sql = "SELECT id, titulo FROM libros WHERE id ='$id_libro'";
    $resultado =  $db->query($sql)->fetch_assoc();


    $libros = $db->query("SELECT * FROM libros")->fetch_all();
    var_dump($resultado);
?>
    <div class="container">
        <h1>
            Cargar trailer para: <?php if (isset($resultado['titulo'])){
                echo $resultado['titulo'];
            } ?>
        </h1>
        
        <form action="validarTrailer.php" onsubmit="return validarTrailer(this);" method="post" enctype="multipart/form-data">
            <?php if($id_libro ===''){?>
                <div class="select-y-boton">
                    <select name="id_libro" id="libro_id">
                        <option disabled="disabled" selected value=""> Seleccione un libro </option>
                        <?php foreach($libros as $libro) { ?>
                            <option value="<?php echo $libro[0]?>"> <?php echo $libro[1] ?> </option>
                        <?php }?>
                    </select>
                </div>
            <?php }else{?>
                <input type="hidden" name="id_libro" value="<?php echo $id_libro ?>" />
            <?php } ?>    
            <div class="input">
                <input type="text" name="titulo" placeholder="Titulo">
            </div>

            <textarea class="input_publicar" name="descripcion" placeholder="Escriba una descripcion para el trailer"></textarea>
 
            <div class="input">
                Adjuntar imagen: <input type="file" name="file" placeholder="Archivo adjunto">
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





<?php 
include 'views/footer.php';
?>