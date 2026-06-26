<?php
ob_start();
include_once "../includes/session.php";
include_once "../includes/database.php";
include_once "../includes/global.php";
include_once "../includes/static.inc.php";
include_once "../includes/PHPExcel/PHPExcel.php";

sessionCheck();
switch($_REQUEST["mode"]){
 case 'query':
	
	$id=isset ($_POST['id'])?$_POST['id']:"";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$sql = "SELECT unidad,tipo,valor FROM material WHERE id=$id";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		if ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['unidad']."^";
			$result.=$row['tipo']."^";
			$result.=$row['valor'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
	break;

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
        $file = fopen(LOG_FILE_PATH . DIRECTORY_SEPARATOR . "imp_material.log", "w");
		for($row = 2; $row <= $lastRow; $row++) {             
            $cellA = $worksheet->getCell('A'.$row)->getValue();
            $cellB = $worksheet->getCell('B'.$row)->getValue();
			$cellC = $worksheet->getCell('C'.$row)->getValue();
			$cellD = $worksheet->getCell('D'.$row)->getValue();
			$cellE = $worksheet->getCell('E'.$row)->getValue();
			$cellF = $worksheet->getCell('F'.$row)->getValue();
			$cellG = $worksheet->getCell('G'.$row)->getValue();
			$cellH = $worksheet->getCell('H'.$row)->getValue();

                if($cellA != '') {

                    $sql = "SELECT * FROM material WHERE codigo = '".$cellA."'";
                    $query = db_query($sql);

                    $data = mysqli_fetch_array($query);
                    if(count($data) == 0) {

                        $sql = "INSERT INTO `material` 
                            (`codigo`,`tipo`,`item`,`unidad`,`valor`,`factor1`,`factor2`,`factor3`) 
                            VALUES ('".$cellA."','".$cellB."','". $cellC."','". $cellD."',".$cellE.",".$cellF.",".$cellG.",".$cellH.")";

                        $sql_udpate = db_query($sql);
						$message = $row . '| Success - Se ha creado esta relación satisfactoriamente (codigo = '. $cellA .', tipo = '. $cellB .', item = '. $cellC .', unidad = '. $cellD .', valor = '. $cellE .', factor1 = '. $cellF .', factor2 = '. $cellG .', factor3 = '. $cellH .').';
                        array_push($success, $message);
                    } else {
                        array_push($errors, $row . '| Error - Uno de los valores o ambos está vacio (codigo = '. $cellA .', tipo = '. $cellB .', item = '. $cellC .', unidad = '. $cellD .', valor = '. $cellE .', factor1 = '. $cellF .', factor2 = '. $cellG .', factor3 = '. $cellH .').');
                    }
                } else {
                    $message = $row . '| Error - Intentando ingresar material duplicado. (codigo = '. $cellA .', tipo = '. $cellB .', item = '. $cellC .', unidad = '. $cellD .', valor = '. $cellE .', factor1 = '. $cellF .', factor2 = '. $cellG .', factor3 = '. $cellH .').';
                    array_push($errors, $row . '| Error - Uno de los valores o ambos está vacio (codigo = '. $cellA .', tipo = '. $cellB .', item = '. $cellC .', unidad = '. $cellD .', valor = '. $cellE .', factor1 = '. $cellF .', factor2 = '. $cellG .', factor3 = '. $cellH .').');
                }
            
            fwrite($file, $message . "\r\n");
		}
        
        unlink($tmpfname);
        $result = array("success" => $success, "errors" =>$errors);
        echo json_encode($result);

    break;
} // end switch
?>
