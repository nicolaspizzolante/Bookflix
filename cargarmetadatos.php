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
?>
    <div class="container">
        <h1>Cargar Metadatos</h1>
        <form action="validarMetadatos.php" onsubmit="return validarMetadatos(this);" method="post" enctype="multipart/form-data">
        
            <div class="input">
                <input type="text" id="titulo" name="titulo" placeholder="Titulo" value="<?php if(isset($_GET['titulo'])){ echo $_GET['titulo'];} ?>">
            </div>
            
            <div class="input">
                <input type="text" id="isbn" name="isbn" placeholder="ISBN" value="<?php if(isset($_GET['isbn'])){ echo $_GET['isbn'];} ?>">
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
                <a class="boton-alta" id="alta-autor" href="altaautor.php?validar=<?php echo 2?>&idlibro=<?php echo 0?>"><i class="fas fa-plus"></i></a>
            </div>
            
            <div class="select-y-boton">
                <select name="genero" id="genero">
                    <option disabled="disabled" selected value=""> Seleccione un Genero </option>
                    <?php foreach($generos as $genero) { ?>
                        <option value="<?php echo $genero[0]?>"> <?php echo $genero[1] ?> </option>
                    <?php }?>
                </select>
                <a class="boton-alta" id="alta-editorial" href="altagenero.php?validar=<?php echo 2?>&idlibro=<?php echo 0?>"><i class="fas fa-plus"></i></a>
            </div>
            
            <div class="select-y-boton">
                <select name="editorial" id="editorial">
                    <option disabled="disabled" selected value=""> Seleccione una Editorial </option>
                    <?php foreach($editoriales as $editorial) { ?>
                        <option value="<?php echo $editorial[0]?>"> <?php echo $editorial[1] ?> </option>
                    <?php }?>
                </select>
                <a class="boton-alta" id="alta-genero" href="altaeditorial.php?validar=<?php echo 2?>&idlibro=<?php echo 0?>"><i class="fas fa-plus"></i></a>
            </div>

            <div>
                <p>Fecha de publicacion</p>
                <input type="datetime-local" name="fechaPublicacion" step="1" min="2020-06-01" max="2100-12-31" value="">
            </div>
            <div>
                <p>Fecha de vencimiento</p>
                <input type="datetime-local" name="fechaVencimiento" step="1" min="2020-06-01" max="2100-12-31" value="">
            </div>

                <div class="input">
                Ingrese Imagen: <input type="file" name="foto" placeholder="foto">
            </div>
        
            <textarea class="input_sinopsis" id="sinopsis" name="sinopsis" placeholder="Escriba una sinopsis" ><?php if(isset($_GET['sinopsis'])){ echo $_GET['sinopsis'];} ?></textarea>

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
        // Dejo preseleccionado autor editorial y genero que vengan en la url
        <?php if (isset($_GET["id_autor"])){ ?>
            $("#autor").val(<?php echo $_GET["id_autor"]; ?>);
        <?php } ?>

        <?php if (isset($_GET["id_editorial"])){ ?>
            $("#editorial").val(<?php echo $_GET["id_editorial"]; ?>);
        <?php } ?>

        <?php if (isset($_GET["id_genero"])){ ?>
            $("#genero").val(<?php echo $_GET["id_genero"]; ?>);
        <?php } ?>

        // Al presionar el boton de alta autor, editorial o genero
        // guardo los datos del form en el local storage
        $('#alta-autor').on('click', function(e){
            saveLocalStorage();
        });

        $('#alta-editorial').on('click', function(e){
            saveLocalStorage();
        });

        $('#alta-genero').on('click', function(e){
            saveLocalStorage();
        });

        // Si vengo de dar de alta un autor, editorial o genero
        // seteo el form con los valores del localStorage y lo reseteo
        <?php if (isset($_GET['from_alta_autor']) || isset($_GET['from_alta_editorial']) || isset($_GET['from_alta_genero'])) { ?>
            $("#titulo").val(localStorage.getItem('titulo'));
            $("#isbn").val(localStorage.getItem('isbn'));
            $("#autor").val(localStorage.getItem('autor'));
            $("#genero").val(localStorage.getItem('genero'));
            $("#editorial").val(localStorage.getItem('editorial'));
            $("#sinopsis").val(localStorage.getItem('sinopsis'));

            localStorage.clear();
        <?php } ?>

        // Function que guarda los datos del form en el localStorage
        function saveLocalStorage(){
            localStorage.setItem('titulo', $("#titulo").val());
            localStorage.setItem('isbn', $("#isbn").val());
            localStorage.setItem('autor', $("#autor").val());
            localStorage.setItem('genero', $("#genero").val());
            localStorage.setItem('editorial', $("#editorial").val());
            localStorage.setItem('sinopsis', $("#sinopsis").val());
        }
    </script>

<?php include 'views/footer.php'; ?>