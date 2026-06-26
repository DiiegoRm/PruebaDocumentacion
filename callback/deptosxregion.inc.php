<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/global.php";
include_once "../includes/database.php";

switch($_REQUEST["mode"]){
 case 'query':
	$id=isset ($_POST['id'])?$_POST['id']:"";
$id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$sql = "SELECT de.id,de.nombre FROM deptos de, deptosxregion dxr WHERE dxr.idregion=$id AND dxr.iddepto=de.id";
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
