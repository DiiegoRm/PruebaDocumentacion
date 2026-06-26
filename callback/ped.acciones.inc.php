<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';
include_once "../includes/PHPExcel/PHPExcel.php";

switch($_REQUEST["mode"]) {
 case 'gestionar':
	$id = getVal($_POST['id'],"null");//
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$user = getAppUser();

	$sql = "UPDATE `pedidosxorden` SET idestadoped=$PED_ST_GESTIONADO,notas='Pedido Gestionado',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0){
		echo "OK";
	}
	else echo "No fue posible Gestionar el Pedido.";
	break;
 case 'gestionarmas':
	$user = getAppUser();
	foreach($_POST as $key=>$value){
		if(strpos($key,"ped_") === 0){
			$sql = "UPDATE `pedidosxorden` SET idestadoped=$PED_ST_GESTIONADO,notas='Pedido Gestionado',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$value";
			db_query($sql,true);
		}
	}
	echo "OK";
	break;
 case 'entregar':
	$id = getVal($_POST['id'],"null");//
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$fecha = getStrVal($_POST['fecha'],"null");
	$traslado = getStrVal($_POST['traslado'],"null");//
  $traslado=mysqli_real_escape_string($dbsgp,$traslado);//KIUWAN
	$user = getAppUser();

	$sql = "UPDATE `pedidosxorden` SET idestadoped=$PED_ST_ENTREGADO,fecha_entrega=$fecha,traslado=$traslado,notas='Pedido Entregado',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasped WHERE idpedido=$id",true);
		echo "OK";
	}
	else echo "No fue posible Entregar el Pedido.";
	break;
 case 'entregarmas':
	$fecha = getStrVal($_POST['fecha'],"null");
	$traslado = getStrVal($_POST['traslado'],"null");//
  $traslado=mysqli_real_escape_string($dbsgp,$traslado);//KIUWAN
	$user = getAppUser();
	foreach($_POST as $key=>$value){
		if(strpos($key,"ped_") === 0){
			$sql = "UPDATE `pedidosxorden` SET idestadoped=$PED_ST_ENTREGADO,fecha_entrega=$fecha,traslado=$traslado,notas='Pedido Entregado',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$value";
			if (db_query($sql,true) > 0){
				db_query("DELETE FROM bandejasped WHERE idpedido=$value",true);
			}
		}
	}
	echo "OK";
	break;
 case 'cancelar':
	$id = getVal($_POST['id'],"null");//
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$user = getAppUser();

	$sql = "UPDATE `pedidosxorden` SET idestadoped=$PED_ST_CANCELADO,notas='Pedido Cancelado',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasped WHERE idpedido=$id",true);
		echo "OK";
	}
	else echo "No fue posible Cancelar el Pedido.";
	break;
 case 'cancelarmas':
	$user = getAppUser();
	foreach($_POST as $key=>$value){
		if(strpos($key,"ped_") === 0){
			$sql = "UPDATE `pedidosxorden` SET idestadoped=$PED_ST_CANCELADO,notas='Pedido Cancelado',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$value";
			if (db_query($sql,true) > 0){
				db_query("DELETE FROM bandejasped WHERE idpedido=$value",true);
			}
		}
	}
	echo "OK";
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
        $file = fopen(LOG_FILE_PATH . DIRECTORY_SEPARATOR . "imp_peps.log", "w");
		for($row = 2; $row <= $lastRow; $row++) {             
            $cellA = $worksheet->getCell('A'.$row)->getValue();
            $cellB = $worksheet->getCell('B'.$row)->getValue();
			$cellC = $worksheet->getCell('C'.$row)->getValue();
			$cellD = $worksheet->getCell('D'.$row)->getValue();
			$cellE = $worksheet->getCell('E'.$row)->getValue();
			$cellF = $worksheet->getCell('F'.$row)->getValue();
			$cellG = $worksheet->getCell('G'.$row)->getValue();
			$cellH = $worksheet->getCell('H'.$row)->getValue();
			$cellI = $worksheet->getCell('I'.$row)->getValue();

                if($cellA != '') {

						$sql = "INSERT INTO `peps` (idclase,nombre,mo,cable,otros,periodo,tipoobra,idtipored,tipoot) 
						VALUES (". $cellA.",'".$cellB."','".$cellC."','".$cellD."','".$cellE."','".$cellF."','".$cellG."',". $cellI.",'". $cellH."')";

                        $sql_udpate = db_query($sql);
						$message = $row . '| Success - Se ha creado esta relación satisfactoriamente (idclase = '. $cellA .', nombre = '. $cellB .', mo = '. $cellC .', cable = '. $cellD .', otros = '. $cellE .', periodo = '. $cellF .', tipoobra = '. $cellG .', tipoot = '. $cellH .', tipored = '. $cellI .').';
                        array_push($success, $message);
                } else {
                    $message = $row . '| Error - Uno de los valores o ambos está vacio (idclase = '. $cellA .', nombre = '. $cellB .', mo = '. $cellC .', cable = '. $cellD .', otros = '. $cellE .', periodo = '. $cellF .', tipoobra = '. $cellG .', tipoot = '. $cellH .', tipored = '. $cellI .').';
                    array_push($errors, $message);
                }
            
            fwrite($file, $message . "\r\n");
		}
        
        unlink($tmpfname);
        $result = array("success" => $success, "errors" =>$errors);
        echo json_encode($result);

    break;
} // end switch
?>
