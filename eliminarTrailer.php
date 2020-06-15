<?php 
include 'db.php';

$db = conectar();
$id = $_GET['id'];

$sql = "DELETE FROM trailers WHERE id = $id";
$db->query($sql);

header("Location: verlistadoLibros.php");

?>