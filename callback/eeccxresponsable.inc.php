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
	$sql = "SELECT a.id, CONCAT(a.nombre,' - ',a.relacion) nombre, a.active
		FROM subcontratista a
		LEFT JOIN eeccxresponsable c ON a.id=c.idresponsable
		LEFT JOIN eecc b ON b.id=c.ideecc
		LEFT JOIN contratos d ON b.id=d.ideecc
		WHERE d.id=$id";
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
