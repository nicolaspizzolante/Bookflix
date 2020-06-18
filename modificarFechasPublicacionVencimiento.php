<?php

include 'autenticador.php';
$autenticador = new autenticador();

if (!$autenticador->estaLogeado() || !$autenticador->esAdmin()) {
    header('Location: login.php');
    exit;
}

include 'views/header.php';

$db = conectar();
$id = $_GET['id']; 



$sql = "SELECT fecha_publicacion, fecha_vencimiento, libro_id FROM libros_pdf WHERE id = '$id'";
$resultado = $db->query($sql);
$libro = $resultado->fetch_assoc();

$idlibro = $libro['libro_id'];
$sql = "SELECT titulo FROM libros WHERE id = '$idlibro'";
$resultado = $db->query($sql);
$titulo = $resultado->fetch_assoc();


//create string con fecha y hora de publicacion y vencimiento actuales

$fechaPub = substr($libro['fecha_publicacion'],0,10);
$fechaVenc = substr($libro['fecha_vencimiento'],0,10);
$horaPub = substr($libro['fecha_publicacion'],11,18);
$horaVenc = substr($libro['fecha_vencimiento'],11,18);
$fechaHoraPub = $fechaPub.'T'.$horaPub;
$fechaHoraVenc = $fechaVenc.'T'.$horaVenc;


date_default_timezone_set('America/Argentina/Buenos_Aires');

?>

<div class="container">
        <h1>Editar fechas del libro "<?php echo $titulo['titulo']?>"</h1>
        <form action="validarEdicionFechas.php?id=<?php echo $id?>" onsubmit="" method="post" enctype="multipart/form-data">
        
        <article class="libro">
            <p>Fecha de publicacion actual: <?php echo $libro['fecha_publicacion']?></p>
        </article>
                
            <div>
                <p>Nueva fecha de publicacion:</p>
                <?php if(substr($libro['fecha_publicacion'],0,16) > date('Y-m-d H:i')){?>
                <input type="datetime-local" name="nuevaFechaPublicacion" step="1" min=<?php $diaAnterior= date('Y-m-d',strtotime(date('Y-m-d')."- 0 days")); echo $diaAnterior.'T00:00:00';?> max="2100-12-31" value=<?php echo $fechaHoraPub?>>
                <?php }else{?>
                    <input type="datetime-local" name="nuevaFechaPublicacion" step="1" min=<?php echo $fechaHoraPub;?> max="2100-12-31" value=<?php echo $fechaHoraPub?>>
                <?php }?>
            </div>
            <br></br>
            <article class="libro">
            <p>Fecha de vencimiento actual: <?php echo $libro['fecha_vencimiento']?></p>
            </article>
            <div>
                <p>Nueva fecha de vencimiento</p>
                <?php if(substr($libro['fecha_vencimiento'],0,16) > date('Y-m-d H:i')){?>
                <input type="datetime-local" name="nuevaFechaVencimiento" step="1" min=<?php $diaAnterior= date('Y-m-d',strtotime(date('Y-m-d')."- 0 days")); echo $diaAnterior.'T00:00:00';?> max="2100-12-31" value="<?php echo $fechaHoraVenc?>">
                <?php }else{?>
                    <input type="datetime-local" name="nuevaFechaVencimiento" step="1" min=<?php echo $fechaHoraVenc;?> max="2100-12-31" value="<?php echo $fechaHoraVenc?>">
                <?php }?>
            </div>
            <br></br>

           	<div class="botones">
                <div class="input">
                    <input type="submit" value="Ok">
                </div>
                <a href="verListadoLibros.php" id="btn-cancelar">Cancelar</a>
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