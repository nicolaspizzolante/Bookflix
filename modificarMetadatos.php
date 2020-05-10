<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	} 
    include 'views/header.php';

	$db = conectar();
	
	//$idlibro = $_GET['ident'];

	$sql = "SELECT * FROM libros WHERE id = 4";  //cambiar por $idLibro

	$result = mysqli_query($db, $sql); 
	$libro = mysqli_fetch_array($result); 

    $autores = $db->query("SELECT * FROM autores")->fetch_all();
    $generos = $db->query("SELECT * FROM generos")->fetch_all();
    $editoriales = $db->query("SELECT * FROM editoriales")->fetch_all();

?>
    <div class="container">
		<h1>Modificar libro</h1>
		<?php $id_libro = $libro['id'];?>
        <form action="validarEdicionMetadatos.php?ident=<?php echo $id_libro;?>" onsubmit="return validarMetadatos(this);" method="post" enctype="multipart/form-data">
        
            <div class="input">
				<label for="email">Cambiar titulo:</label>
                <input type="text" name="titulo" placeholder="Nuevo titulo" value="<?php echo $libro['titulo'] ?>">
            </div>
            
            <div class="input">
				<label for="email">Cambiar ISBN:</label>
				<input type="text" name="isbn" placeholder="Nuevo ISBN" value="<?php echo $libro['isbn'] ?>">
			</div>
			
			
			<div class="input">
			<label for="email">Cambiar autor:</label>
            <div class="select-y-boton">
                <select name="autor" id="">
                    <option disabled="disabled" selected value=""> Seleccione un Autor </option>
                    <?php foreach($autores as $autor) { ?>
                        <option value="<?php echo $autor[0]?>"> <?php echo $autor[1] ?> </option>
                    <?php }?>
                </select>
                <a class="boton-alta" href="altaautor.php"><i class="fas fa-plus"></i></a>
			</div>
			</div>
			
			<label for="email">Cambiar genero:</label>
            <div class="select-y-boton">
                <select name="genero" id="">
                    <option disabled="disabled" selected value="<?php echo $libro['genero'] ?>"> Seleccione un Genero </option>
                    <?php foreach($generos as $genero) { ?>
                        <option value="<?php echo $genero[0]?>"> <?php echo $genero[1] ?> </option>
                    <?php }?>
                </select>
                <a class="boton-alta" href="altagenero.php"><i class="fas fa-plus"></i></a>
            </div>
			
			<label for="email">Cambiar editorial:</label>
            <div class="select-y-boton">
                <select name="editorial" id="">
                    <option disabled="disabled" selected value="<?php echo $libro['editorial'] ?>"> Seleccione una Editorial </option>
                    <?php foreach($editoriales as $editorial) { ?>
                        <option value="<?php echo $editorial[0]?>"> <?php echo $editorial[1] ?> </option>
                    <?php }?>
                </select>
                <a class="boton-alta" href="altaeditorial.php"><i class="fas fa-plus"></i></a>
            </div>

            <div class="input">
                Actualizar PDF: <input type="file" name="pdf" placeholder="PDF">
            </div>
            
            <div class="input">
                Actualizar Imagen: <input type="file" name="foto" placeholder="foto">
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