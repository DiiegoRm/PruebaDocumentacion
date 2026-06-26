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
                                ->setTitle("ipc")
                                ->setSubject("IPC impuestos.")
                                ->setDescription("IPC impuestos.");

    // Pestaña 1

    // Add some data
    $objPHPExcel->setActiveSheetIndex(0);
    //Ancho de las columnas
    $objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setAutoSize(true);
     
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'VALUE')
                ->setCellValue('B1', 'FECHA DE INICIO')
                ->setCellValue('C1', 'FECHA DE CULMINACION')
                ->setCellValue('D1', 'ID DEL CONTRATO')
          ->setCellValue('E1', ' ');

    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet(0)->setTitle("IPC");

   // Pestana 3
    $sheet2 = $objPHPExcel->getActiveSheet();
    $objWorkSheet2 = $objPHPExcel->createSheet(2);

    //Write cells
    $objWorkSheet2->setCellValue('A1', 'ID')
    ->setCellValue('B1', 'NUMERO')
    ->setCellValue('C1', 'EECC')
    ->setCellValue('D1', 'ZONA');

    // Arreglar la consulta no son materiales si no los contratos de las eecc
    $sql = "SELECT 
    c.id, c.numero, e.nombre eecc, z.nombre zona
    FROM eecc e, contratos c, zonas z 
    WHERE e.id = c.ideecc AND z.id = c.idzona AND c.active='Si'";

    $query = @db_query($sql);
    $i = 2;
    while($row = mysqli_fetch_array($query)) {
      $objWorkSheet2->setCellValue("A" . $i, $row['id'])
        ->setCellValue("B" . $i, $row['numero'])
        ->setCellValue("C" . $i, $row['eecc'])
        ->setCellValue("D" . $i, $row['zona']);
      $i++;
    }

    $objWorkSheet2->setTitle("CONTRATOS");

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="IPC.xlsx"');
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