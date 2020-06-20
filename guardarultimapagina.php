<?php 
include 'db.php';

$db = conectar();


// parametros que llegan por url
$usuario_id = $_GET['usuario_id'];
$pdf_id = $_GET['pdf_id'];
$pagina = $_GET['pagina'];

// busco si ese libro ya esta en historial para ese usuario
$sql = "SELECT id FROM ultima_pagina WHERE pdf_id = '$pdf_id' AND usuario_id = '$usuario_id'";
$resultado = $db->query($sql)->fetch_assoc()['id'];

// si está actualizo, sino inserto nueva fila en tabla historial
if($resultado != null){
    $sql = "UPDATE ultima_pagina SET pagina = '$pagina' WHERE id = '$resultado'";
} else {
    $sql = "INSERT INTO ultima_pagina (pdf_id, usuario_id, pagina) VALUES ('$pdf_id', '$usuario_id', '$pagina')";
}

// ejecuto la consulta
try {
    $db->query($sql);
} catch(Exception $e) {
    echo $e->getMessage();
}

?>