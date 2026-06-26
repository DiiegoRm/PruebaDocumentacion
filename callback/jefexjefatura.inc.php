<?php
ob_start();
include_once "../includes/session.php";
include_once "../includes/global.php";
include_once "../includes/database.php";

switch($_REQUEST["mode"]){
 case 'query':
	$id=isset ($_POST['id'])?$_POST['id']:"";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$sql = "SELECT je.id,je.nombre FROM jefes je, jefaturas jt WHERE jt.id=$id AND jt.idjefe=je.id";
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
