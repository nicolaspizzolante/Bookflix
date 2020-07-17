<?php 
include 'db.php';
date_default_timezone_set('America/Argentina/Buenos_Aires');

$db = conectar();
$fecha = date("Y-m-d H:i:s"); // fecha actual para guardar en la tabla historial, ya sea update o insert

// parametros que llegan por url
$perfil_id = $_GET['perfil_id'];
$pdf_id = $_GET['pdf_id'];

// buscar id del libro a partir de pdf_id
$sql = "SELECT libro_id FROM libros_pdf WHERE id = $pdf_id";
$libro_id = $db->query($sql)->fetch_assoc()['libro_id'];

// busco si ese libro ya esta en historial para ese usuario
$sql = "SELECT id FROM historial WHERE libro_id = '$libro_id' AND perfil_id = '$perfil_id'";
$resultado = $db->query($sql)->fetch_assoc()['id'];

// si está actualizo, sino inserto nueva fila en tabla historial
if($resultado != null){
    $sql = "UPDATE historial SET fecha = '$fecha' WHERE id = '$resultado'";
    try {
        $db->query($sql);
    } catch(Exception $e) {
        echo $e->getMessage();
    }
} else {
    $sql = "INSERT INTO historial (libro_id, perfil_id, fecha) VALUES ('$libro_id', '$perfil_id', '$fecha')";

    try {
        $db->query($sql);
    } catch(Exception $e) {
        echo $e->getMessage();
    }

    $sql = "SELECT titulo FROM libros WHERE id = $libro_id";
    $r = $db->query($sql);
    $nombre = $r->fetch_assoc()['titulo'];

    $sql = "INSERT INTO reportes_lecturas (libro_id,usuario_id,nombre_libro) VALUES ('$libro_id','$perfil_id','$nombre')";
    try {
        $db->query($sql);
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}

// ejecuto la consulta


?>