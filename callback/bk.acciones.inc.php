<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';
switch($_REQUEST["mode"]){
 case 'backup':
		$pathFile = BACKUP_PATH. DIRECTORY_SEPARATOR;
		$backupFile = "bk.gestot_".date("Y-m-d-H-i-s").".sql";
		$handle = fopen($pathFile.$backupFile,'w+');

		$dump = "SET FOREIGN_KEY_CHECKS = 0;\n";
		$dump .= "SET UNIQUE_CHECKS=0;\n";
		$rows = 0;
		$bytes = 0;
		$count = 1;
		$_SESSION['BK_ACTION'] = "Exportando tablas...";
		$q = db_query("SHOW TABLE STATUS FROM " . MYSQL_DB_NAME);
		$tables=mysqli_num_rows($q);
		//fwrite($handle,$dump);
		while($r = mysqli_fetch_array($q)) {
			if($r['Name'] != "backups"){
				$rows += $r['Rows'];
				$bytes += $r['Data_length'] + $r['Index_length'];
				$dump = getTableDef($r['Name']);
				$dump .= getTableData($r['Name']);
				fwrite($handle,$dump);
			}
			session_start();
			$_SESSION['BK_PROGRESS'] = (($count++) / $tables) * 90.00;
			session_write_close();
		}
		//Triggers
		$_SESSION['BK_ACTION'] = "Exportando otros...";
		$result = db_query('SHOW TRIGGERS');
		$triggers=mysqli_num_rows($result);
		$count = 1;
		$dump = "\nDELIMITER |\n";
		//fwrite($handle,$dump);
		while($row = mysqli_fetch_row($result)){
			$dump = "DROP TRIGGER IF EXISTS ".$row[0].";\n";
			$row2 = mysqli_fetch_row(db_query('SHOW CREATE TRIGGER '.$row[0]));
			$dump.= "CREATE ".substr($row2[2],strpos($row2[2],"TRIGGER"))."|\n";
			fwrite($handle,$dump);
			session_start();
			$_SESSION['BK_PROGRESS'] = 90.00 + ((($count++) / $triggers) * 10.00);
			session_write_close();
		}
		$dump = "DELIMITER ;\n";
		$dump .= "SET UNIQUE_CHECKS=1;\n";
		$dump .= "SET FOREIGN_KEY_CHECKS = 1;\n";
		fwrite($handle,$dump);
		//Write to file
		$zippedFle = $pathFile.$backupFile.".zip";
		fclose($handle);
		//zip file
		$_SESSION['BK_ACTION'] = "Comprimiendo archivo...";
		$zip = new ZipArchive();
    if($zip->open($zippedFle,ZIPARCHIVE::CREATE) === true) {
      $zip->addFile($pathFile.$backupFile,$backupFile);
    }
    $zip->close();
		session_start();
		$_SESSION['BK_ACTION'] = "Terminado! $tables tablas, ~ ".number_format($rows)." registros y ".humanFileSize($bytes);
		$_SESSION['BK_PROGRESS'] = "100";
		session_write_close();
		$user = getAppUser();
		$sql = "INSERT INTO `backups`(archivo,version,create_user,active) VALUES('$backupFile','" . DB_VERSION . "',$user->uid,'Si')";
		if (db_query($sql,true) >= 0){
			echo $_SESSION['BK_ACTION'];
		} else {
			echo "Error al generar el backup, contacte al administrador del sistema.";
		}
		//echo $dump;

	break;
 case 'restore':
		$pathFile = BACKUP_PATH. DIRECTORY_SEPARATOR;
		$backupFile = getPostNum('file');
		$handle = fopen(realpath(basename($pathFile.$backupFile)),'r');

		$size = filesize($pathFile.$backupFile);
		$count = 0;
		$lineas = 0;
		$sql = "";
		$delimiter = ";";
		$_SESSION['BK_PROGRESS'] = "0";
		if ($handle) {
			while (($bufer = fgets($handle, 4096)) !== false) {
				//Omitir lineas en blanco y comentarios
				if (strlen(trim($bufer)) > 0 && strpos($bufer,"-- ") === false){
					//Validar si hay un nuevo Delimitador
					$posdel = strpos($bufer,"DELIMITER ");
					if($posdel !== false){
						$delimiter = substr($bufer,$posdel+10,1);
						db_query($bufer);
						$count++;
					} else {
						if(strpos($bufer,$delimiter)){
							$sql .= $bufer;
							if(++$count%CHUNK_ROWS == 0){
								$pos = ftell($handle);
								session_start();
								$_SESSION['BK_ACTION'] = "Procesados $count instrucciones...";
								//$_SESSION['BK_PROGRESS'] = $pos/$size*100.00;
                $possize =$pos/$size*100.00;;
          			preg_match("/(\d{4})-(\d{2})-(\d{2})/", $possize, $results1);
          			if(sizeof($results1) > 0){
                  $_SESSION['BK_PROGRESS'] = $possize;
                }
								session_write_close();
							}
							db_query($sql);
							$sql ="";
						} else {
							$sql .= $bufer;
						}
					}
				}
				$lineas++;
			}
			fclose($handle);
		} else {
			echo "No se puede abrir el archivo, consulte al administrador del sistema.";
		}
		session_start();
		//$_SESSION['BK_ACTION'] = "Finalizado, ".humanFileSize($size).", ".number_format($lineas)." lineas, ".number_format($count)." instrucciones.";
    $BK_ACTION="Finalizado, ".humanFileSize($size).", ".number_format($lineas)." lineas, ".number_format($count)." instrucciones.";
    preg_match("/(\d{4})-(\d{2})-(\d{2})/", $BK_ACTION, $results2);
    if(sizeof($results2) > 0){
      $_SESSION['BK_PROGRESS'] = $BK_ACTION;
    }
		$_SESSION['BK_PROGRESS'] = "100";
		session_write_close();
	break;
} // end switch
?>
