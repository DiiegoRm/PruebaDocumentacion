<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once "../includes/user.class.inc.php";

switch($_REQUEST["mode"]){
 case 'query':
	$appuser = getAppUser();
	$id=isset ($_POST['id'])?$_POST['id']:"";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$filter=isset ($_POST['filter'])?$_POST['filter']:"";

	$sql = "SELECT id,nombre FROM comuna WHERE idpoligono=$id and active='Si'";
  
	if($filter=="VB"){
		$sql .= $appuser->getcomunaFilterftthVB("id","p.");
	} else if($filter=="OT"){
		$sql .= $appuser->getcomunaFilterftthVB("id","p.");
	}
	$query = db_query($sql,true);
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
