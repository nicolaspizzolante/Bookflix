<?php

    session_start();
    include 'db.php';
    $conexion = conectar();
    $id_usuario = $_SESSION['usuario']['id'];
    $id_libro = $_GET['id_libro'];
    $comentario = $_GET['comentario'];
    $calificacion = $_GET['puntuacion'];
    $contiene_spoiler = $_GET['spoiler'];


        $sql = "INSERT INTO comentarios (texto, fecha, libro_id, usuario_id, calificacion, es_spoiler) 
            VALUES ('$comentario', now(), '$id_libro', '$id_usuario', '$calificacion', '$contiene_spoiler')";
        
        try{
            $resultado = $conexion->query($sql);
            /*
            $sql= "SELECT COUNT(*),usuarios_id FROM comentarios WHERE usuarios_id= $id_usuario";
            $resultado2= $conexion->query($sql);
            
            if ($resultado2->fetch_assoc()['COUNT(*)'] > 3) {
                $sql= "UPDATE usuarios SET destacado=1 WHERE id= $id_usuario ";
                $resultado3 = $conexion->query($sql);
            }
            */

            header('Location: libro.php?id='.$id_libro);
        }  catch(Exception $e) {
            $_SESSION['errores'] .= '<li>Error de la base de datos.</li>';
            header('Location: libro.php?id='.$id_libro);
        }
    