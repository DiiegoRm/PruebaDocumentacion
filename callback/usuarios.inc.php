<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once "../includes/user.class.inc.php";
include_once "../includes/static.inc.php";
include_once "../includes/PHPExcel/PHPExcel.php";

switch($_REQUEST["mode"]) {

    case 'findContrato':
        $response = db_query("SELECT DISTINCT con.id, con.numero AS nombre, con.active FROM contratos con INNER JOIN ipc pc ON pc.idcontrato = con.id WHERE con.ideecc = " . $_POST['id']);
        $datos = [];
        $datos = "<option value=''>---SELECCIONE---</option>";
        while($data = mysqli_fetch_array($response)){
            $datos .= "<option value='".$data['id']."'>".$data['nombre']."</option>";
        }
        echo json_encode([$datos]);
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
        $file = fopen(LOG_FILE_PATH . DIRECTORY_SEPARATOR . "imp_usuario.log", "w");
		for($row = 2; $row <= $lastRow; $row++) {             
            $cellA = $worksheet->getCell('A'.$row)->getValue();
            $cellB = $worksheet->getCell('B'.$row)->getValue();
            $cellC = $worksheet->getCell('C'.$row)->getValue();
            $cellD = $worksheet->getCell('D'.$row)->getValue();
            $cellE = $worksheet->getCell('E'.$row)->getValue();
            $cellF = $worksheet->getCell('F'.$row)->getValue();

            /* Configuracion */

            $cellG = $worksheet->getCell('G'.$row)->getValue();
            if($cellG == "ORDENES")
                $cellG = 'OT';
            if($cellG == "VIABILIDAD")
                $cellG = 'VB';

            $cellF = $worksheet->getCell('F'.$row)->getValue();
            $cellF = explode("|", $cellF);
            $cellF = $cellF[0];
    
            $cellH = $worksheet->getCell('H'.$row)->getValue();
            $cellH = explode("|", $cellH);
            $cellH = $cellH[0];
            if(empty($cellH))
                $cellH = 'null';

            $cellI = $worksheet->getCell('I'.$row)->getValue();
            $cellI = explode("|", $cellI);
            $cellI = $cellI[0];
            if(empty($cellI))
                $cellI = 'null';

            $cellJ = $worksheet->getCell('J'.$row)->getValue();
            $cellJ = explode("|", $cellJ);
            $cellJ = $cellJ[0];
            if(empty($cellJ))
                $cellJ = 'null';

            $cellK = $worksheet->getCell('K'.$row)->getValue();
            $cellK = explode("|", $cellK);
            $cellK = $cellK[0];
            if(empty($cellK))
                $cellK = 'null';

            $cellL = $worksheet->getCell('L'.$row)->getValue();
            $cellL = explode("|", $cellL);
            $cellL = $cellL[0];
            if(empty($cellL))
                $cellL = 'null';

            $cellM = $worksheet->getCell('M'.$row)->getValue();
            $cellM = explode("|", $cellM);
            $cellM = $cellM[0];
            if(empty($cellM))
                $cellM = 'null';

            $cellN = $worksheet->getCell('N'.$row)->getValue();
            $cellN = explode("|", $cellN);
            $cellN = $cellN[0];
            if(empty($cellN))
                $cellN = 'null';

            $cellO = $worksheet->getCell('O'.$row)->getValue();
            $cellO = explode("|", $cellO);
            $cellO = $cellO[0];
            if(empty($cellO))
                $cellO = 'null';

                if(($cellA != '') && ($cellB != '') && ($cellC != '') && ($cellD != '')) {
                                       
                    /* Validaciones */
                    if($cellO != 'null'){
                        unset($validacion);
                        $response = db_query("select id from segmentos where id = '".$cellO."'");
                        while($data = mysqli_fetch_array($response)){
                            $validacion = $data['id'];
                        }
                        if (empty($validacion)){
                            $message = $row . '| Error - segmento no encontrado';
                            array_push($errors, $message);
                            continue;
                        }
                    } 
                    
                    # --

                    if($cellJ != 'null'){
                        unset($validacion);
                        $response = db_query("select id from jefaturas where id = '".$cellJ."'");
                        while($data = mysqli_fetch_array($response)){
                            $validacion = $data['id'];
                        }
                        if (empty($validacion)){
                            $message = $row . '| Error - jefatura no encontrado';
                            array_push($errors, $message);
                            continue;
                        }
                    } 
                    
                    # --

                    if($cellH != 'null'){
                        unset($validacion);
                        $response = db_query("select id from regiones where id = '".$cellH."'");
                        while($data = mysqli_fetch_array($response)){
                            $validacion = $data['id'];
                        }
                        if (empty($validacion)){
                            $message = $row . '| Error - region no encontrado';
                            array_push($errors, $message);
                            continue;
                        }
                    } 
                    
                    # --

                    if($cellI != 'null'){
                        unset($validacion);
                        $response = db_query("select id from zonas where id = '".$cellI."'");
                        while($data = mysqli_fetch_array($response)){
                            $validacion = $data['id'];
                        }
                        if (empty($validacion)){
                            $message = $row . '| Error - zona no encontrado';
                            array_push($errors, $message);
                            continue;
                        }
                    } 
                    
                    # --

                    if($cellK != 'null'){
                        unset($validacion);
                        $response = db_query("select id from deptos where id = '".$cellK."'");
                        while($data = mysqli_fetch_array($response)){
                            $validacion = $data['id'];
                        }
                        if (empty($validacion)){
                            $message = $row . '| Error - departamento no encontrado';
                            array_push($errors, $message);
                            continue;
                        }
                    } 
                    
                    # --

                    if($cellL != 'null'){
                        unset($validacion);
                        $response = db_query("select id from localidades where id = '".$cellL."'");
                        while($data = mysqli_fetch_array($response)){
                            $validacion = $data['id'];
                        }
                        if (empty($validacion)){
                            $message = $row . '| Error - localidad no encontrado';
                            array_push($errors, $message);
                            continue;
                        }
                    } 
                    
                    # --

                    if($cellM != 'null'){
                        unset($validacion);
                        $response = db_query("select id from sectores where id = '".$cellM."'");
                        while($data = mysqli_fetch_array($response)){
                            $validacion = $data['id'];
                        }
                        if (empty($validacion)){
                            $message = $row . '| Error - sector no encontrado';
                            array_push($errors, $message);
                            continue;
                        }
                    } 
                    
                    # --

                    if($cellN != 'null'){
                        unset($validacion);
                        $response = db_query("select id from eecc where id = '".$cellN."'");
                        while($data = mysqli_fetch_array($response)){
                            $validacion = $data['id'];
                        }
                        if (empty($validacion)){
                            $message = $row . '| Error - eeecc no encontrado';
                            array_push($errors, $message);
                            continue;
                        }
                    } 
                 
                    /* -- end -- */
                    unset($validacion);
                    $response = db_query("select id from usuarios where id = '".$cellA."' or login = '".$cellB."'");
                    while($data = mysqli_fetch_array($response)){
                        $validacion = $data['id'];
                    }

                    if (!empty($validacion)){
                        #echo("INSERT INTO configuracion VALUES(NULL,'$cellG',$cellA,$cellO,$cellJ,$cellH,$cellI,$cellK,$cellL,$cellM,$cellN)");die();
                        #array_push($errors, $row . '| Error - El usuario o el numero de documento ya existen (Usuario = '. $cellB .', Nombre = '. $cellC .').');
                        $sql = "INSERT INTO configuracion VALUES(NULL,'$cellG',$cellA,$cellO,$cellJ,$cellH,$cellI,$cellK,$cellL,$cellM,$cellN)";
                        db_query($sql, true);
                        $message = $row . '| Success - Se ha agregado la informacion satisfactoriamente (Usuario = '. $cellB .', Nombre = '. $cellC .').';
                        array_push($success, $message);
                    }else{
                        /* echo("INSERT INTO `usuarios` 
                        (`id`,`login` , `nombre` , `password`, `idgrupo`,telefono,email,User_LDAP) 
                        VALUES ($cellA, '$cellB', '$cellC', MD5($cellA), $cellF, $cellD, '$cellE', '$cellB')");*/
                        $sql_update = db_query("INSERT INTO `usuarios` 
                        (`id`,`login` , `nombre` , `password`, `idgrupo`,telefono,email,User_LDAP) 
                        VALUES ($cellA, '$cellB', '$cellC', MD5($cellA), $cellF, $cellD, '$cellE', '$cellB')");

                        $sql = "INSERT INTO configuracion VALUES(NULL,'$cellG',$cellA,$cellO,$cellJ,$cellH,$cellI,$cellK,$cellL,$cellM,$cellN)";
                        db_query($sql, true);
                        $message = $row . '| Success - Se ha creado el usuario satisfactoriamente (Usuario = '. $cellB .', Nombre = '. $cellC .').';
                        array_push($success, $message);
                    }
                } else {
                    $message = $row . '| Error - Uno de los valores está vacio (Usuario = '. $cellB .', Nombre = '. $cellC .').';
                    array_push($errors, $message);
                }
            fwrite($file, $message . "\r\n");
		}
        unlink($tmpfname);
        $result = array("success" => $success, "errors" =>$errors);
        echo json_encode($result);

    break;
}
?>
