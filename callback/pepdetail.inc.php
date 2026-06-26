<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
switch($_REQUEST["mode"]){
 case 'query':
	include_once "../includes/global.php";
	include_once "../includes/database.php";
	$id=isset ($_POST['id'])?$_POST['id']:"";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$sql = "SELECT mo,cable,otros FROM peps WHERE id=$id";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		if ($row = mysqli_fetch_array($query)) {
			$result.="|";
		$result.=$row['mo']."^";
			$result.=$row['cable']."^";
			$result.=$row['otros'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
} // end switch
?>
