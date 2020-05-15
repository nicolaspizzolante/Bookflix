<?php 
include 'db.php';

$id = $_GET['id'];

$link = conectar();

$sql = "SELECT foto_video FROM novedades WHERE id = $id"; 

$result = mysqli_query($link, $sql); 
$row = mysqli_fetch_array($result); 
mysqli_close($link);

if($row) {
    echo $row['foto_video'];
}
