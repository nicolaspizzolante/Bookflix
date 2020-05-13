<?php 
include 'db.php';

$db = conectar();
$id = $_GET['id'];

$sql = "DELETE FROM novedades WHERE id = $id";
$db->query($sql);

header("Location: verlistadonovedades.php");

?>