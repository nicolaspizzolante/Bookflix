<?php 
include 'db.php';

$db = conectar();
$id = $_GET['id'];

$sql = "DELETE FROM trailers WHERE id_libro = $id";
$db->query($sql);

header("Location: verlistadoLibros.php");

?>