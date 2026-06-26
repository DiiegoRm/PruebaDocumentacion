<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
switch($_REQUEST["mode"]){
 case 'query':
	include_once "../includes/global.php";
	include_once "../includes/database.php";
	$id=isset ($_POST['id'])?$_POST['id']:"";

	$sql = "SELECT r.id,r.nombre FROM zonas r, contratos c WHERE c.idzona=r.id AND c.id=$id";
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
