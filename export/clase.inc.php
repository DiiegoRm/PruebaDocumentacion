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
                                ->setTitle("Clase")
                                ->setSubject("Clase Baremo.")
                                ->setDescription("Clase Baremo.");
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0);
    //Ancho de las columnas
    $objPHPExcel->getActiveSheet(0)->getColumnDimension('A')->setAutoSize(true);
     
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'NOMBRE')
                ->setCellValue('B1', 'UNIDAD')
          ->setCellValue('C1', ' ');

    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet(0)->setTitle("Clase Baremo");

    $tipo = "PB,MA,%";

    $objValidation = $objPHPExcel->getActiveSheet()->getCell('B2')->getDataValidation();
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
    $objValidation->setFormula1('"'.$tipo.'"');

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Clase Baremo.xlsx"');
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