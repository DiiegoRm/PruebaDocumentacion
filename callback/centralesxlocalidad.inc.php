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
  $sql = "SELECT id,CONCAT(codigo,' - ',nombre) nombre FROM distribuidores WHERE idlocalidad=$id ORDER BY nombre";
	$query = db_query($sql,true);
	if (mysqlI_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqlI_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['nombre'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";//$result = rawurlencode($result);
} // end switch
?>
