<?php 
include 'db.php';

$db = conectar();
$id_user = $_GET['id_user'];

session_start();
session_destroy();

$sql = "DELETE FROM usuarios WHERE id = $id_user";
$db->query($sql);

header("Location: index.php");


?>