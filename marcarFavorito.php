<?php 
include 'db.php';

$db = conectar();

$idUsuario = $_GET['id_user'];
$idLibro = $_GET['id_libro'];
$marcar = $_GET['marcar'];

//Si es 1 hay que marcar como favorito
if($marcar == 1){
    $sql = "INSERT INTO favoritos (perfil_id, libro_id) VALUES ('$idUsuario','$idLibro')";
    $db->query($sql);
}else{
    $sql = "DELETE FROM favoritos WHERE libro_id = '$idLibro' and perfil_id = '$idUsuario'";
    $db->query($sql);
}

header("Location: libro.php?id=$idLibro");

?>