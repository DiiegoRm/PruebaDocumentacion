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
	//$id=isset ($_POST['id'])?$_POST['id']:""; Kiuwan
  $id=isset ($_POST['id'])?mysqli_real_escape_string($dbsgp,$_POST['id']):"";
	$filter=isset ($_POST['filter'])?$_POST['filter']:"";
	$sql = "SELECT A.id,A.codigo FROM armarios A
			INNER JOIN distribuidores D
			  ON A.idDistribuidor=D.codigo
			WHERE D.id= ORDER BY A.codigo";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['codigo']."^";
			$result.=$row['codigo'];
		}
		echo $result;
	}
	else echo "NO";
} // end switch
?>
