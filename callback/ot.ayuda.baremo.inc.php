<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
switch($_REQUEST["mode"]){
 case 'query':
	include_once "../includes/database.php";
	include_once "../includes/global.php";

	$id = getVal($_POST['txtId'],"null");
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$sql = "SELECT notas FROM ayuda WHERE idbaremo=$id";
	echo $Sql;
	$query =  db_query($sql,true);
	$result = "La actividad no cuenta con ayuda!";
	if (mysqli_num_rows($query) > 0){
		if ($row = mysqli_fetch_array($query)) {
			$result = $row['notas'];
		}
	}
	echo htmlspecialchars($result);
} // end switch
?>
