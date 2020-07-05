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
    transition: all .5 ease-out;
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

<div class="perfiles">

    <?php while ($perfil = $resultado->fetch_assoc()){ ?>
        <a href="elegirPerfil.php?id=<?= $perfil['id'] ?>&nombre=<?= $perfil['nombre'] ?>" class="perfil">
            <i class="far fa-user"></i>
            <?= $perfil['nombre'] ?> 
        </a>
    <?php } ?>

    <a href="altaperfil.php" class="perfil">
        <i class="fas fa-plus"></i>
        Agregar perfil
    </a>
    
</div>
    
</body>
</html>





