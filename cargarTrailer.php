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
                        <option disabled="disabled" selected value=""> Seleccione libro si desea asociarlo </option>
                        <?php foreach($libros as $libro) { ?>
                            <?php 
                                $sql= "SELECT id_libro FROM trailers WHERE id_libro= '$libro[0]'";
                                $aux= $db->query($sql)->fetch_assoc();
                            ?>
                            <?php  if($aux['id_libro'] != $libro[0]){ ?>
                                <option value="<?php echo $libro[0]?>"> <?php echo $libro[1] ?> </option>
                            <?php } ?>    
                        <?php }?>
                    </select>
                </div>
            <?php }else{?>
                <input type="hidden" name="id_libro" value="<?php echo $id_libro ?>" />
            <?php } ?>    
            <div class="input">
                <input type="text" name="titulo" placeholder="Titulo"> (*)
            </div>
            <div class="desc-trailer">
                <textarea class="input_publicar" name="descripcion" placeholder="Escriba una descripcion para el trailer"></textarea>
                <span>(*)</span>
            </div>
            <div class="input">
                Adjuntar imagen: <input type="file" name="file" placeholder="Archivo adjunto">
            </div>
            
            <div style="margin-bottom: 22px; margin-top:12px">
            <p> (*) Campos obligatorios</p>
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