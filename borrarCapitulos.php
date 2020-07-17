<?php 
include 'db.php';

$db = conectar();
$idLibro = $_GET['idLibro'];



$sql = "SELECT subidos FROM libros WHERE id=$idLibro";
$r = $db->query($sql);
$cantSubidos = $r->fetch_assoc()['subidos'];

for($i = 0; $i<=$cantSubidos; $i+=1){
    $sql = "DELETE FROM libros_pdf WHERE libro_id = $idLibro";
    $db->query($sql);
}

$sql = "UPDATE libros SET subidos = 0 WHERE id=$idLibro";
$db->query($sql);
$sql = "UPDATE libros SET capitulos = 0 WHERE id=$idLibro";
$db->query($sql);

$sql = "SELECT * FROM historial WHERE libro_id = $idLibro";
        $r = $db->query($sql);
        while ($histo = $r->fetch_assoc()){
            if(!$histo['terminado']){
                $h_perfil_id = $histo['perfil_id'];
                $sql = "DELETE FROM historial WHERE libro_id = $idLibro and perfil_id = $h_perfil_id";
                $db->query($sql);
            }
    }


header("Location: libro.php?id=$idLibro");

?>