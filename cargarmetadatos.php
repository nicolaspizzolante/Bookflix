<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado() || !$autenticador->esAdmin()) {
		header('Location: login.php');
		exit;
	} 
    include 'views/header.php';

    $db = conectar();

    $autores = $db->query("SELECT * FROM autores")->fetch_all();
    $generos = $db->query("SELECT * FROM generos")->fetch_all();
    $editoriales = $db->query("SELECT * FROM editoriales")->fetch_all();

    var_dump($_GET);
?>
    <div class="container">
        <h1>Cargar Metadatos</h1>
        <form action="validarMetadatos.php" onsubmit="return validarMetadatos(this);" method="post" enctype="multipart/form-data">
        
            <div class="input">
                <input type="text" name="titulo" placeholder="Titulo" value="<?php if(isset($_GET['titulo'])){ echo $_GET['titulo'];} ?>">
            </div>
            
            <div class="input">
                <input type="text" name="isbn" placeholder="ISBN" value="<?php if(isset($_GET['isbn'])){ echo $_GET['isbn'];} ?>">
            </div>

            <div class="select-y-boton">
                <select name="autor" id="autor">
                    <option disabled="disabled" selected value=""> Seleccione un Autor </option>    

                    <?php foreach($autores as $autor) { ?>
                        <option value="<?php echo $autor[0]?>"> <?php echo $autor[1] ?> </option>
                    <?php }?>
                </select>
                <!-- se envia el idlibro = 0 porque no existe un libro con ese id, pero evita que se rompa
                al hacer el GET en altaautor. Lo mismo para el genero y la editorial -->
                <a class="boton-alta" href="altaautor.php?validar=<?php echo 2?>&idlibro=<?php echo 0?>"><i class="fas fa-plus"></i></a>
            </div>
            
            <div class="select-y-boton">
                <select name="genero" id="genero">
                    <option disabled="disabled" selected value=""> Seleccione un Genero </option>
                    <?php foreach($generos as $genero) { ?>
                        <option value="<?php echo $genero[0]?>"> <?php echo $genero[1] ?> </option>
                    <?php }?>
                </select>
                <a class="boton-alta" href="altagenero.php?validar=<?php echo 2?>&idlibro=<?php echo 0?>"><i class="fas fa-plus"></i></a>
            </div>
            
            <div class="select-y-boton">
                <select name="editorial" id="editorial">
                    <option disabled="disabled" selected value=""> Seleccione una Editorial </option>
                    <?php foreach($editoriales as $editorial) { ?>
                        <option value="<?php echo $editorial[0]?>"> <?php echo $editorial[1] ?> </option>
                    <?php }?>
                </select>
                <a class="boton-alta" href="altaeditorial.php?validar=<?php echo 2?>&idlibro=<?php echo 0?>"><i class="fas fa-plus"></i></a>
            </div>

          <!--  <div class="input">
                Ingrese PDF: <input type="file" name="pdf" placeholder="PDF">
            </div>
            -->
            <div class="input">
                Ingrese Imagen: <input type="file" name="foto" placeholder="foto">
            </div>
        
            <textarea class="input_sinopsis" name="sinopsis" placeholder="Escriba una sinopsis" ><?php if(isset($_GET['sinopsis'])){ echo $_GET['sinopsis'];} ?></textarea>
            
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        if(<?php echo $_GET["id_autor"]; ?>){
            $("#autor").val(<?php echo $_GET["id_autor"]; ?>);
        }

        if(<?php echo $_GET["id_editorial"]; ?>){
            $("#editorial").val(<?php echo $_GET["id_editorial"]; ?>);
        }

        if(<?php echo $_GET["id_genero"];?>){
            $("#genero").val(<?php echo $_GET["id_genero"]; ?>);
        }
    </script>

<?php include 'views/footer.php'; ?> 