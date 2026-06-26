<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
switch($_REQUEST["mode"]){
 case 'query':
	include_once "../includes/global.php";
	include_once "../includes/database.php";
	$id=isset($_POST['id'])?$_POST['id']:"";
	$prueba=isset($_POST['prueba'])?$_POST['prueba']:"";
       $idorden=isset($_POST['idorden'])?$_POST['idorden']:"";
       $idorden=htmlspecialchars($idorden);//KIUWAN
    	$solicitud=isset($_POST['solicitud'])?$_POST['solicitud']:"";
      $solicitud=htmlspecialchars($solicitud);//KIUWAN
	if ($prueba==0)
	{
		$idcontrato=getSQLValue("SELECT idcontrato FROM sgp.presupuesto WHERE id=$idorden");
	} else {
		$idcontrato=getSQLValue("SELECT idcontrato FROM sgp.ordenes WHERE id=$idorden");
	}
	$sql = " SELECT b.unidad, b.puntos, case when(
		('$solicitud' BETWEEN i.start_date AND i.end_date) AND b.material>0) 
	then ((b.material*i.value)+ b.material)
	else b.material end as 'material',
    b.metodo,b.factor1,b.factor2,b.factor3,b.item,b.descripcion 
	FROM baremo b 
	left join ipc i on i.idcontrato=$idcontrato and  '$solicitud' 
	BETWEEN i.start_date AND i.end_date AND i.active = 'Si' WHERE b.id =$id";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		if ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['unidad']."^";
			$result.=$row['puntos']."^";
			$result.=$row['material']."^";
			$result.=$row['metodo']."^";
			$result.=$row['factor1']."^";
			$result.=$row['factor2']."^";
			$result.=$row['factor3']."^";
			$result.=$row['item']."^";
			$result.=$row['descripcion'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
} // end switch
?>
