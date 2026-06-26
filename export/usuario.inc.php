<?php
error_reporting(0);
ini_set('display_errors', False);
ini_set('display_startup_errors', False);

ob_start();

include_once "../includes/session.php";
sessionCheck();
include('../includes/static.inc.php');
include('../includes/database.php');
include('../includes/PHPExcel/PHPExcel.php');

function createDropDownList($objPHPExcel, $cell, $config){
  $objValidation = $objPHPExcel->getActiveSheet()->getCell($cell)->getDataValidation();
  $objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
  $objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
  $objValidation->setAllowBlank(false);
  $objValidation->setShowInputMessage(true);
  $objValidation->setShowErrorMessage(true);
  $objValidation->setShowDropDown(true);
  $objValidation->setErrorTitle('Input error');
  $objValidation->setError('Value is not in list.');
  $objValidation->setPromptTitle('Seleccione un valor');
  $objValidation->setPrompt('Debe seleccionar un valor de la lista.');
  $objValidation->setFormula1($config);
}

function getDatatoList($sql, $objPHPExcel, $objWorkSheet, $init, $name){
  unset($result);
  $query = db_query($sql, true);
  if(mysqli_num_rows($query) > 0){
    $i = 0;
    while ($row = mysqli_fetch_array($query)) {
      $i++;
      $objWorkSheet->setCellValue($init.$i, trim(strtoupper(str_replace("(","-",str_replace(")","-",$row['id']."|".$row['nombre'])))));
    }
    $objPHPExcel->addNamedRange( 
      new PHPExcel_NamedRange(
          $name, 
          $objWorkSheet, 
          $init.'1:'.$init.$i,
          false,
          NULL
      ) 
    );
    return true;
  }
  return false;
}
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()
						->setTitle("Plantilla usuarios")
						->setDescription("Plantilla usuarios")
						->setKeywords("Plantilla usuarios");

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'DOC. IDENTIDAD')
            ->setCellValue('B1', 'USUARIO')
            ->setCellValue('C1', 'NOMBRE')
            ->setCellValue('D1', 'TELEFONO')
            ->setCellValue('E1', 'EMAIL')
            ->setCellValue('F1', 'GRUPO')
              ->setCellValue('G1', 'TIPO')

              ->setCellValue('H1', 'REGION')

              ->setCellValue('I1', 'ZONA')
              ->setCellValue('I2', 'SELECCIONE')
              ->setCellValue('J1', 'JEFATURA')
              ->setCellValue('J2', 'SELECCIONE')
              ->setCellValue('K1', 'DEPARTAMENTO')
              ->setCellValue('K2', 'SELECCIONE')
              ->setCellValue('L1', 'LOCALIDAD')
              ->setCellValue('L2', 'SELECCIONE')
              ->setCellValue('M1', 'SECTOR')
              ->setCellValue('M2', 'SELECCIONE')
              ->setCellValue('N1', 'EECC')
              ->setCellValue('N2', 'SELECCIONE')
              ->setCellValue('O1', 'SEGMENTO')
              ->setCellValue('O2', 'SELECCIONE');

/* Creacion de los campos calculados*/
$objWorkSheet = $objPHPExcel->createSheet(1);
$objWorkSheet->setTitle("Detalle");

$objWorkSheet->SetCellValue("G1", "VIABILIDAD")
             ->SetCellValue("G2", "ORDENES");

$objPHPExcel->addNamedRange( 
  new PHPExcel_NamedRange(
      'tipos', 
      $objWorkSheet, 
      'G1:G2'
  ) 
);


createDropDownList($objPHPExcel, 'G2', '=tipos');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'ORDENES');

$sql = getDatatoList("SELECT id,concat(nombre, ' ', CASE WHEN id= $GRP_SEG_CONSULTA or id=$GRP_SEG_REGISTRO or id=$GRP_SEG_CIERRE THEN 'Segmento' WHEN id=$GRP_EECC THEN 'Contratista' ELSE 'Otro' END ) nombre FROM grupos WHERE active='Si' AND id > 1",
                    $objPHPExcel, $objWorkSheet, 'F', 'perfil');
createDropDownList($objPHPExcel, 'F2', '=perfil');

$sql = getDatatoList("SELECT id,nombre FROM regiones WHERE active='SI'", $objPHPExcel, $objWorkSheet, 'H', 'viabilidad');
createDropDownList($objPHPExcel, 'H2', '=viabilidad');

$sql = getDatatoList("SELECT id,nombre FROM zonas WHERE active='SI'", $objPHPExcel, $objWorkSheet, 'I', 'zonas');
createDropDownList($objPHPExcel, 'I2', "=zonas");

$sql = getDatatoList("SELECT j.id,j.nombre FROM jefaturas j, regiones r WHERE j.idregion = r.id", $objPHPExcel, $objWorkSheet, 'J', 'jefaturas');
createDropDownList($objPHPExcel, 'J2', '=jefaturas');

$sql = getDatatoList("SELECT distinct(d.id),d.nombre FROM deptos d, deptosxjefatura dj WHERE dj.iddepto=d.id", $objPHPExcel, $objWorkSheet, 'K', 'deptos');
createDropDownList($objPHPExcel, 'K2', '=deptos');

$sql = getDatatoList("SELECT id,nombre FROM localidades WHERE active='SI'", $objPHPExcel, $objWorkSheet, 'L', 'localidades');
createDropDownList($objPHPExcel, 'L2', '=localidades');

$sql = getDatatoList("SELECT id,nombre FROM sectores WHERE active='SI'", $objPHPExcel, $objWorkSheet, 'M', 'sectores');
createDropDownList($objPHPExcel, 'M2', '=sectores');

$sql = getDatatoList("SELECT id,nombre FROM eecc WHERE active='SI'", $objPHPExcel, $objWorkSheet, 'N', 'empresas');
createDropDownList($objPHPExcel, 'N2', "=empresas");

$sql = getDatatoList("SELECT id,nombre FROM segmentos WHERE active='SI'", $objPHPExcel, $objWorkSheet, 'O', 'segmentos');
createDropDownList($objPHPExcel, 'O2', '=segmentos');


            
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Usuarios');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>