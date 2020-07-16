<?php 
include 'db.php';

$db = conectar();
$idLibro = $_GET['idLibro'];
$idpdf = isset($_GET['pdf_id']) ? $_GET['pdf_id'] : NULL;

if(!$idpdf){
    $sql = "DELETE FROM libros WHERE id = $idLibro";
    $db->query($sql);
    
    
    header("Location: perfilLibro.php?id=$idLibro");
}else{
    $sql = "DELETE FROM libros_pdf WHERE id = $idpdf";
    $db->query($sql);
    $sql = "UPDATE libros SET subidos = subidos - 1 WHERE id=$idLibro";
    $db->query($sql);

    //Si borra todos los capitulos reinicio contador de capitulos 
    $sql = "SELECT subidos FROM libros WHERE id=$idLibro";
    $r = $db->query($sql);
    $cantSubidos = $r->fetch_assoc()['subidos'];
    if($cantSubidos == 0){
        $sql = "UPDATE libros SET capitulos = 0 WHERE id=$idLibro";
        $db->query($sql);
    }

    header("Location: libro.php?id=$idLibro");
}

?>