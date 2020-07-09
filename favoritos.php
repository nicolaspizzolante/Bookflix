<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	} 
    include 'views/header.php';

    $db = conectar();
    $id_usuario = $_SESSION['usuario']['id'];

    $sql = "SELECT libro_id FROM favoritos WHERE usuario_id = $id_usuario";
    $rows = $db->query($sql);

    if($rows->num_rows == 0){
    $_SESSION['usuario']['errores'] = 'Aun no has marcado favoritos';

    }else{?>
    <div style="text-align:center"><h1> Listado de favoritos </h1></div>
    <table class = "table table-striped table-dark">
        <tr>
            <td style="text-align: center">Libro</td>
        </tr>
    <tbody>
        <?php while ($row = $rows->fetch_assoc()){ ?>

        <tr>
            <td ><a href="libro.php?id=<?php echo $row['libro_id']; ?>"><?php echo $autenticador->retornarTitulo($row['libro_id']); ?></a></td>
        </tr>
        <?php } ?>
    </tbody>
    </table>
        <?php }?>

    <h3>
			<?php if(isset($_SESSION['usuario']['errores'])){ ?>
				<h2 style="text-align:center; color:white;">
					<?php
						echo $_SESSION['usuario']['errores']; 
						$_SESSION['usuario']['errores'] = '';
					?>
				</h2>
			<?php } ?>
		</h3>



<?php include 'views/footer.php'; ?>
