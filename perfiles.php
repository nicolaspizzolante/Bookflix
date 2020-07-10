<?php
    include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}
    
    $usuario_id = $_SESSION['usuario']['id'];

	$db = conectar();
	$sql = "SELECT * FROM perfiles WHERE usuario_id = '$usuario_id'";
    $resultado = $db->query($sql);

    $cant_perfiles = $resultado->num_rows;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
	<link rel="stylesheet" href="fontawesome/css/all.min.css">
	<script src="https://kit.fontawesome.com/c7cd9c5077.js" crossorigin="anonymous"></script>
    <title>Bookflix</title>
</head>

<style>

* {
    box-sizing: border-box;
}

body {
    height: 90vh;
    background: #282828;
    display: flex;
    align-items: center;
    justify-content: center;
}

.main {
    display:flex;
    flex-direction:column;
    text-align:center;
}

h1 {
    font-size: 2.5em !important;
    margin-bottom: 30px;
}

.perfiles {
    display:flex;
}

.perfil {
    display: flex;
    flex-direction: column;
    text-align: center;
    margin-right: 20px;
    background: #212121;
    padding: 25px;
    border: 5px solid transparent;
    transition: border .2s;
    color: white;
    border-radius: 3px;
}

.perfil:hover {
    cursor: pointer;
    border: 5px solid #E51F1F;
}

.perfil i {
    font-size: 100px;
    color: white;
    margin-bottom: 20px;
}

</style>

<body>

<div class="main">
    <h1>¿Quien está leyendo?</h1>
    
    <div class="perfiles" style="display:none;">

        <?php while ($perfil = $resultado->fetch_assoc()){ ?>
            <a href="elegirPerfil.php?id=<?= $perfil['id'] ?>&nombre=<?= $perfil['nombre'] ?>" class="perfil">
                <i class="far fa-user"></i>
                <?= $perfil['nombre'] ?> 
            </a>
        <?php } ?>
        
        <?php if (($_SESSION['usuario']['es_premium'] && $cant_perfiles < 4) || (!$_SESSION['usuario']['es_premium'] && $cant_perfiles < 2)){ ?>
            <a href="altaperfil.php" class="perfil">
                <i class="fas fa-plus"></i>
                Agregar perfil
            </a>
        <?php } ?>
    </div>
    
    <?php if (isset($_SESSION['errores'])): ?>
		<ul id="errores" class="asd" style="display:block;">
			<?php 
				echo $_SESSION['errores']; 
				unset($_SESSION['errores']);
			?>
		</ul>
	<?php endif ?>

	<?php if (isset($_SESSION['exito'])){ ?>
        <ul id="exito" style="display:block;">
            <?php 
                echo $_SESSION['exito']; 
                unset($_SESSION['exito']);
            ?>
        </ul>
    <?php } ?>

</div>

    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

$(window).on('load', function(){
    $('.perfiles').fadeIn(1000);
});


</script>


</body>
</html>





