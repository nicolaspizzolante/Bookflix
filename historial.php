<?php
	include 'autenticador.php';
	$autenticador = new autenticador();
	
	if (!$autenticador->estaLogeado()) {
		header('Location: login.php');
		exit;
	} 
    include 'views/header.php';

    $db = conectar();
    $id = $_SESSION['usuario']['id'];

    $sql = "SELECT libro_id, fecha FROM historial WHERE usuario_id = $id ORDER BY fecha DESC";
    $rows = $db->query($sql);

    ?>

    <table class = "table table-striped table-dark">
        <tr>
            <td style="text-align: center">Libro</td>
            <td style="text-align: center">Leído por ultima vez</td>
        </tr>
    <tbody>
        <?php while ($row = $rows->fetch_assoc()){ ?>

        <tr>
            <td ><?php echo $autenticador->retornarTitulo($row['libro_id']); ?></td>
            <td style="text-align: center"><?php echo date("d/m/Y H:i", strtotime($row['fecha'])); ?></td>
        </tr>
        <?php } ?>
    </tbody>
    </table>



<?php include 'views/footer.php'; ?>
