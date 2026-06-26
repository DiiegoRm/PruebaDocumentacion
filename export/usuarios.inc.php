<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include('../includes/static.inc.php');
include('../includes/database.php');
include('../includes/global.php');
include('../includes/PHPExcel/PHPExcel.php');

$sql = "SELECT u.id,u.login,u.nombre,u.active,g.nombre groupname, 
    IFNULL(e.nombre,'-') eecc,
    IFNULL(s.nombre,'-') segmento,
	c.idjefatura,IFNULL(j.nombre,'-') jefatura,
	c.idregion,IFNULL(r.nombre,'-') region,
	c.idzona,IFNULL(z.nombre,'-') zona,
	c.iddepto,IFNULL(d.nombre,'-') depto,
	c.idlocalidad,IFNULL(l.nombre,'-') localidad,
	c.idsector,IFNULL(st.nombre,'-') sector
    
   FROM `usuarios` u, `grupos` g, `configuracion` c 
   LEFT JOIN eecc e ON (c.ideecc=e.id)
   LEFT JOIN segmentos s ON (c.idsegmento=s.id)
   LEFT JOIN jefaturas j ON (c.idjefatura=j.id)
   LEFT JOIN regiones r ON (c.idregion=r.id)
   LEFT JOIN zonas z ON (c.idzona=z.id)
   LEFT JOIN deptos d ON (c.iddepto=d.id)
   LEFT JOIN localidades l ON (c.idlocalidad=l.id)
   LEFT JOIN sectores st ON (c.idsector=st.id)
   WHERE u.idgrupo = g.id AND c.idusuario = u.id ".$_SESSION['filters'];

$query = db_query($sql);
$i=0;

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Everis LTDA")
                            ->setLastModifiedBy("Everis LTDA")
                            ->setTitle("Usuarios")
                            ->setSubject("Listado de Usuarios.")
                            ->setDescription("Listado de Usuarios.");
// Add some data
$objPHPExcel->setActiveSheetIndex(0);
//Ancho de las columnas
$objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setAutoSize(true);
 
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'LOGIN')
            ->setCellValue('C1', 'NOMBRE')
            ->setCellValue('D1', 'ACTIVE')
            ->setCellValue('E1', 'GRUPO')
            ->setCellValue('F1', 'EECC')
            ->setCellValue('G1', 'JEFATURA')
            ->setCellValue('H1', 'REGION')
            ->setCellValue('I1', 'ZONA')
            ->setCellValue('J1', 'DEPARTAMENTO')
      ->setCellValue('N1', ' ');

// Escribir Excel
$i = 2;
while ($row = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
    $objPHPExcel->getActiveSheet()->getCell("A".$i)->setValue($row['id']);
    $objPHPExcel->getActiveSheet()->getCell("B".$i)->setValue($row['login']);
    $objPHPExcel->getActiveSheet()->getCell("C".$i)->setValue($row['nombre']);
    $objPHPExcel->getActiveSheet()->getCell("D".$i)->setValue($row['active']);
    $objPHPExcel->getActiveSheet()->getCell("E".$i)->setValue($row['groupname']);
    $objPHPExcel->getActiveSheet()->getCell("F".$i)->setValue($row['eecc']); 
    $objPHPExcel->getActiveSheet()->getCell("G".$i)->setValue($row['jefatura']); 
    $objPHPExcel->getActiveSheet()->getCell("H".$i)->setValue($row['region']); 
    $objPHPExcel->getActiveSheet()->getCell("I".$i)->setValue($row['zona']);
    $objPHPExcel->getActiveSheet()->getCell("J".$i)->setValue($row['depto']);   
    $i++;
}

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Usuarios.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
