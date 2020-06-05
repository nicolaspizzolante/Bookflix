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
        <form action="validarCargaLibro.php?id=<?php echo $idlibro?>" onsubmit="" method="post" enctype="multipart/form-data">
            

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

	

<?php include 'views/footer.php'; ?> 