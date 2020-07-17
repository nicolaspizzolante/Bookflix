<?php 
include 'db.php';

$db = conectar();

$idPerfil = $_GET['id_user'];
$idLibro = $_GET['id_libro'];
$marcar = $_GET['marcar'];

//Si es 1 hay que marcar como favorito
if($marcar == 1){
    $sql = "UPDATE historial SET terminado = 1 WHERE perfil_id = '$idPerfil' and libro_id = '$idLibro'";
    $db->query($sql);

    $sql = "UPDATE reportes_lecturas SET terminado = 1 WHERE usuario_id = '$idPerfil' and libro_id = '$idLibro'";
    $db->query($sql);

}else{
    $sql = "UPDATE historial SET terminado = 0 WHERE perfil_id = '$idPerfil' and libro_id = '$idLibro'";
    $db->query($sql);

    $sql = "UPDATE reportes_lecturas SET terminado = 0 WHERE usuario_id = '$idPerfil' and libro_id = '$idLibro'";
    $db->query($sql);
}

header("Location: libro.php?id=$idLibro");

?>