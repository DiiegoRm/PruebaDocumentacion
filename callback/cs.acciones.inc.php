<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';

switch($_REQUEST["mode"]){
 case 'aceptar':
	$id = getVal($_POST['id'],"null");
	$user = getAppUser();
	
	$sql = "UPDATE `liquidaciones` SET idestadoliq=$LIQ_ST_GESTIONRESERVAS,notas='Liquidacion Aceptada',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasliq WHERE idliquidacion=$id",true);
		db_query("INSERT INTO bandejasliq(idliquidacion, idgrupo) VALUES($id,$GRP_OP_CENTRAL)",true);
		echo "OK";
	}
	else echo "No fue posible Aceptar la liquidacion.";
	break;
 case 'aceptarmas':
	$user = getAppUser();
	foreach($_POST as $key=>$value){
		if(strpos($key,"cs_") === 0){
			$sql = "UPDATE `liquidaciones` SET idestadoliq=$LIQ_ST_GESTIONRESERVAS,notas='Liquidacion Aceptada',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$value";
			if (db_query($sql,true) > 0){
				db_query("DELETE FROM bandejasliq WHERE idliquidacion=$value",true);
				db_query("INSERT INTO bandejasliq(idliquidacion, idgrupo) VALUES($value,$GRP_OP_CENTRAL)",true);
			}
		}
	}
	echo "OK";
	break;
 case 'rechazar':
	$id = getVal($_POST['id'],"null");
	$obs = getStrVal($_POST['obs'],"null");
	$user = getAppUser();
	
	$sql = "UPDATE `liquidaciones` SET idestadoliq=$LIQ_ST_RECHAZADA,notas=$obs,modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasliq WHERE idliquidacion=$id",true);
		db_query("INSERT INTO bandejasliq(idliquidacion, idgrupo) VALUES($id,$GRP_EECC)",true);
		echo "OK";
	}
	else echo "No fue posible Rechazar la liquidacion.";
	break;
 case 'rechazarmas':
	$obs = getStrVal($_POST['obs'],"null");
	$user = getAppUser();
	foreach($_POST as $key=>$value){
		if(strpos($key,"cs_") === 0){
			$sql = "UPDATE `liquidaciones` SET idestadoliq=$LIQ_ST_RECHAZADA,notas=$obs,modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$value";
			if (db_query($sql,true) > 0){
				db_query("DELETE FROM bandejasliq WHERE idliquidacion=$value",true);
				db_query("INSERT INTO bandejasliq(idliquidacion, idgrupo) VALUES($value,$GRP_EECC)",true);
			}
		}
	}
	echo "OK";
	break;
 case 'aprobar':
	$id = getVal($_POST['id'],"null");
	$user = getAppUser();
	
	$sql = "UPDATE `liquidaciones` SET idestadoliq=$LIQ_ST_APROBADA,notas='Liquidacion Aprobada',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasliq WHERE idliquidacion=$id",true);
		db_query("INSERT INTO bandejasliq(idliquidacion, idgrupo) VALUES($id,$GRP_SOPORTE_TECNICO)",true);
		echo "OK";
	}
	else echo "No fue posible Aprobar la liquidacion.";
	break;
 case 'aprobarmas':
	$user = getAppUser();
	foreach($_POST as $key=>$value){
		if(strpos($key,"cs_") === 0){
			$sql = "UPDATE `liquidaciones` SET idestadoliq=$LIQ_ST_APROBADA,notas='Liquidacion Aprobada',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$value";
			if (db_query($sql,true) > 0){
				db_query("DELETE FROM bandejasliq WHERE idliquidacion=$value",true);
				db_query("INSERT INTO bandejasliq(idliquidacion, idgrupo) VALUES($value,$GRP_SOPORTE_TECNICO)",true);
			}
		}
	}
	echo "OK";
	break;
 case 'asignar':
	$id = getVal($_POST['id'],"null");
	$date = getVal($_POST['date'],"null");
	if($date != "null")$date = "'".$date."'";
	$user = getAppUser();
	
	$sql = "UPDATE `liquidaciones` SET fecha_causacion=$date,modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0){
		echo "OK";
	}
	else echo "No fue posible Asignar/Quitar Fecha Causacion para la liquidacion.";
	break;
 case 'asignarmas':
	$date = getVal($_POST['date'],"null");
	if($date != "null")$date = "'".$date."'";
	$user = getAppUser();

	foreach($_POST as $key=>$value){
		if(strpos($key,"cs_") === 0){
			$sql = "UPDATE `liquidaciones` SET fecha_causacion=$date,modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$value";
			db_query($sql,true);
		}
	}
	echo "OK";
	break;
 case 'causar':
	$id = getVal($_POST['id'],"null");
	$pedido = getStrVal($_POST['ped'],"null");
	$migo = getStrVal($_POST['migo'],"null");
	
	$user = getAppUser();
	
	$sql = "UPDATE `liquidaciones` SET idestadoliq=$LIQ_ST_CAUSADA,pedido=$pedido,migo=$migo,notas='Liquidacion Causada',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasliq WHERE idliquidacion=$id",true);
		db_query("INSERT INTO bandejasliq(idliquidacion, idgrupo) VALUES($id,$GRP_EECC)",true);
		echo "OK";
	}
	else echo "No fue posible Causar la liquidacion.";
	break;
 case 'causarmas':
	$pedido = getStrVal($_POST['ped'],"null");
	$migo = getStrVal($_POST['migo'],"null");
	$user = getAppUser();

	foreach($_POST as $key=>$value){
		if(strpos($key,"cs_") === 0){
			$sql = "UPDATE `liquidaciones` SET idestadoliq=$LIQ_ST_CAUSADA,pedido=$pedido,migo=$migo,notas='Liquidacion Causada',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$value";
			if (db_query($sql,true) > 0){
				db_query("DELETE FROM bandejasliq WHERE idliquidacion=$value",true);
				db_query("INSERT INTO bandejasliq(idliquidacion, idgrupo) VALUES($value,$GRP_EECC)",true);
			}
		}
	}
	echo "OK";
	break;
 case 'facturar':
	$id = getVal($_POST['id'],"null");
	$factura = getStrVal($_POST['fact'],"null");
	$user = getAppUser();
	
	$sql = "UPDATE `liquidaciones` SET idestadoliq=$LIQ_ST_FACTURADA,factura=$factura,notas='Liquidacion Facturada',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0){
		$liqTotal = getSQLValue("SELECT IFNULL(count(*),0) FROM liquidaciones WHERE tipo='TOTAL' AND id=$id");
		if($liqTotal > 0){
			$idorden = getSQLValue("SELECT idorden FROM liquidaciones WHERE id=$id");
			$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_CERRADA,notas='Orden Liquidada',modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$idorden";
			if (db_query($sql,true) > 0){
				//Eliminar de todas las bandejas
				db_query("DELETE FROM bandejasot WHERE idorden=$idorden",true);
			}
		}
		db_query("DELETE FROM bandejasliq WHERE idliquidacion=$id",true);
		echo "OK";
	}
	else echo "No fue posible Subir Factura a la liquidacion.";
	break;
 case 'facturarmas':
	$factura = getStrVal($_POST['fact'],"null");
	$user = getAppUser();

	foreach($_POST as $key=>$value){
		if(strpos($key,"cs_") === 0){
			$sql = "UPDATE `liquidaciones` SET idestadoliq=$LIQ_ST_FACTURADA,factura=$factura,notas='Liquidacion Facturada',modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$value";
			if (db_query($sql,true) > 0){
				$liqTotal = getSQLValue("SELECT IFNULL(count(*),0) FROM liquidaciones WHERE tipo='TOTAL' AND id=$value");
				if($liqTotal > 0){
					$idorden = getSQLValue("SELECT idorden FROM liquidaciones WHERE id=$value");
					$sql = "UPDATE `ordenes` SET idestadoot=$OT_ST_CERRADA,notas='Orden Liquidada',modify_user=$user->uid,modify_date=CURRENT_TIMESTAMP WHERE id=$idorden";
					if (db_query($sql,true) > 0){
						//Eliminar de todas las bandejas
						db_query("DELETE FROM bandejasot WHERE idorden=$idorden",true);
					}
				}
				db_query("DELETE FROM bandejasliq WHERE idliquidacion=$value",true);
			}
		}
	}
	echo "OK";
	break;
 case 'cancelar':
	$id = getVal($_POST['id'],"null");
	$obs = getStrVal($_POST['obs'],"null");
	$user = getAppUser();
	
	$sql = "UPDATE `liquidaciones` SET idestadoliq=$LIQ_ST_CANCELADA,Active='No',notas=$obs,modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$id";
	if (db_query($sql,true) > 0){
		db_query("DELETE FROM bandejasliq WHERE idliquidacion=$id");
		echo "OK";
	}
	else echo "No fue posible Rechazar la liquidacion.";
	break;
 case 'cancelarmas':
	$obs = getStrVal($_POST['obs'],"null");
	$user = getAppUser();
	
	foreach($_POST as $key=>$value){
		if(strpos($key,"cs_") === 0){
			$sql = "UPDATE `liquidaciones` SET idestadoliq=$LIQ_ST_CANCELADA,Active='No',notas=$obs,modify_user=$user->uid,`modify_date`=CURRENT_TIMESTAMP WHERE `id`=$value";
			if (db_query($sql,true) > 0){
				db_query("DELETE FROM bandejasliq WHERE idliquidacion=$value");
			}
		}
	}
	echo "OK";
	break;

	case 'exportar':
		$month = getVal($_POST['txtFrmLiqMonth'],"null");
		$year = getStrVal($_POST['txtFrmLiqYear'],"null");
		$estado = getVal($_POST['txtEstado'], null);

			$sql = sprintf("SELECT *, (`SubTotal Baremos`*`Valor Clase`)+(`SubTotal Materiales (CoP$)`*IFNULL(`coeficiente`,0)) AS ValorTotal
				FROM
				(SELECT o.numero AS 'Orden', es.nombre as 'Estado2', l.idestadoliq AS 'Estado',
				a.version AS 'Consecutivo', b.item AS 'Item', b.descripcion AS 'Descripcion', 
				b.unidad AS 'Unidad', a.puntos AS 'Puntos Baremo', a.material AS 'Materiales (CoP$)',
				a.cantidad AS 'Cantidad', TRUNCATE(a.puntos*a.cantidad,8) AS 'SubTotal Baremos', 
				TRUNCATE(a.material*a.cantidad,8) AS 'SubTotal Materiales (CoP$)', 
				c.numero AS 'Contrato', cmo.nombre as 'Clase', cmo.id as 'id_clase_obra', 
				CASE WHEN  i.id is not null AND o.fecha_solicitud BETWEEN i.start_date AND i.end_date AND cmo.unidad='PB' 
				THEN ((pb.valor*i.value)+ pb.valor) ELSE  pb.valor  END AS 'Valor Clase',
				pe.mo AS 'M.O.PEP',d.nombre AS 'Depto',ex.nombre AS 'EECC', l.fecha_causacion AS 'Fecha Causacion',
				case cmo.id 
				when 20 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=21 and pb2.ideecc=pb.ideecc) 
				when 22 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=23 and pb2.ideecc=pb.ideecc) 
				when 24 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=167 and pb2.ideecc=pb.ideecc)
				when 25 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=166 and pb2.ideecc=pb.ideecc) 
				when 28 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=29 and pb2.ideecc=pb.ideecc) 
				when 62 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=63 and pb2.ideecc=pb.ideecc) 
				when 64 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=65 and pb2.ideecc=pb.ideecc)
				when 66 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=67 and pb2.ideecc=pb.ideecc) 
				when 68 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=69 and pb2.ideecc=pb.ideecc) 
				when 90 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=91 and pb2.ideecc=pb.ideecc) 
				when 97 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=98 and pb2.ideecc=pb.ideecc)
				when 99 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=100 and pb2.ideecc=pb.ideecc) 
				when 101 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=102 and pb2.ideecc=pb.ideecc)
				when 174 then (select pb2.valor from  sgp.preciosbaremo pb2  where pb2.idclase=175 and pb2.ideecc=pb.ideecc)
				end as  'coeficiente',
				l.idorden
				FROM sgp.actividadesxorden a 
				INNER JOIN sgp.baremo b ON a.idbaremo = b.id
				INNER JOIN sgp.clasemanoobra cmo ON  cmo.id=b.idclase
				INNER JOIN preciosbaremo pb ON pb.idclase=cmo.id
				INNER JOIN sgp.eecc ex ON  pb.ideecc=ex.id
				INNER JOIN sgp.contratos c ON c.ideecc = ex.id
				INNER JOIN sgp.ordenes o ON  o.idcontrato = c.id AND a.idorden = o.id
				INNER JOIN sgp.peps pe ON o.idpep = pe.id
				INNER JOIN sgp.liquidaciones l ON  l.idorden = o.id 
				INNER JOIN sgp.deptos d ON o.iddepto = d.id
				INNER JOIN sgp.estadoliq es ON l.idestadoliq=es.id 
				LEFT JOIN ipc i ON o.fecha_solicitud BETWEEN i.start_date AND i.end_date and i.idcontrato=o.idcontrato
				WHERE 
				l.version=a.version
				AND ((a.version>2))
				and MONTH(l.fecha_causacion)=%s
				and YEAR(l.fecha_causacion)=%s
				and es.id = %s
				ORDER BY a.idorden, a.idbaremo)A", 
				$month,
				$year,
				$estado);

				date_default_timezone_set('America/Bogota');
				header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
				header ('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header ("Pragma: no-cache");
				header ('Pragma: public');
				header ("Content-Disposition: attachment; filename=ActividadesOT.xls");
				header ('Content-Type: application/ms-excel; charset=UTF-8');
				header ('Content-Transfer-Encoding: binary');
				
				echo "\xEF\xBB\xBF";
				$query = db_query($sql);
				if (!file_exists("php://output")) {
					$out = fopen("php://output", 'w');
				}

				$data=array();
				while ($row = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
					$data[] = $row;
				}

				foreach ($data as $keydata) {
					if($i++==0){
						fputcsv($out, array_keys($keydata),";",'"');
					}
					fputcsv($out, array_values($keydata),";",'"');
				}
				fclose($out);

			break;	
			case 'tipo':
				foreach($_POST as $key=>$value){
					$tipo = getSQLValue("SELECT tipo FROM liquidaciones WHERE id=$value");
					if ($tipo == 'TOTAL') {
						db_query("UPDATE `liquidaciones` SET tipo='PARCIAL', `modify_date`=CURRENT_TIMESTAMP WHERE `id`= $value", true);
					} else {
						db_query("UPDATE `liquidaciones` SET tipo='TOTAL', `modify_date`=CURRENT_TIMESTAMP WHERE `id`= $value", true);
					}
				}
				echo 'OK';
			break;
} // end switch
?>
