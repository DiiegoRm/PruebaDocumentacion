<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';

switch($_REQUEST["mode"]){
 case 'aprobar':

	$id = getVal($_POST['id'],"null");//
    $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$user = getAppUser();
	$sql = "UPDATE `solicitudesh` SET idestadosol=$SOL_ST_APROBADA,notas='Solicitud Aprobada',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0){
		$sql="INSERT INTO actividadesxorden (`idorden`,`version`,`idbaremo`,`puntos`,`material`,`cantidad`)
				(SELECT s.idorden,$OT_VER_EJECUCION,s.idbaremo,b.puntos,b.material,ROUND(IFNULL((s.valor/(pb.valor*(CASE WHEN i.id is not null then i.value+1 else 1 end)))/b.puntos,0),12)
				FROM solicitudesh s
				INNER JOIN ordenes o ON s.idorden=o.id
				INNER JOIN baremo b ON	s.idbaremo=b.id
				INNER JOIN preciosbaremo pb ON pb.idclase=b.idclase AND pb.ideecc=o.ideecc
				left join ipc i on i.idcontrato=o.idcontrato and o.fecha_solicitud BETWEEN i.start_date AND i.end_date
				WHERE  s.id=$id)
				ON DUPLICATE KEY UPDATE cantidad=cantidad+VALUES(cantidad)";
		if (db_query($sql,true) > 0){
			$idorden = getSQLValue("SELECT idorden FROM solicitudesh WHERE id=$id");
			calcularOrden($idorden,$OT_VER_EJECUCION);
		}
		db_query("DELETE FROM bandejash WHERE idsolicitud=$id",true);
		echo "OK";
	}
	else echo "No fue posible Aprobar la Solicitud.";
	break;
 case 'cancelar':
	$id = getVal($_POST['id'],"null");//
  	$id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$user = getAppUser();
	$sql = "UPDATE `solicitudesh` SET idestadosol=$SOL_ST_CANCELADA,notas='Solicitud Cancelada',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0) {
		$idorden = getSQLValue("SELECT idorden FROM solicitudesh WHERE id=$id");
		$cantidad = getSQLValue("SELECT COUNT(*) AS cantidad FROM solicitudesh WHERE idorden=$idorden AND idestadosol!=$SOL_ST_CANCELADA");
		
		/* Desarollo para eliminar el registro de la tabla actividadesxorden*/
		db_query("DELETE FROM actividadesxorden WHERE idbaremo = (SELECT idbaremo FROM solicitudesh WHERE id = $id)", true);		
		calcularOrden($idorden,$OT_VER_EJECUCION);
		/**/
		
		$idbaremo = getSQLValue("SELECT s.idbaremo
		FROM solicitudesh s, estadosol e, baremo b
				WHERE s.idestadosol=e.id AND s.idbaremo=b.id
		AND s.idorden=$idorden AND s.active='Si'");
		if($cantidad == 1) {
			$sql = "SELECT s.idorden,$OT_VER_EJECUCION,s.idbaremo,b.puntos,b.material,ROUND(IFNULL((s.valor/(pb.valor*(CASE WHEN i.id is not null then i.value+1 else 1 end)))/b.puntos,0),12) as cantidad 
					FROM solicitudesh s
					INNER JOIN ordenes o ON s.idorden=o.id
					INNER JOIN baremo b ON	s.idbaremo=b.id
					INNER JOIN preciosbaremo pb ON pb.idclase=b.idclase AND pb.ideecc=o.ideecc
					left join ipc i on i.idcontrato=o.idcontrato and o.fecha_solicitud BETWEEN i.start_date AND i.end_date
					WHERE  s.id=$id";
			
			$result = db_query($sql);
			$data = mysqli_fetch_array($result);

			$sql="UPDATE actividadesxorden set 
						puntos = puntos-$data[puntos]/*, 
						material = material-$data[material],*/  
						cantidad = cantidad-$data[cantidad]
				  WHERE idorden = $data[idorden] 
				  	AND idbaremo = $data[idbaremo]";
		} else {
			$sql = "SELECT s.idorden,$OT_VER_EJECUCION,s.idbaremo,b.puntos,b.material,ROUND(IFNULL((s.valor/(pb.valor*(CASE WHEN i.id is not null then i.value+1 else 1 end)))/b.puntos,0),12) as cantidad 
					FROM solicitudesh s
					INNER JOIN ordenes o ON s.idorden=o.id
					INNER JOIN baremo b ON	s.idbaremo=b.id
					INNER JOIN preciosbaremo pb ON pb.idclase=b.idclase AND pb.ideecc=o.ideecc
					left join ipc i on i.idcontrato=o.idcontrato and o.fecha_solicitud BETWEEN i.start_date AND i.end_date
					WHERE  s.id=$id";
			
			$result = db_query($sql);
			$data = mysqli_fetch_array($result);

			$sql="UPDATE actividadesxorden set 
						puntos = puntos-$data[puntos]/*, 
						material = material-$data[material], 
						cantidad = cantidad-$data[cantidad]*/ 
				  WHERE idorden = $data[idorden] 
				  	AND idbaremo = $data[idbaremo]";
		}
		if(db_query($sql,true)) {
			echo "OK";
		}
	}
	else echo "No fue posible Aprobar la Solicitud.";
 break;
 case 'aprobarmas':
	$user = getAppUser();
	foreach($_POST as $key=>$value){
		if(strpos($key,"sol_") === 0){
			$sql = "UPDATE `solicitudesh` SET idestadosol=$SOL_ST_APROBADA,notas='Solicitud Aprobada',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$value";
			if (db_query($sql,true) > 0){
				$sql="INSERT INTO actividadesxorden (`idorden`,`version`,`idbaremo`,`puntos`,`material`,`cantidad`)
						(SELECT s.idorden,$OT_VER_EJECUCION,s.idbaremo,b.puntos,b.material,ROUND(IFNULL((s.valor/pb.valor)/b.puntos,0),12)
						FROM solicitudesh s, ordenes o, baremo b,preciosbaremo pb WHERE s.idorden=o.id
						AND pb.idclase=$ID_CLASE_H AND pb.ideecc=o.ideecc AND s.idbaremo=b.id AND s.id=$value)
						ON DUPLICATE KEY UPDATE cantidad=cantidad+VALUES(cantidad)";
				if (db_query($sql,true) > 0){
					$idorden = getSQLValue("SELECT idorden FROM solicitudesh WHERE id=$value");
					calcularOrden($idorden,$OT_VER_EJECUCION);
				}
				db_query("DELETE FROM bandejash WHERE idsolicitud=$value",true);
			}
		}
	}
	echo "OK";
	break;
 case 'rechazar':
	$id = getVal($_POST['id'],"null");//
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$obs = getPostStr('txtObs');
	$user = getAppUser();

	$sql = "UPDATE `solicitudesh` SET idestadosol=$SOL_ST_RECHAZADA,notas=$obs,modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejash WHERE idsolicitud=$id",true);
		echo "OK";
	}
	else echo "No fue posible Rechazar la Solicitud.";
	break;
 case 'rechazarmas':
	$obs = getPostStr('txtObs');
	$user = getAppUser();
	foreach($_POST as $key=>$value){
		if(strpos($key,"sol_") === 0){
			$sql = "UPDATE `solicitudesh` SET idestadosol=$SOL_ST_RECHAZADA,notas=$obs,modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$value";
			if (db_query($sql,true) > 0){
				db_query("DELETE FROM bandejash WHERE idsolicitud=$value",true);
			}
		}
	}
	echo "OK";
	break;
} // end switch
?>
