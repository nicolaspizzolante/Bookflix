<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado() || !$autenticador->esAdmin()) {
		header('Location: login.php');
		exit;
	} 
    include 'views/header.php';

	$db = conectar();
	
    $idlibro = $_GET['id']; //quitar este comentario y reemplazar la linea de abajo

	$sql = "SELECT * FROM libros WHERE id = '$idlibro'";  //cambiar por $sql = "SELECT * FROM libros WHERE id = '$idlibro'";

	$result = mysqli_query($db, $sql); 
    $libro = $result->fetch_assoc();
    
    $autores = $db->query("SELECT * FROM autores")->fetch_all();
    $generos = $db->query("SELECT * FROM generos")->fetch_all();
    $editoriales = $db->query("SELECT * FROM editoriales")->fetch_all();
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
        
		<h1>Modificar libro</h1>
		<?php $id_libro = $libro['id']; ?>
        <form action="validarEdicionMetadatos.php?ident=<?php echo $id_libro;?>" onsubmit="return validarMetadatos(this);" method="post" enctype="multipart/form-data">
        
            <div class="input">
				<label for="titulo">Cambiar titulo:</label>
                <input type="text" name="titulo" placeholder="Nuevo titulo" value="<?php echo $libro['titulo'] ?>">
            </div>
            
            <div class="input">
				<label for="isbn">Cambiar ISBN:</label>
				<input type="text" name="isbn" placeholder="Nuevo ISBN" value="<?php echo $libro['isbn'] ?>">
			</div>
			
            <div class="select-y-boton">
                <label for="autor">Cambiar autor: </label>
                <select name="autor" id="autor">
                    <?php foreach($autores as $autor) { ?>
                        <option value="<?php echo $autor[0]?>"> <?php echo $autor[1] ?> </option>
                    <?php }?>
                </select>
                <a class="boton-alta" href="altaautor.php?validar=<?php echo 3?>&idlibro=<?php echo $idlibro?>"><i class="fas fa-plus"></i></a>
            </div>
			
            <div class="select-y-boton">
                <label for="genero">Cambiar genero: </label>
                <select name="genero" id="genero">
                    <?php foreach($generos as $genero) { ?>
                        <option value="<?php echo $genero[0]?>"> <?php echo $genero[1] ?> </option>
                    <?php }?>
                </select>
                <a class="boton-alta" href="altagenero.php?validar=<?php echo 3?>&idlibro=<?php echo $idlibro?>"><i class="fas fa-plus"></i></a>
            </div>
			
            <div class="select-y-boton">
			    <label for="editorial">Cambiar editorial: </label>
                <select name="editorial" id="editorial">
                    <?php foreach($editoriales as $editorial) { ?>
                        <option value="<?php echo $editorial[0]?>"> <?php echo $editorial[1] ?> </option>
                    <?php }?>
                </select>
                <a class="boton-alta" href="altaeditorial.php?validar=<?php echo 3?>&idlibro=<?php echo $idlibro?>"><i class="fas fa-plus"></i></a>
            </div>
            
            <div class="input">
                Actualizar Imagen: <input type="file" name="foto" placeholder="foto">
            </div>
            <div class="input">
                <input type="number" id="cantCapitulos" name="cantCapitulos" placeholder="Cantidad de capitulos" value="<?php echo $libro['capitulos']?>">
            </div>

            <textarea class="input_sinopsis" name="sinopsis" placeholder="Escriba una sinopsis"><?php echo $libro['sinopsis'] ?></textarea>
            
            
            <div class="botones">
                <div class="input">
                    <input type="submit" value="Ok">
                </div>
                <a href="verlistadolibros.php" id="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>

	<ul id="errores" style="display:none"></ul>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $("#autor").val(<?php echo $libro['autor_id']; ?>).trigger('change');
        $("#editorial").val(<?php echo $libro['editorial_id']; ?>).trigger('change');
        $("#genero").val(<?php echo $libro['genero_id']; ?>).trigger('change');
    </script>

<?php include 'views/footer.php'; ?> 