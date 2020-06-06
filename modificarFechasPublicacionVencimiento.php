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

$sql = "SELECT fecha_publicacion, fecha_vencimiento FROM libros_pdf WHERE id = '$id'";
$resultado = $db->query($sql);

$libro = $resultado->fetch_assoc();

?>

<div class="container">
        <h1>Editar fechas</h1>
        <form action="validarEdicionFechas.php?id=<?php echo $id?>" onsubmit="" method="post" enctype="multipart/form-data">
        
        <article class="libro">
            <p>Fecha de publicacion actual: <?php echo $libro['fecha_publicacion']?></p>
        </article>
                
            <div>
                <p>Nueva fecha de publicacion:</p>
                <input type="datetime-local" name="nuevaFechaPublicacion" step="1" min="2020-06-01" max="2100-12-31" value="">
            </div>
            <br></br>
            <article class="libro">
            <p>Fecha de vencimiento actual: <?php echo $libro['fecha_vencimiento']?></p>
            </article>
            <div>
                <p>Nueva fecha de vencimiento</p>
                <input type="datetime-local" name="nuevaFechaVencimiento" step="1" min="2020-06-01" max="2100-12-31" value="">
            </div>
            <br></br>

           	<div class="botones">
                <div class="input">
                    <input type="submit" value="Ok">
                </div>
                <a href="novedad.php?id=<?php echo $id; ?>" id="btn-cancelar">Cancelar</a>
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