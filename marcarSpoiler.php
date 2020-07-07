<?php 
include 'db.php';

$db = conectar();

$idComentario = $_GET['idComment'];
$idLibro = $_GET['idLibro'];
$spoiler = $_GET['identificador'];

//Si es 1 hay que marcar como spoiler
if($spoiler == 1){
    $sql = "UPDATE comentarios SET es_spoiler = 1 WHERE id = $idComentario";
    $db->query($sql);
}else{
    $sql = "UPDATE comentarios SET es_spoiler = 0 WHERE id = $idComentario";
    $db->query($sql);
}


header("Location: libro.php?id=$idLibro");

?>