<?php
    include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	}

	include 'views/header.php';
    
    $usuario_id = $_SESSION['usuario']['id'];

	$db = conectar();
	$sql = "SELECT * FROM perfiles WHERE usuario_id = '$usuario_id'";
    $resultado = $db->query($sql);

    while ($perfil = $resultado->fetch_assoc()){
        var_dump($perfil);
    }
?>

<?php include 'views/footer.php'; ?>
