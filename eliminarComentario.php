<?php

include 'db.php';
$db = conectar();
$id_comentario = (isset($_GET['id'])) ?(int) $_GET['id'] : '';

    $sql = "SELECT libro_id FROM comentarios WHERE id = $id_comentario";
    $db->query($sql);
    $result = $db->query($sql);
    $id_lib = $result->fetch_assoc()['libro_id'];
    $id_libro= $id_lib;

    $sql = "DELETE FROM comentarios WHERE id = $id_comentario";
    $db->query($sql);

    header("Location: libro.php?id=$idLibro");
    ?>
/*
        $respuesta=array(
            'respuesta'=>'correcto',
            'idLibro' => $id_libro
        );    

    echo json_encode($respuesta); */