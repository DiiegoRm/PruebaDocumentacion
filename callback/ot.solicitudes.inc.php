<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';
switch($_REQUEST["mode"]){
 case 'query':
	$id=isset ($_POST['id'])?$_POST['id']:"";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$sql = "SELECT s.idbaremo,s.justificacion,s.valor,e.nombre estado,s.idestadosol FROM solicitudesh s, estadosol e WHERE s.idestadosol=e.id AND s.id=$id";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
    $row = mysqli_fetch_array($query);
		if (count($row)>0) {
			$result.="|";
			$result.=$row['idbaremo']."^";
			$result.=$row['justificacion']."^";
			$result.=$row['valor']."^";
			$result.=$row['estado']."^";
			$result.=$row['idestadosol'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
	break;
 case 'baremoh': // Deprecated
	$sql = "SELECT id,item,descripcion FROM baremo WHERE idclase=$ID_CLASE_H AND active='Si'";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['item']."^";
			$result.=$row['descripcion'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
	break;
 case 'solicitudes':
	$sql = "SELECT id,item,descripcion FROM baremo WHERE metodo='SOLICITUD' AND active='Si'";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['item']."^";
			$result.=$row['descripcion'];
		}
		echo $result;
	}
	else echo "NO";
	break;
 case 'cotizaciones':
	$id=isset ($_POST['id'])?$_POST['id']:"";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$sql = "SELECT id,empresa,titulo,archivo FROM cotizaciones WHERE idsolicitud=$id";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['empresa']."^";
			$result.=$row['titulo']."^";
			$result.=$row['archivo'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
	break;
case 'del':
	$id=isset ($_POST['id'])?$_POST['id']:"0";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	db_query("DELETE FROM seguimientosol WHERE idsolicitud=$id",true);
	db_query("DELETE FROM bandejash WHERE idsolicitud=$id",true);
	$sql = "DELETE FROM cotizaciones WHERE idsolicitud=$id";
	if(db_query($sql,true) >= 0){
		$sql = "DELETE FROM solicitudesh WHERE id=$id";
		if(db_query($sql,true) > 0){
			echo "OK";
		}
	}
break;
case 'save':
	$idorden=isset ($_POST['idorden'])?$_POST['idorden']:"";
	$idbaremo=isset ($_POST['item'])?$_POST['item']:"";
	$idsol=isset ($_POST['idsol'])?$_POST['idsol']:"";
	$just=isset ($_POST['just'])?$_POST['just']:"";
	$valor=isset ($_POST['valor'])?str_replace(",","",$_POST['valor']):"0";
	$user = getAppUser();

	$sql = "INSERT INTO solicitudesh(idorden,idbaremo,idestadosol,justificacion,valor,create_user,modify_user,notas) VALUES($idorden,$idbaremo,$SOL_ST_SOLICITADA,'$just',$valor,$user->uid,$user->uid,'Solicitud Creada')";
	if(db_query($sql,true) > 0){
		$idsol = getLastId();
		//Asign Trays
		db_query("INSERT INTO bandejash(idsolicitud, idgrupo) VALUES($idsol,$GRP_INGENIERIA),($idsol,$GRP_SEG_REGISTRO),($idsol,$GRP_ONMS),($idsol,$GRP_SOPORTE_TECNICO)",true);
		//print_r($_POST);
		//lo normal
		foreach($_POST as $key=>$value){
			if(strpos($key,"e_") === 0){
				$data = explode("_",$key);
				$mode = $_POST["m_$data[1]"];
				$state = $_POST["s_$data[1]"];

				if(($mode == "new" || $mode == "edit")){
					if($state=="modified"){
						$empresa = $_POST["e_$data[1]"];
            $empresa=mysqli_real_escape_string($dbsgp,$empresa);//KIUWAN
						$fileid = $_POST["i_$data[1]"];$valor=mysqli_real_escape_string($dbsgp,$valor);//KIUWAN
						$filename = preg_replace('/[^\w\._]+/', '_', $_POST["n_$data[1]"]);
						$ext = pathinfo($filename, PATHINFO_EXTENSION);
						$file = $idsol."_".$fileid.".$ext";
						if(hasVal($filename)){
							if (copy(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR. basename($filename),SOL_FILE_PATH. DIRECTORY_SEPARATOR .basename($file))) {
								$sql = "INSERT INTO cotizaciones(idsolicitud,empresa,titulo,archivo) VALUES($idsol,'$empresa','$filename','$file')";
								db_query($sql,true);
								unlink(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR .basename($filename));
							}
						} else {
								$sql = "INSERT INTO cotizaciones(idsolicitud,empresa) VALUES($idsol,'$empresa')";
								db_query($sql,true);
						}
					}/* else if($mode == "edit" && $state=="deleted"){
						$sql = "DELETE FROM materialesxorden WHERE idorden=$idorden AND idbaremo=$idbaremo AND idmaterial=$idmaterial";
						db_query($sql,true);
					}*/
				}
			}
		}
		echo "OK";
	}
	else echo "NO";
	break;
} // end switch
?>
