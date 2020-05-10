<?php 
include 'db.php';

$id = $_GET['libro_id'];


$link = conectar();


$sql = "SELECT imagen FROM libros WHERE id = $id"; 


$result = mysqli_query($link, $sql); 
$row = mysqli_fetch_array($result); 
mysqli_close($link);

$img = 'https://matthewsenvironmentalsolutions.com/images/com_hikashop/upload/not-available_1481220154.png';

if($row){
	echo $row['imagen'];
}else{
	echo $img;
}
