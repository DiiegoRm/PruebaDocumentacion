<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
include_once "../includes/static.inc.php";
include_once "../includes/PHPExcel/PHPExcel.php";

switch($_REQUEST["mode"]) {
    case 'import':
        $file = isset($_FILES['files']) ? $_FILES['files'] : ""; 
        $fileTmpPath   = $file['tmp_name'];
        $fileName      = $file['name'];
        $fileSize      = $file['size'];
        $fileType      = $file['type'];
        $fileNameCmps  = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedfileExtensions = array('xls', 'xlsx');
        if(in_array($fileExtension, $allowedfileExtensions)) {
            if(copy($fileTmpPath, UPLOAD_TMP_DIR . DIRECTORY_SEPARATOR . $fileName)) {                
                $_SESSION['fileName'] = $fileName;
                echo 'Ok';
            } else {
                echo 'Documento no ha podido ser procesado.';
            }
        } else {
            echo 'Documento no permitido debe ser xls o xlsx.';
        }        
    break;
    case 'process':
        $tmpfname = UPLOAD_TMP_DIR . DIRECTORY_SEPARATOR . $_SESSION['fileName'];
        $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
        $excelObj = $excelReader->load($tmpfname);
		$worksheet = $excelObj->getSheet(0);
		$lastRow = $worksheet->getHighestRow();        
        
        $errors = Array();
        $success = Array();
        $file = fopen(LOG_FILE_PATH . DIRECTORY_SEPARATOR . "imp_clase.log", "w");
		for($row = 2; $row <= $lastRow; $row++) {             
            $cellA = $worksheet->getCell('A'.$row)->getValue();
            $cellB = $worksheet->getCell('B'.$row)->getValue();

                if(($cellA != '') && ($cellB != '')) {
                        
                        $sql_update = db_query("INSERT INTO `clasemanoobra` (`nombre`,`unidad`) VALUES ('".$cellA."','". $cellB."')");
                        $message = $row . '| Success - Se ha creado esta relación satisfactoriamente (nombre = '. $cellA .', unidad = '. $cellB .').';
                            array_push($success, $message);
                       
                } else {
                    $message = $row . '| Error - Uno de los valores o ambos está vacio (nombre = '. $cellA .', unidad = '. $cellB .').';
                    array_push($errors, $row . '| Error - Uno de los valores o ambos está vacio (nombre = '. $cellA .', unidad = '. $cellB .').');
                }
            
            fwrite($file, $message . "\r\n");
		}
        
        unlink($tmpfname);
        $result = array("success" => $success, "errors" =>$errors);
        echo json_encode($result);

    break;
}