<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';
switch($_REQUEST["mode"]){
 case 'lastliq':
	$id=isset ($_POST['id'])?$_POST['id']:"";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$sql = "SELECT IFNULL(SUM(totalba),0) lastba,IFNULL(SUM(totalmo),0) lastmo,IFNULL(SUM(totalma),0) lastma,IFNULL(SUM(valor),0) lastva,IFNULL(SUM(grabable),0) lastga,IFNULL(SUM(facturado),0) lastfa,IFNULL(SUM(iva),0) lastiva
  FROM liquidaciones WHERE idorden=$id AND idestadoliq NOT IN ($LIQ_ST_RECHAZADA,$LIQ_ST_CANCELADA)";
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		while ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['lastba']."^";
			$result.=$row['lastmo']."^";
			$result.=$row['lastma']."^";
			$result.=$row['lastva']."^";
			$result.=$row['lastga']."^";
			$result.=$row['lastfa']."^";
			$result.=$row['lastiva'];
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
	break;
 case 'barxliq':
	$id=isset ($_POST['id'])?$_POST['id']:"";
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$sql = "SELECT IFNULL(tpb,0) tpb,IFNULL(tmo,0) tmo,IFNULL(tma,0) tma,IFNULL(cdirecto,0) cdirecto,IFNULL(utilidad,0) utilidad,IFNULL(claseh,0) claseh,IFNULL(costoaiu,0) costoaiu,IFNULL(iva,0) iva FROM totalesxorden WHERE idorden=$id AND version=$OT_VER_EJECUCION";
	$idtipoot = getSQLValue("SELECT idtipoot FROM ordenes WHERE id=$id");
	$query =  db_query($sql,true);
	if (mysqli_num_rows($query) > 0){
		$result = "OK";
		if ($row = mysqli_fetch_array($query)) {
			$result.="|";
			$result.=$row['tpb']."^";
			$result.=$row['tmo']."^";
			$result.=$row['tma']."^";
			switch($idtipoot){
				case $OT_TIPO_CONSTRUCCION:
					$tva = $row['cdirecto'];
					$tga = $row['utilidad']+$row['claseh'];
					$tfa = $row['costoaiu'];
					$tiva = $row['iva'];
					break;
				case $OT_TIPO_INVENTARIORED:
					$tva = $row['cdirecto'];
					$tga = $row['utilidad']+$row['claseh'];
					$tfa = $row['costoaiu'];
					$tiva = $row['iva'];
					break;
				default:
					$tva = 0;
					$tga = $row['costoaiu'];
					$tfa = $row['costoaiu'];
					$tiva = $row['iva'];
					break;
			}
			$result.="$tva^";
			$result.="$tga^";
			$result.="$tfa^";
			$result.="$tiva";
		}
		echo htmlspecialchars($result);
	}
	else echo "NO";
	break;
case 'save':
	$idorden=isset ($_POST['idorden'])?$_POST['idorden']:"0";
  $idorden=mysqli_real_escape_string($dbsgp,$idorden);//KIUWAN
	$tipo=isset ($_POST['tipo'])?$_POST['tipo']:"PARCIAL";
	$totalba=isset ($_POST['totalba'])?str_replace(",","",$_POST['totalba']):"0";
	$totalmo=isset ($_POST['totalmo'])?str_replace(",","",$_POST['totalmo']):"0";
	$totalma=isset ($_POST['totalma'])?str_replace(",","",$_POST['totalma']):"0";
	$totalva=isset ($_POST['totalva'])?str_replace(",","",$_POST['totalva']):"0";
	$totalga=isset ($_POST['totalga'])?str_replace(",","",$_POST['totalga']):"0";
	$totalfa=isset ($_POST['totalfa'])?str_replace(",","",$_POST['totalfa']):"0";
	$totaliva=isset ($_POST['totaliva'])?str_replace(",","",$_POST['totaliva']):"0";
  $totaliva=mysqli_real_escape_string($dbsgp,$totaliva);//KIUWAN

	$version=getSQLValue("SELECT IFNULL(MAX(version),$OT_VER_EJECUCION) FROM liquidaciones WHERE idorden=$idorden");
	$user = getAppUser();

	//-->
	$seq=getSQLValue("SELECT IFNULL(COUNT(*),0)+1 FROM liquidaciones WHERE idorden=$idorden");
	if($tipo == "PARCIAL"){
		$numero = "LQP-".padZeroLeft($seq,8);
	} else {
		$numero = "LQT-".padZeroLeft($seq,8);
	}
	//<--
	$sql = "INSERT INTO liquidaciones(numero,idorden,version,idestadoliq,tipo,fecha_liquidacion,totalba,totalmo,totalma,valor,grabable,
    facturado,iva,create_user,modify_user,notas) VALUES('$numero',$idorden,$version+1,$LIQ_ST_ENCONCILIACION,'$tipo',CURRENT_DATE(),
    $totalba,$totalmo,$totalma,$totalva,$totalga,$totalfa,$totaliva,$user->uid,$user->uid,'Liquidacion Creada')";
	if(db_query($sql,true) > 0){
		$lastliq = getLastId();
		//--> update materiales
		if($version > 3){
            		db_query("INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad)
							SELECT $idorden,$OT_VER_EJECUCION,a.idbaremo,a.puntos,0 as 'material',0 as 'cantidad'  FROM
                                                (SELECT a.idorden,a.version,a.idbaremo,a.puntos,a.material,a.cantidad,a.suplemento,a.mtsducto,a.pares,a.empalmes,a.depende
                                                    FROM sgp.actividadesxorden a
                                                    INNER JOIN sgp.liquidaciones l
                                                                on l.idorden=a.idorden and l.version = a.version
                                                                WHERE l.idestadoliq not in ($LIQ_ST_RECHAZADA,$LIQ_ST_CANCELADA) and a.idorden=$idorden and a.version>$OT_VER_EJECUCION)a
                                                    LEFT JOIN (SELECT a.idorden,a.version,a.idbaremo,a.puntos,a.material,a.cantidad,a.suplemento,a.mtsducto,a.pares,a.empalmes,a.depende
                                                                    FROM sgp.actividadesxorden a
                                                                    WHERE a.idorden=$idorden and a.version=$OT_VER_EJECUCION)b
                                                    on (b.idorden=a.idorden and b.idbaremo= a.idbaremo)
                            WHERE b.idorden is null",true);
		        }
		db_query("INSERT INTO materialesxorden(idorden,version,idbaremo,idmaterial,factor,unidad,valor,cantidad,movistar)
							SELECT $idorden,$version+1,m1.idbaremo,m1.idmaterial,m1.factor,m1.unidad,m1.valor,m1.cantidad-IFNULL(m2.cantidad,0) cantidad,m1.movistar-IFNULL(m2.movistar,0) movistar FROM (
								SELECT idorden,version,idbaremo,idmaterial,factor,unidad,valor,SUM(cantidad) cantidad,SUM(movistar) movistar FROM materialesxorden WHERE idorden=$idorden AND version=$OT_VER_EJECUCION GROUP BY idorden,version,idbaremo,idmaterial,factor,unidad,valor
								) m1 LEFT JOIN  (
								SELECT idbaremo,idmaterial,SUM(cantidad) cantidad,SUM(movistar) movistar FROM materialesxorden WHERE idorden=$idorden AND version>$OT_VER_EJECUCION AND version NOT IN(SELECT version FROM liquidaciones WHERE idorden=$idorden AND idestadoliq IN($LIQ_ST_RECHAZADA,$LIQ_ST_CANCELADA)) GROUP BY idbaremo,idmaterial
								) m2
								ON (m1.idbaremo=m2.idbaremo AND m1.idmaterial=m2.idmaterial), material ma WHERE ma.id=m1.idmaterial AND ABS(m1.cantidad-IFNULL(m2.cantidad,0)) >0",true);
		//--> update baremos
		db_query("INSERT INTO actividadesxorden(idorden,version,idbaremo,puntos,material,cantidad)
							SELECT $idorden,$version+1,b.id,a1.puntos,a1.material,a1.cantidad-IFNULL(a2.cantidad,0) cantidad FROM (
								SELECT idorden,version,idbaremo,puntos,material,SUM(cantidad) cantidad FROM actividadesxorden WHERE idorden=$idorden AND version=$OT_VER_EJECUCION GROUP BY idorden,version,idbaremo,puntos,material
								) a1 LEFT JOIN  (
								SELECT idbaremo,puntos,material,SUM(cantidad) cantidad FROM actividadesxorden WHERE idorden=$idorden AND version>$OT_VER_EJECUCION AND version NOT IN(SELECT version FROM liquidaciones WHERE idorden=$idorden AND idestadoliq IN ($LIQ_ST_RECHAZADA,$LIQ_ST_CANCELADA)) GROUP BY idbaremo,puntos,material
								) a2
								ON (a1.idbaremo=a2.idbaremo), baremo b WHERE a1.idbaremo=b.id AND ABS(a1.cantidad-IFNULL(a2.cantidad,0)) > 0",true);

		//--> Asign Trays
		db_query("INSERT INTO bandejasliq(idliquidacion, idgrupo) VALUES($lastliq,$GRP_EECC)",true);
		db_query("INSERT INTO bandejasliq(idliquidacion, idgrupo) SELECT $lastliq,g.id FROM ordenes o, usuarios u, grupos g WHERE o.resp_movistar=u.id AND u.idgrupo=g.id AND o.id=$idorden LIMIT 1",true);

		echo "OK";
	}	else echo "NO";
	break;
} // end switch
?>
