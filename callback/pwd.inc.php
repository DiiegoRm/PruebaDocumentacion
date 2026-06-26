<?php
ob_start();
include_once "../includes/session.php";
include_once "../includes/global.php";
include_once "../includes/database.php";
sessionCheck();
switch($_REQUEST["mode"]){
 case 'policies':
	$sql = "SELECT nombre,valor,REPLACE(regla,'#VALUE#',valor) rule,REPLACE(mensaje,'#VALUE#',valor) msg FROM seguridad WHERE active='Si' AND tipo='VALIDACION'";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['nombre']."^";
			$result.=$row['valor']."^";
			$result.=$row['rule']."^";
			$result.=$row['msg'];
		}
		echo $result;
	}
	else echo "NO";
	break;
 case 'history':
	$id=isset ($_POST['id'])?$_POST['id']:"0";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$pwd=isset ($_POST['pwd'])?$_POST['pwd']:"0";
  $pwd=mysqli_real_escape_string($dbsgp,$pwd);//KIUWAN

	$q = db_query("SELECT REPLACE(REPLACE(REPLACE(regla,'#VALUE#',valor),'#1#','$id'),'#2#','$pwd') rule,REPLACE(mensaje,'#VALUE#',valor) msg FROM seguridad WHERE active='Si' AND tipo='HISTORICO'");
  $row = mysqli_fetch_array($q);
  if (count($row)>0) {
		$sql = $row[0];
		$msg = $row[1];
	}
	if(!empty($sql)){
		$query =  db_query($sql,true);
		if (mysqli_num_rows($query) > 0){
      $row = mysqli_fetch_array($query);
			if (count($row)>0) {
				if($row[0] != "0"){
					$result=$msg;
				} else {
					$result="OK";
				}
			}
			echo htmlspecialchars($result);
		} else echo "NO";
	} else echo "OK";
} // end switch
?>
