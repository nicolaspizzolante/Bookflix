<?php

include 'autenticador.php';
$autenticador = new autenticador();

if (!$autenticador->estaLogeado() || !$autenticador->esAdmin()) {
    header('Location: login.php');
    exit;
}

include 'views/header.php';

$db = conectar(); 


date_default_timezone_set('America/Argentina/Buenos_Aires');

?>

<div class="container">
        <h1>Buscar usuarios suscriptos </h1>
        <form action="buscarSuscriptos.php" onsubmit="" method="post" enctype="multipart/form-data">
                
            <div>
                <p>Desde:</p>
                    <input type="date" name="fechaDesde" step="1" min="" max="<?php $diaAnterior= date('Y-m-d',strtotime(date('Y-m-d')."- 0 days")); echo $diaAnterior;?>" value="">
            </div>

            <div>
                <p>Hasta</p>
                <input type="date" name="fechaHasta" step="1" min="" max="<?php $diaAnterior= date('Y-m-d',strtotime(date('Y-m-d')."- 0 days")); echo $diaAnterior;?>" value="">
            </div>

           
           	<div class="botones">
                <div class="input">
                    <input type="submit" value="Ok">
                </div>
                <a href="verReportes.php" id="btn-cancelar">Cancelar</a>
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