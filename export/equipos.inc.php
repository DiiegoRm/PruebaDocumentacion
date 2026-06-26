<?php 
    include('../includes/static.inc.php');
    include('../includes/database.php');

    $sqlEecc = "SELECT DISTINCT(ecc.id), ecc.serial, ee.nombre empresa, 
    m.nombre marca, te.nombre NME,dp.nombre depto, ecc.calibrado, ecc.auditado, 
    ecc.fecha_calibracion FC, ecc.fecha_vencimiento FV, ecc.active, ecc.detalle 
    FROM tipoequipo te
    JOIN equipos_ecc ecc ON ecc.funcionalidad = te.id 
    JOIN deptos dp ON dp.id = ecc.depto_id 
    JOIN eecc ee ON ee.id = ecc.eecc_id 
    JOIN marca m ON m.id = ecc.marca_id 
    JOIN configuracion con ON con.ideecc = ecc.eecc_id";

    $resultEecc = db_query($sqlEecc, true);

    unlink('equipos2.txt');
    $file = fopen('equipos2.txt', 'aw');
    // header
    $header = "ID; EECC; DEPARTAMENTO; FUNCIONALIDAD; MARCA; SERIAL; FECHA CALIBRACION; FECHA VENCIMIENTO; AUDITORIA;\r\n";
    fwrite($file, $header);
    while($row = mysqli_fetch_array($resultEecc)) {

        $data = $row['id'] . ";". $row['empresa'].";".$row['depto'].";".$row['NME'].
                ";".$row['marca'].";".$row['serial'].";". $row['FC'].";".$row['FV'].
                ";".$row['auditado'].";\r\n";
        fwrite($file, $data);
        
    }

    fclose($file);