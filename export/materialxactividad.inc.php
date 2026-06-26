<?php 
    ob_start();
    include_once "../includes/session.php";
    sessionCheck();
    include('../includes/static.inc.php');
    include('../includes/database.php');
    include('../includes/PHPExcel/PHPExcel.php');

  // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Everis LTDA")
                                ->setLastModifiedBy("Everis LTDA")
                                ->setTitle("MaterialesxActividad")
                                ->setSubject("Materiales por actividad.")
                                ->setDescription("Materiales por actividad.");

    // Pestaña 1

    // Add some data
    $objPHPExcel->setActiveSheetIndex(0);
    //Ancho de las columnas
    $objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setAutoSize(true);
     
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'MATERIAL')
                ->setCellValue('B1', 'ACTIVIDAD')
          ->setCellValue('D1', ' ');

    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet(0)->setTitle("MaterialxActividad");

    // Pestana 2
    $sheet = $objPHPExcel->getActiveSheet();
    $objWorkSheet = $objPHPExcel->createSheet(1);
    //Write cells
    $objWorkSheet->setCellValue('A1', 'ID')
    ->setCellValue('B1', 'MATERIAL');

    // Consultar Materiales
    $sql = "SELECT id,CONCAT(codigo,' | ',item) nombre,active 
            FROM material WHERE id > 0 AND
            active = 'Si' 
            ORDER BY codigo";

    $query = @db_query($sql);
    $i = 2;
    while($row = mysqli_fetch_array($query)) {
      $objWorkSheet->setCellValue("A" . $i, $row['id'])
        ->setCellValue("B" . $i, $row['nombre']);
      $i++;
    }

    $objWorkSheet->setTitle("Materiales");

    // Pestana 3
    $sheet2 = $objPHPExcel->getActiveSheet();
    $objWorkSheet2 = $objPHPExcel->createSheet(2);

    //Write cells
    $objWorkSheet2->setCellValue('A1', 'ID')
    ->setCellValue('B1', 'ACTIVIDAD');

    $sql = "SELECT b.id, 
            CONCAT(b.item,' | ',b.descripcion,' | ',e.nombre) nombre,
            b.active 
            FROM baremo b 
            INNER JOIN preciosbaremo p ON p.idclase=b.idclase 
            INNER JOIN eecc e on e.id=p.ideecc WHERE b.item!='0' 
            AND b.active='Si' 
            ORDER BY b.item";

    $query = @db_query($sql);
    $i = 2;
    while($row = mysqli_fetch_array($query)) {
      $objWorkSheet2->setCellValue("A" . $i, $row['id'])
        ->setCellValue("B" . $i, $row['nombre']);
      $i++;
    }

    $objWorkSheet2->setTitle("Actividad");

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="MaterialesxActividad.xlsx"');
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