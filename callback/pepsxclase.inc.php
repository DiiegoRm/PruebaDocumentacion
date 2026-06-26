<?php
ob_start();
include_once "../includes/session.php";
include_once "../includes/global.php";
include_once "../includes/static.inc.php";
include_once "../includes/database.php";
include_once "../includes/user.class.inc.php";
sessionCheck();
switch($_REQUEST["mode"]){
 case 'query':

	$id=isset ($_POST['id'])?$_POST['id']:"0";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$tr=isset ($_POST['tr'])?$_POST['tr']:"";
  $tr=mysqli_real_escape_string($dbsgp,$tr);//KIUWAN
	if(hasVal($tr)){
		$tred = " AND idtipored=$tr";
		}
	$appuser=getAppUser();
if($appuser->isInRole("$GENERAR_OT_CAPEX,$GENERAR_OT_OPEX")){
					$tt = " AND tipoot IN(";
					if($appuser->isInRole("$GENERAR_OT_CAPEX")){
						$tt .="'CAPEX'";
					}
					if($appuser->isInRole("$GENERAR_OT_OPEX")){
						if($appuser->isInRole("$GENERAR_OT_CAPEX")){
							$tt .=",";
						}
						$tt .="'OPEX'";
					}
					$tt .= ")";
				}
	$sql = "SELECT id,CONCAT(tipoot,' - ',nombre,' - ',tipoobra) nombre FROM peps WHERE active='Si' AND idclase=$id $tred $tt";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['nombre'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";

} // end switch
?>
