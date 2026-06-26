<?php 
    include_once "session.php";
    include_once "database.php";
    include_once "global.php";

    if(isLoggedIn()) {

        $nombreArchivo = $_GET['document'];
        $ruta = $_GET['ruta'];
        $nombre = $_GET['name'];
        
        $rutaArchivo = ".." . $ruta . basename($nombreArchivo);

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-Disposition: attachment; filename=$nombre"); 

        echo file_get_contents($rutaArchivo);
        die();
    } else {
        
        header("Location: ../index.php");
    }

?>