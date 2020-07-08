<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	} 
    include 'views/header.php';

    date_default_timezone_set('America/Argentina/Buenos_Aires');

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

<?php if(isset($_SESSION['exito'])){ ?>
		<ul id="exito" class="asd">
				<?php 
					echo $_SESSION['exito']; 
					unset($_SESSION['exito']);
				?>
		</ul>
	<?php } ?>

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
        <?php if(($libro['subidos']<=0) or ((substr($l['fecha_publicacion'],0,16)>date('Y-m-d H:i')) or ((substr($l['fecha_vencimiento'],0,16) <= date('Y-m-d H:i'))and($l['fecha_vencimiento']!='')and($l['fecha_vencimiento'] !='0000-00-00 00:00:00' )))){ ?>
            <h3> Libro no disponible  </h3>
        <?php }?>

            <?php if(($libro['subidos'] > 0) and (($autenticador->esAdmin()) or ((substr($l['fecha_publicacion'],0,16)<=date('Y-m-d H:i')) and (substr($l['fecha_vencimiento'],0,16) > date('Y-m-d H:i')or (($l['fecha_vencimiento'] == '0000-00-00 00:00:00')or($l['fecha_vencimiento'] == '')))))){?>
                <div class="input">
                    <a href="leerLibro.php?id=<?php echo $l['id']?>"><input type="button" value="Leer completo"></a>
                </div>
            <?php }?>
            
        <?php } else { if($libro['subidos'] > 0) {?>
            
            <?php if($libro['capitulos'] == $libro['subidos']){?>
                <?php
                    $sql = "SELECT * FROM libros_pdf WHERE libro_id = '$libro_id'";
                    $resultado = $db->query($sql);
                    $todosDisponibles =TRUE;
                    $int = 1;
                    while ($cap = $resultado->fetch_assoc()){
                        if($int == 1){
                            $idPrimero = $cap['id'];
                            $int = $int +1;
                        }
                        if((substr($cap['fecha_publicacion'],0,16)>date('Y-m-d H:i')) or ((substr($cap['fecha_vencimiento'],0,16) <= date('Y-m-d H:i'))and($cap['fecha_vencimiento'] != '0000-00-00 00:00:00')and($cap['fecha_vencimiento'] != ''))){
                            $todosDisponibles = FALSE;
                        }
                    }
                    if($todosDisponibles){
                ?>
                <div class="input">
                    <a href="leerLibro.php?id=<?php echo $idPrimero ?>"><input type="button" value="Leer completo"></a>
                </div>
            <?php } ?>
        <?php } ?>

            <div class="input">
                <a href="perfilLibro.php?id=<?php echo $libro['id']?>&selector=<?php echo 1?>"><input type="button" value="Ver Capitulos"></a>
            </div>
        <?php }else{ ?>
                <h3> Libro no disponible  </h3>
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
                    $pdf_id = $result->fetch_assoc()['id'];
            ?>
                    <div class="input">
                        <a href="modificarFechasPublicacionVencimiento.php?id=<?php echo $pdf_id?>"><input type="button" value="Editar fechas"></a>
                    </div>
                    <div class="input">
						<button id="btn-borrar" onClick="confirmation('<?php echo $pdf_id?>','<?php echo $libro_id?>')">Eliminar pdf</button>
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
    <?php 
            $sql = "SELECT texto, fecha, libro_id, usuario_id, calificacion FROM comentarios ORDER BY fecha DESC";
            $coms = $db->query($sql);
            $Comentaste =false;
            $user_id = $_SESSION['usuario']['id'];
    ?>
    <?php foreach ($coms as $com) {
        if($com['usuario_id'] == $user_id && $com['libro_id'] == $libro_id) {
           
            $Comentaste = true;
        }
     } 
    ?> 
    <?php if($Comentaste == false && !($autenticador->esAdmin())){ ?>
        <form action="comentar.php" method="get" class="form-comentario" id="formulario-comentar">
                <textarea class="comentar" placeholder="Deja un comentario" name="comentario"></textarea>
                <input type="hidden" name="id_libro" value="<?php echo $libro_id; ?>">
                <div class="opciones-comentar">
                    <div class="puntuacion-ratio" name="puntuacion">
                        <p>Deja una puntuacion:</p>
                        <input type="radio" name="puntuacion" value="1"> 1
                        <input type="radio" name="puntuacion" value="2"> 2
                        <input type="radio" name="puntuacion" value="3" checked> 3
                        <input type="radio" name="puntuacion" value="4"> 4
                        <input type="radio" name="puntuacion" value="5"> 5
                    </div>
                    <div class="contiene-spoiler">
                        <p>Tu comentario contiene spoilers?</p>
                        <div class="eleccion-spoiler">
                            <div >
                            <span>SI</span> <input type="radio" name="spoiler" value="1">
                            </div>
                            <div>
                                <span> NO</span> <input type="radio" name="spoiler" value="0" checked> 
                            </div>
                        </div>
                        
                    </div>
                </div>
                <input type="submit" class="boton-comentar">
        </form>
    <?php }elseif(!$autenticador->esAdmin()){ ?>
        <h1 class="ya-comentado">Ya dejaste tu opinión del libro !</h1>	
    <?php } ?>
		<?php // consulta a la tabla de comentarios
			$sql = "SELECT * FROM comentarios ORDER BY fecha DESC";
            $comentarios = $db->query($sql);
            $comentarios1 = $db->query($sql);
			?> 
	
		<div class="lista-comentarios">
            <?php $hayComentarios=false;
                  foreach ($comentarios as $comentario) {
                      if($libro_id== $comentario['libro_id']){
                          $hayComentarios = true;
                    }
                 }
                if($hayComentarios){?>
                <h2 class="titulo-comentarios">Comentarios</h2>
            <?php }else{
                if(!$autenticador->esAdmin()){?>
                <h2 class="reseña">Se el primero en dejar su opinión !</h2>  
            <?php }else{?>
                <h2 class="reseña">Aún no se han realizado comentarios</h2> 
            <?php }?>
        <?php }?>      
	<?php while ($comentario = $comentarios1->fetch_assoc()){ //array asociativo con los comentarios?>
			<?php 
				$id_libro_comentario = $comentario['libro_id'];
				
				if ($libro_id === $id_libro_comentario) {
                   
					$usuario_id = $comentario['usuario_id'];
					$sql = "SELECT id, nombre, apellido FROM usuarios WHERE id = $usuario_id";
                    $nombres = $db->query($sql);//traigo nom y ape de la tabla de usuarios usando el usuario id del comentario 
                    
			?>
			  <?php $nombre = $nombres->fetch_assoc(); ?>
	 			<div class="comentario" id="comentario:<?php echo $comentario['id'] ?>" >
                    <div class="parte-superior">
                        <div class="izq">
                            <div>
                                <?php switch ($comentario['calificacion']) {
                                    case 5:
                                        ?>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <?php
                                        break;
                                    case 4:
                                        ?>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <?php
                                        break;
                                    case 3:
                                        ?>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <?php
                                        break;        
                                    case 2:
                                        ?>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <?php
                                        break;     
                                        case 1:
                                            ?>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <?php
                                            break;        
                                }?>
                                
                            </div>
                            <div>
                                <span class="nombre-usuario"><?php echo $nombre['nombre'];?> <?php echo $nombre['apellido'] ?></span>
                            </div>
                            <div>
                                <i class="far fa-clock"></i>
                                <span class="fecha-comentario"><?php echo $comentario['fecha'] ?></span>
                            </div> 
                        </div><!--.izq-->
                        <div class="der">
                        <?php
                            if($comentario['usuario_id']==$user_id || $autenticador->esAdmin()){?>  
                            <div class="eliminar-com"><button id="btn-borrar-com" onClick="borrarCom('<?php echo $id_libro_comentario?>', '<?php echo $comentario['id']?>')"><i class="fas fa-trash"></i></button></div> 
                            <?php } ?>
                            <?php if($autenticador->esAdmin()){?>
                                <?php if($comentario['es_spoiler'] == 1){?>
                                    <div class="checkSpoiler">
                                        <a href="marcarSpoiler.php?idComment=<?php echo $comentario['id']?>&idLibro=<?php echo $libro_id?>&identificador=0" id="checkSpoiler">No es spoiler</a>
                                    </div>
                                <?php }else{?>
                                    <div class="checkSpoiler">
                                        <a href="marcarSpoiler.php?idComment=<?php echo $comentario['id']?>&idLibro=<?php echo $libro_id?>&identificador=1" id="checkSpoiler">Es spoiler</a>
                                    </div>
                                <?php }?>
                                <?php }?>
                        
                        </div>
                        
                    </div>
                    <div class="parte-inferior container">
                        <?php 
                            if($comentario['es_spoiler']==1){ ?>
                                <div class="cont-spoiler">
                                    <p class="warning" onClick="myFunction()">Warning: spoiler</p>
                                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                                </div>
                                
                        <?php } ?>
                        
                        <p class="<?php echo ($comentario['es_spoiler']==1 ? 'no-mostrar' : 'mostrar') ?>"><?php echo $comentario['texto'] ?></p>
                    </div>

                </div>
			  
		  <?php }?>

	<?php } ?>		
	</div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function borrarCom($id_libro_comentario, $comentario_id){
	Swal.fire({
		title: '¿Seguro?',
		text: "¡Esta acción no se puede revertir!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: '¡Sí, borrar!',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.value) {
			$.ajax({
				url: "eliminarComentario.php?id=" + $comentario_id ,
				context: document.body
			}).done(() => {
				Swal.fire(
					'¡Borrado!',
					'El comentario fue borrado con exito',
					'success'
				).then(() =>{
					window.location.href = "libro.php?id=" + $id_libro_comentario;
				});
			});
		}
	})
}
</script>

<script>


function confirmation($identPDF, $identLibro){
	Swal.fire({
		title: '¿Seguro?',
		text: "¡Esta acción no se puede revertir!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: '¡Sí, borrar!',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.value) {
			$.ajax({
				url: "borrarLibro.php?idLibro=" + $identLibro+"&pdf_id=" + $identPDF,
				context: document.body
			}).done(() => {
				Swal.fire(
					'¡Borrado!',
					'El libro fue borrado con exito',
					'success'
				).then(() =>{
					window.location.href = "verListadoLibros.php";
				});
			});
		}
	})
}
</script>
<?php include 'views/footer.php'; ?>