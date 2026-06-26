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
	$id=getPostNum('id','0');
	$filter=isset ($_POST['filter'])?$_POST['filter']:"";
	$sql = "SELECT d.id,d.nombre FROM deptos d, deptosxjefatura dj WHERE dj.idjefatura=$id AND dj.iddepto=d.id ";
	if($filter=="VB"){
		$sql .= $appuser->getDeptoFilterVB("id","d.");
	} else if($filter=="OT"){
		$sql .= $appuser->getDeptoFilterOT("id","d.");
	}
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['nombre'];
		}
		echo $result;
	}
	else echo "NO";
} // end switch
?>
