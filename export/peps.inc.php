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
                                ->setTitle("Materiales")
                                ->setSubject("Materiales por actividad.")
                                ->setDescription("Materiales por actividad.");
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0);
    //Ancho de las columnas
    $objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setAutoSize(true);
     
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'PROYECTO')
                ->setCellValue('B1', 'NOMBRE')
                ->setCellValue('C1', 'MANO OBRA')
                ->setCellValue('D1', 'CABLE')
                ->setCellValue('E1', 'OTROS')
                ->setCellValue('F1', 'PERIODO')
                ->setCellValue('G1', 'TIPO OBRA')
                ->setCellValue('H1', 'TIPO OT')
                ->setCellValue('I1', 'TIPO RED')
          ->setCellValue('J1', ' ');
  
   // Terminar combo
   $tipoobra = "Ampliacion,Reposicion,Inventario,Otros";
   $objValidation = $objPHPExcel->getActiveSheet()->getCell('G2')->getDataValidation();
   $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
   $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
   $objValidation->setAllowBlank(false);
   $objValidation->setShowInputMessage(true);
   $objValidation->setShowErrorMessage(true);
   $objValidation->setShowDropDown(true);
   $objValidation->setErrorTitle('Error de entrada');
   $objValidation->setError('El valor no está en la lista.');
   $objValidation->setPromptTitle('Elija de la lista');
   $objValidation->setPrompt('Elija un valor de la lista desplegable.');
   $objValidation->setFormula1('"'.$tipoobra.'"');

   $tipoot = "OPEX,CAPEX";
   $objValidation = $objPHPExcel->getActiveSheet()->getCell('H2')->getDataValidation();
   $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
   $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
   $objValidation->setAllowBlank(false);
   $objValidation->setShowInputMessage(true);
   $objValidation->setShowErrorMessage(true);
   $objValidation->setShowDropDown(true);
   $objValidation->setErrorTitle('Error de entrada');
   $objValidation->setError('El valor no está en la lista.');
   $objValidation->setPromptTitle('Elija de la lista');
   $objValidation->setPrompt('Elija un valor de la lista desplegable.');
   $objValidation->setFormula1('"'.$tipoot.'"');

   // Cambiar el nombre de hoja de cálculo
   $objPHPExcel->getActiveSheet(0)->setTitle("PEPS");
          
        // Pestana 2
        $sheet = $objPHPExcel->getActiveSheet();
        $objWorkSheet = $objPHPExcel->createSheet(1);
        //Write cells
        $objWorkSheet->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'NOMBRE');

        // Consultar Materiales
        $sql = "SELECT id,nombre,active FROM claseproyecto WHERE active = 'Si'";

        $query = @db_query($sql);
        $i = 2;
        while($row = mysqli_fetch_array($query)) {
          $objWorkSheet->setCellValue("A" . $i, $row['id'])
            ->setCellValue("B" . $i, $row['nombre']);
          $i++;
        }
        $objWorkSheet->setTitle("Proyectos");

      // Pestana 3
      $sheet = $objPHPExcel->getActiveSheet();
      $objWorkSheet = $objPHPExcel->createSheet(1);
      //Write cells
      $objWorkSheet->setCellValue('A1', 'ID')
      ->setCellValue('B1', 'NOMBRE');

      // Consultar Materiales
      $sql = "SELECT id,nombre,active FROM tipored WHERE active='Si'";

      $query = @db_query($sql);
      $i = 2;
      while($row = mysqli_fetch_array($query)) {
        $objWorkSheet->setCellValue("A" . $i, $row['id'])
          ->setCellValue("B" . $i, $row['nombre']);
        $i++;
      }
      $objWorkSheet->setTitle("Tipo de Red");

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="PEPS.xlsx"');
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