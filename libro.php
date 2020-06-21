<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	} 
    include 'views/header.php';

    $db = conectar();
    $libro_id = $_GET['id'];

    // Traigo el libro de la db
    $sql = "SELECT * FROM libros WHERE id = $libro_id";
    $resultado = $db->query($sql);
    $libro = $resultado->fetch_assoc();

    // Traigo nombre de autor, editorial y genero del libro
    $genero_id = $libro['genero_id'];
    $editorial_id = $libro['editorial_id'];
    $autor_id = $libro['autor_id'];

    $sql = "SELECT nombre FROM generos WHERE id = $genero_id";
    $resultado = $db->query($sql);
    $libro['genero'] = $resultado->fetch_assoc()['nombre'];

    $sql = "SELECT nombre FROM editoriales WHERE id = $editorial_id";
    $resultado = $db->query($sql);
    $libro['editorial'] = $resultado->fetch_assoc()['nombre'];

    $sql = "SELECT nombre FROM autores WHERE id = $autor_id";
    $resultado = $db->query($sql);
    $libro['autor'] = $resultado->fetch_assoc()['nombre'];

    // consulta para saber si tiene imagen
    $sql = "SELECT id FROM libros WHERE id = $libro_id and imagen is not null and trim(imagen) <> ''";
    $imagen = $db->query($sql);
    $tieneImagen = $imagen->num_rows;

    // consulta para saber si tiene trailer
    $sql = "SELECT * FROM trailers WHERE id_libro = $libro_id";
    $trailer =  $db->query($sql);
    $tieneTrailer = $trailer->num_rows;

?>

<div class="container">

    <!-- Informacion principal -->
    <div class="main"> 

        <div class="imagen-libro">
            <?php if($tieneImagen){ ?>
                <img src="mostrarImagen.php?libro_id=<?php echo $libro['id'] ?>">
            <?php } else {?>
                <img src="img/image.jpg">
            <?php } ?>
        </div>

        <div class="info-libro">
            <div class="titulo-libro"> <h1><?php echo $libro['titulo'] ?></h1> </div>
            <div class="genero"><?php echo $libro['autor']; ?></div>
            <div class="autor"> <?php echo $libro['genero']; ?> </div>
            <div class="editorial"> <?php echo $libro['editorial']; ?> </div>
            <div class="sinopsis-libro"><?php echo $libro['sinopsis'] ?></div>
        </div>

	</div>

    <!-- Botones -->
    <div class="botones-libro">
        
        <!-- boton leer completo -->
        <?php 
            if($libro['capitulos'] <= 1){
                $sql = "SELECT * FROM libros_pdf WHERE libro_id = '$libro_id'";
                $resultado = $db->query($sql);
                $l = $resultado->fetch_assoc();
        ?>

            <?php if(($libro['subidos'] > 0) and (($autenticador->esAdmin()) or ((substr($l['fecha_publicacion'],0,16)<=date('Y-m-d H:i')) and (substr($l['fecha_vencimiento'],0,16) > date('Y-m-d H:i'))or (($l['fecha_vencimiento'] == '0000-00-00 00:00:00')or($l['fecha_vencimiento'] == ''))))){?>
                <div class="input">
                    <a href="leerLibro.php?id=<?php echo $l['id']?>"><input type="button" value="Leer completo"></a>
                </div>
            <?php } ?>
            
        <?php } else { if($libro['subidos'] > 0) {?>
            
            <?php if($libro['capitulos'] == $libro['subidos']){?>
                <?php
                    $sql = "SELECT id FROM libros_pdf WHERE libro_id = '$libro_id' LIMIT 1";
                    $resultado = $db->query($sql);
                    $l = $resultado->fetch_assoc()['id'];
                ?>
                <div class="input">
                    <a href="leerLibro.php?id=<?php echo $l ?>"><input type="button" value="Leer completo"></a>
                </div>
            <?php } ?>

            <div class="input">
                <a href="perfilLibro.php?id=<?php echo $libro['id']?>&selector=<?php echo 1?>"><input type="button" value="Ver Capitulos"></a>
            </div>
        <?php } ?>
               
        <?php }?>

        <!-- botones de administrador -->
        <?php if($autenticador->esAdmin()){ ?>
            
            <div class="input">
                <a href="modificarMetadatos.php?id=<?php echo $libro_id;?>"><input type="button" value="Editar"></a>
            </div>

            <!-- Determino que botones de carga muestro -->
            <?php if($libro['subidos'] == 0){ ?>

                <div class="input">
                    <a href="cargarCapitulo.php?id=<?php echo $libro_id;?>"><input type="button" value="Cargar capitulo"></a>
                </div>

                <div class="input">
                    <a href="cargarLibro.php?id=<?php echo $libro_id;?>"><input type="button" value="Cargar libro"></a>
                </div>

            <?php } elseif (($libro['capitulos'] > 1) and ($libro['capitulos'] > $libro['subidos'])) { ?>

                <div class="input">
                    <a href="cargarCapitulo.php?id=<?php echo $libro_id;?>"><input type="button" value="Cargar capitulo"></a>
                </div>
            
            <?php } ?> <!-- fin if de botones de carga -->

            <!-- Boton editar fechas -->
            <?php
            if($libro['subidos'] >= 1){
                if($libro['capitulos'] == 1){ //El libro no es subido por capitulos
                    $sql = "SELECT id FROM libros_pdf WHERE libro_id = '$libro_id'";
                    $result = $db->query($sql);
                    $pdf_id = $result->fetch_assoc();
            ?>
                    <div class="input">
                        <a href="modificarFechasPublicacionVencimiento.php?id=<?php echo $pdf_id['id']?>"><input type="button" value="Editar fechas"></a>
                    </div>
                <?php }else{?>
                    <div class="input">
                        <a href="perfilLibro.php?id=<?php echo $libro_id?>&selector=<?php echo 0?>"><input type="button" value="Editar fechas"></a>
                    </div>
                <?php }?>
            <?php } ?> <!-- fin boton editar fechas -->
            
        <?php } ?> <!-- fin botones de administrador -->
        
        <!-- boton ver o cargar trailer -->
        <?php if(!$tieneTrailer && $autenticador->esAdmin()){?>
            
            <div class="input">
                <a href="cargarTrailer.php?id_libro=<?php echo $libro_id;?>"><input type="button" value="Cargar Trailer"></a>
            </div>
        
        <?php } elseif ($tieneTrailer) {?>
    
            <div class="input">
                <a href="trailer.php?id=<?php echo $libro_id; ?>"><input type="button" value="Ver Trailer"></a>
            </div>
    
        <?php }?>

        
    </div>

</div>

<?php include 'views/footer.php'; ?>