<?php
ob_start();
include_once "../includes/session.php";
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';

switch($_REQUEST["mode"]){
 case 'save':
	$id = getVal($_POST['id'],0);
	$ot=isset ($_POST['ot'])?$_POST['ot']:"";//
  $ot=mysqli_real_escape_string($dbsgp,$ot);//KIUWAN
	$start = getStrVal($_POST['start'],"null");
	$finish = getStrVal($_POST['finish'],"null");
	$antecesor = getVal($_POST['antecesor'],"null");//
  $antecesor=mysqli_real_escape_string($dbsgp,$antecesor);//KIUWAN

	$sql = "UPDATE pretareas SET duracion=DATEDIFF($finish,$start) + 1, antecesor=$antecesor WHERE id=$id";
	if (db_query($sql,true) > 0){
		$sql = "SELECT t.id,tt.nombre,t.duracion,t.antecesor FROM pretareas t,tipotarea tt, precronograma c WHERE t.idtipo=tt.id AND t.idcrono=c.id AND c.idpresupuesto=$ot AND t.active='Si'";
		$q = db_query($sql,true);
		$maxlen = 0;
		while ($row = mysqli_fetch_array($q)) {
			$start = getPreTaskStart($row['antecesor']);
			$finish = $start+$row['duracion']-1;
			if($maxlen < $finish) $maxlen=$finish;
		}
		db_query("UPDATE presupuesto SET fecha_requerida=DATE_ADD(fecha_solicitud,INTERVAL $maxlen DAY) WHERE id=$ot",true);
		echo "OK";
	}
	else echo "No fue posible actualizar la tarea.";
	break;
 case 'query':
	$id=isset ($_POST['id'])?$_POST['id']:"";//
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$ot=isset ($_POST['ot'])?$_POST['ot']:"";//
  $ot=mysqli_real_escape_string($dbsgp,$ot);//KIUWAN
	$antecesor = getNameById("pretareas",$id,"antecesor");
	$predecesor = getPreTaskTree($id);
	$sql = "SELECT t.id,tt.nombre FROM pretareas t,tipotarea tt,precronograma c WHERE t.idtipo=tt.id AND t.idcrono=c.id AND c.idpresupuesto=$ot";
	if(strlen($predecesor) > 0){
		$sql .= " AND t.id NOT IN($predecesor 0)";
	}
	$sql .= " AND t.active='Si' AND t.id != $id";
	//echo $sql;
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['nombre']."^";
			if($antecesor == $row['id']){
				$result.="selected";
			}
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
	break;
 case 'list':
	$ot=isset ($_POST['ot'])?$_POST['ot']:"";//
  $ot=mysqli_real_escape_string($dbsgp,$ot);//KIUWAN
	$sql = "SELECT t.id,tt.nombre,t.active FROM pretareas t,tipotarea tt,precronograma c WHERE t.idtipo=tt.id AND t.idcrono=c.id AND c.idpresupuesto=$ot";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['id']."^";
			$result.=$row['nombre']."^";
			$result.=$row['active'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
	break;
 case 'cfg':
	foreach($_POST as $key=>$value){
		if(strpos($key,"task_") === 0){
			$data = explode("_",$key);
			$idtask = $data[1];
			$sql = "UPDATE pretareas SET active ='$value' WHERE id=$idtask";
			db_query($sql,true);
		}
	}
	echo "OK";
} // end switch
?>
