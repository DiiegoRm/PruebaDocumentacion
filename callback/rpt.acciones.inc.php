<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once '../includes/class.excel.xml.php';
require_once '../includes/database.php';
set_time_limit(0);
$time_start = microtime(true);

$filePath = RPT_FILE_PATH . DIRECTORY_SEPARATOR ;
switch($_REQUEST["mode"]){
	case 'viabilidades':
		$fileName = "Viabilidades_" . date("Y-m-d-H-i-s") . ".xml";

		session_start();
		$_SESSION['BK_ACTION'] = "Generando Reporte de Viabilidades...";
		$_SESSION['BK_PROGRESS'] = "0";
		session_write_close();

		$excel = new ExcelXML($fileName,$filePath);
		$excel->writeHeader('Viabilidades Detallados');
		//Viabilidades
		$i=1;$j=1;
		$excel->openSheet('Viabilidades');
		$excel->openRow($i++);
		$excel->writeString($j++,'Numero','H');
		$excel->writeString($j++,'Solicitada','H');
		$excel->writeString($j++,'Requerida','H');
		$excel->writeString($j++,'Fecha Entrega','H');
		$excel->writeString($j++,'Fecha Presupuesto','H');
		$excel->writeString($j++,'Alerta','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'EECC','H');
		$excel->writeString($j++,'Region','H');
		$excel->writeString($j++,'Departamento','H');
		$excel->writeString($j++,'Localidad','H');
		$excel->writeString($j++,'Requerimiento','H');
		$excel->writeString($j++,'Nombre','H');
		$excel->writeString($j++,'Segmento','H');
		$excel->writeString($j++,'Proyecto','H');
		$excel->writeString($j++,'Jefatura','H');
		$excel->writeString($j++,'Jefe','H');
		$excel->writeString($j++,'Direccion','H');
		$excel->writeString($j++,'Constructora','H');
		$excel->writeString($j++,'Contacto','H');
		$excel->writeString($j++,'Telefono','H');
		$excel->writeString($j++,'LV','H');
		$excel->writeString($j++,'BA','H');
		$excel->writeString($j++,'TV','H');
		$excel->writeString($j++,'Estrato','H');
		$excel->writeString($j++,'Total Viviendas','H');
		$excel->writeString($j++,'Etapas','H');
		$excel->writeString($j++,'Viviendas x Etapa','H');
		$excel->writeString($j++,'Notas Segmento','H');
		$excel->writeString($j++,'Notas Ingenieria','H');
		$excel->writeString($j++,'Presupuesto','H');
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Solicitante','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT v.id,v.numero,v.fecha_solicitud,v.entrega,v.fecha_requerida,v.nombre,v.fecha_respuesta,v.fecha_presupuesto,v.active,
		ev.nombre estado,u.nombre solicitante, s.nombre segmento,tv.nombre requerimiento,pv.nombre proyecto,d.nombre depto,l.nombre localidad,
		 r.nombre region, IF(v.idestadovb=$VB_ST_ESTUDIO,IF(v.fecha_requerida BETWEEN DATE_SUB(current_timestamp,INTERVAL 1 DAY)
		 AND current_timestamp,'amarillo',IF(current_timestamp > v.fecha_requerida,'rojo','verde')),'') alerta, e.nombre eecc,js.nombre jefatura,
		 je.nombre jefe,p.numero presupuesto,o.numero orden,v.direccion,v.constructora,v.contacto,v.telefono,v.lb,v.ba,v.tv,v.estrato,
		 v.total_viviendas,v.etapa,v.viviendas_etapa,v.notas_seg,v.notas_ing,v.fecha_respuesta
		 FROM viabilidades v LEFT JOIN ordenes o ON (v.idorden=o.id) LEFT JOIN presupuesto p ON (v.idpresupuesto=p.id)
		 LEFT JOIN eecc e ON (v.ideecc=e.id) LEFT JOIN jefaturas js ON (v.idjefatura=js.id) LEFT JOIN jefes je ON(v.idjefe=je.id)
		 LEFT JOIN proyectovb pv ON(v.idproyectovb=pv.id) LEFT JOIN localidades l ON(v.idlocalidad=l.id) LEFT JOIN deptos d ON(v.iddepto=d.id)
		 LEFT JOIN regiones r ON(v.idregion=r.id), estadovb ev,usuarios u, segmentos s,tipovb tv
		 WHERE v.idestadovb=ev.id AND v.create_user=u.id AND v.idtipovb=tv.id AND v.idsegmento=s.id ORDER BY v.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['fecha_solicitud']);
			$excel->writeString($j++,$row['fecha_requerida']);
			$excel->writeNumber($j++,$row['entrega']);
			$excel->writeString($j++,$row['fecha_presupuesto']);
			$excel->writeString($j++,$row['alerta']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeString($j++,$row['eecc']);
			$excel->writeString($j++,$row['region']);
			$excel->writeString($j++,$row['depto']);
			$excel->writeString($j++,$row['localidad']);
			$excel->writeString($j++,$row['requerimiento']);
			$excel->writeString($j++,$row['nombre']);
			$excel->writeString($j++,$row['segmento']);
			$excel->writeString($j++,$row['proyecto']);
			$excel->writeString($j++,$row['jefatura']);
			$excel->writeString($j++,$row['jefe']);
			$excel->writeString($j++,$row['direccion']);
			$excel->writeString($j++,$row['constructora']);
			$excel->writeString($j++,$row['contacto']);
			$excel->writeString($j++,$row['telefono']);
			$excel->writeNumber($j++,$row['lb']);
			$excel->writeNumber($j++,$row['ba']);
			$excel->writeNumber($j++,$row['tv']);
			$excel->writeString($j++,$row['estrato']);
			$excel->writeNumber($j++,$row['total_viviendas']);
			$excel->writeString($j++,$row['etapa']);
			$excel->writeString($j++,$row['viviendas_etapa']);
			$excel->writeString($j++,$row['notas_seg']);
			$excel->writeString($j++,$row['notas_ing']);
			$excel->writeString($j++,$row['presupuesto']);
			$excel->writeString($j++,$row['orden']);
			$excel->writeString($j++,$row['solicitante']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = ($i / $rows) * 35.00;
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Respuestas
		$i=1;$j=1;
		$excel->openSheet('Respuesta');
		$excel->openRow($i++);
		$excel->writeString($j++,'Viabilidad','H');
		$excel->writeString($j++,'Presupuestpo','H');
		$excel->writeString($j++,'Fecha Solicitud','H');
		$excel->writeString($j++,'Cod. Distribuidor','H');
		$excel->writeString($j++,'Nombre Distribuidor','H');
		$excel->writeString($j++,'POP','H');
		$excel->writeString($j++,'Armario','H');
		$excel->writeString($j++,'Cable','H');
		$excel->writeNumber($j++,'Pares Primarios','H');
		$excel->writeNumber($j++,'Pares Secundarios','H');
		$excel->writeString($j++,'Vel. Max BA','H');
		$excel->writeString($j++,'Dist DSLAM-Arm.(m)','H');
		$excel->writeString($j++,'Dist DSLAM-Caja(m)','H');
		$excel->writeNumber($j++,'Mano de Obra','H');
		$excel->writeNumber($j++,'Materiales','H');
		$excel->writeString($j++,'Observaciones','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT vv.numero,o.numero ppto,o.fecha_solicitud,di.codigo coddist,di.nombre distribuidor,p.nombre pop,armario,cable,parprim,
		parsec,v.nombre velmaxba,distarm,dd.nombre distcaja,tp.tmo,tp.tma,vv.notas_ing
		FROM viabilidades vv LEFT JOIN presupuesto o ON (vv.idpresupuesto=o.id)
		LEFT JOIN totalesxpresupuesto tp ON (tp.idpresupuesto=o.id)
		LEFT JOIN distribuidores di ON (o.iddistribuidor=di.id)
		LEFT JOIN pops p ON (o.idpop=p.id)
		LEFT JOIN distancia dd ON (o.iddistcaja=dd.id)
		LEFT JOIN velocidad v ON (o.idvelmaxba=v.id)
		WHERE vv.idpresupuesto IS NOT NULL";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['ppto']);
			$excel->writeString($j++,$row['fecha_solicitud']);
			$excel->writeString($j++,$row['coddist']);
			$excel->writeNumber($j++,$row['distribuidor']);
			$excel->writeNumber($j++,$row['pop']);
			$excel->writeNumber($j++,$row['armario']);
			$excel->writeNumber($j++,$row['cable']);
			$excel->writeNumber($j++,$row['parprim']);
			$excel->writeNumber($j++,$row['parsec']);
			$excel->writeNumber($j++,$row['velmaxba']);
			$excel->writeNumber($j++,$row['distarm']);
			$excel->writeNumber($j++,$row['distcaja']);
			$excel->writeNumber($j++,$row['tmo']);
			$excel->writeNumber($j++,$row['tma']);
			$excel->writeNumber($j++,$row['notas_ing']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 35.00 + (($i / $rows) * 35.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Seguimiento
		$i=1;$j=1;
		$excel->openSheet('Seguimiento');
		$excel->openRow($i++);
		$excel->writeString($j++,'Viabilidad','H');
		$excel->writeString($j++,'Fecha','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'Usuario','H');
		$excel->writeString($j++,'Notas','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT v.numero,s.create_date,e.nombre estado,u.nombre usuario,s.notas FROM seguimientovb s, viabilidades v, estadovb e, usuarios u WHERE s.idviabilidad=v.id AND s.idestadovb=e.id AND s.idusuario=u.id ORDER BY s.idviabilidad,s.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['create_date']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeNumber($j++,$row['usuario']);
			$excel->writeNumber($j++,$row['notas']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 70.00 + (($i / $rows) * 30.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();
		$excel->writeFooter();
		session_start();
		$_SESSION['BK_ACTION'] = "$fileName, "." Memoria Usada: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB, Duracion: ". number_format(microtime(true) - $time_start,2) . " seg";
		$_SESSION['BK_PROGRESS'] = "100";
		session_write_close();
		echo $_SESSION['BK_ACTION'];
	break;
	case 'presupuestos':
		$fileName = "Presupuestos_" . date("Y-m-d-H-i-s") . ".xml";

		session_start();
		$_SESSION['BK_ACTION'] = "Generando Reporte de Presupuestos...";
		$_SESSION['BK_PROGRESS'] = "0";
		session_write_close();

		$excel = new ExcelXML($fileName,$filePath);
		$excel->writeHeader('Presupuestos Detallados');
		//Presupuestos
		$i=1;$j=1;
		$excel->openSheet('Presupuestos');
		$excel->openRow($i++);
		$excel->writeString($j++,'Numero','H');
		$excel->writeString($j++,'Solicitada','H');
		$excel->writeString($j++,'Requerida','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'EECC','H');
		$excel->writeString($j++,'Zona','H');
		$excel->writeString($j++,'Departamento','H');
		$excel->writeString($j++,'Localidad','H');
		$excel->writeString($j++,'Tipo','H');
		$excel->writeString($j++,'Nombre','H');
		$excel->writeString($j++,'Tipo Red','H');
		$excel->writeString($j++,'Proyecto','H');
		$excel->writeString($j++,'Segmento','H');
		$excel->writeString($j++,'Contrato','H');
		$excel->writeString($j++,'DS','H');
		$excel->writeString($j++,'EPRO','H');
		$excel->writeString($j++,'TRS','H');
		$excel->writeString($j++,'Tipo Proyecto','H');
		$excel->writeString($j++,'Notas','H');
		$excel->writeString($j++,'Distribuidor','H');
		$excel->writeString($j++,'Armario','H');
		$excel->writeString($j++,'Cable','H');
		$excel->writeString($j++,'POP','H');
		$excel->writeString($j++,'Pares Primarios','H');
		$excel->writeString($j++,'Pares Secundarios','H');
		$excel->writeString($j++,'Par/Km','H');
		$excel->writeString($j++,'Km Fibra','H');
		$excel->writeString($j++,'Mts Ducto','H');
		$excel->writeString($j++,'Vel. Max BA','H');
		$excel->writeString($j++,'DSLAM-Caja(m)','H');
		$excel->writeString($j++,'DSLAM-Arm.(m)','H');
		$excel->writeString($j++,'Viviendas','H');
		$excel->writeString($j++,'Torres','H');
		$excel->writeString($j++,'Bocas','H');
		$excel->writeString($j++,'Sol. Verticales','H');
		$excel->writeString($j++,'Creador','H');
		$excel->writeString($j++,'Total MO','H');
		$excel->writeString($j++,'Total MA','H');
		$excel->writeString($j++,'Total OT','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.id,o.numero,o.fecha_solicitud,o.fecha_requerida,s.nombre segmento,o.nombre,o.active,o.estado,tot.nombre req,ee.nombre eecc,
		z.nombre zona, d.nombre depto,l.nombre localidad,tr.nombre red,cp.nombre proyecto,ct.numero contrato,ds,epro,trs,tpy.nombre tipoproyecto,
		notas,dd.nombre distribuidor,armario,cable,parprim,parsec,pp.nombre pop,parkm,kmfibra,mtsducto,vl.nombre velmax,distarm,di.nombre distcaja,
		viviendas,torres,bocas,verticales,u.nombre creador, tp.tmo,tp.tma
		FROM presupuesto o LEFT JOIN eecc ee ON o.ideecc=ee.id LEFT JOIN zonas z ON o.idzona=z.id
		LEFT JOIN deptos d ON o.iddepto=d.id LEFT JOIN localidades l ON o.idlocalidad=l.id
		LEFT JOIN tipored tr ON o.idtipored=tr.id LEFT JOIN claseproyecto cp ON o.idclaseproyecto=cp.id
		LEFT JOIN totalesxpresupuesto tp ON tp.idpresupuesto=o.id LEFT JOIN contratos ct ON o.idcontrato=ct.id
		LEFT JOIN tipoproyecto tpy ON o.idtipoproyecto=tpy.id LEFT JOIN distribuidores dd ON o.iddistribuidor=dd.id
		LEFT JOIN pops pp ON o.idpop=pp.id LEFT JOIN velocidad vl ON o.idvelmaxba=vl.id
		LEFT JOIN distancia di ON o.iddistcaja=di.id,tipoot tot,segmentos s,usuarios u
		WHERE o.idtipoot=tot.id AND o.idsegmento=s.id AND o.create_user=u.id ORDER BY id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['fecha_solicitud']);
			$excel->writeString($j++,$row['fecha_requerida']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeString($j++,$row['eecc']);
			$excel->writeString($j++,$row['zona']);
			$excel->writeString($j++,$row['depto']);
			$excel->writeString($j++,$row['localidad']);
			$excel->writeString($j++,$row['req']);
			$excel->writeString($j++,$row['nombre']);
			$excel->writeString($j++,$row['red']);
			$excel->writeString($j++,$row['proyecto']);
			$excel->writeString($j++,$row['segmento']);
			$excel->writeString($j++,$row['contrato']);
			$excel->writeString($j++,$row['ds']);
			$excel->writeString($j++,$row['epro']);
			$excel->writeString($j++,$row['trs']);
			$excel->writeString($j++,$row['tipoproyecto']);
			$excel->writeString($j++,$row['notas']);
			$excel->writeString($j++,$row['distribuidor']);
			$excel->writeString($j++,$row['armario']);
			$excel->writeString($j++,$row['cable']);
			$excel->writeString($j++,$row['pop']);
			$excel->writeNumber($j++,$row['parprim']);
			$excel->writeNumber($j++,$row['parsec']);
			$excel->writeNumber($j++,$row['parkm']);
			$excel->writeNumber($j++,$row['kmfibra']);
			$excel->writeNumber($j++,$row['mtsducto']);
			$excel->writeString($j++,$row['velmax']);
			$excel->writeString($j++,$row['distcaja']);
			$excel->writeString($j++,$row['distarm']);
			$excel->writeNumber($j++,$row['viviendas']);
			$excel->writeNumber($j++,$row['torres']);
			$excel->writeNumber($j++,$row['bocas']);
			$excel->writeNumber($j++,$row['verticales']);
			$excel->writeString($j++,$row['creador']);
			$excel->writeNumber($j++,$row['tmo']);
			$excel->writeNumber($j++,$row['tma']);
			$excel->writeNumber($j++,$row['tmo'] + $row['tma']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = ($i / $rows) * 20.00;
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Total Baremos
		$i=1;$j=1;
		$excel->openSheet('Total Baremos');
		$excel->openRow($i++);
		$excel->writeString($j++,'Presupuesto','H');
		$excel->writeString($j++,'Clase Mano de Obra','H');
		$excel->writeString($j++,'Unidad','H');
		$excel->writeString($j++,'Valor/Unitario (CoP$)','H');
		$excel->writeString($j++,'Costo Directo (CoP$)','H');
		$excel->writeString($j++,'Puntos/Baremo','H');
		$excel->writeString($j++,'Valor Total (CoP$)','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT p.numero,cm.id,pp.unidad,cm.nombre,pp.valor,pp.costo,pp.puntos,pp.puntos*pp.valor total FROM preciosxpresupuesto pp, clasemanoobra cm,presupuesto p WHERE pp.idclase=cm.id AND pp.idpresupuesto=p.id ORDER BY pp.idpresupuesto,pp.idclase";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['nombre']);
			$excel->writeString($j++,$row['unidad']);
			$excel->writeNumber($j++,$row['valor']);
			$excel->writeNumber($j++,$row['costo']);
			$excel->writeNumber($j++,$row['puntos']);
			$excel->writeNumber($j++,$row['total']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 20.00 + (($i / $rows) * 20.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Actividades
		$i=1;$j=1;
		$excel->openSheet('Actividades');
		$excel->openRow($i++);
		$excel->writeString($j++,'Presupuesto','H');
		$excel->writeString($j++,'Item','H');
		$excel->writeString($j++,'Descripcion','H');
		$excel->writeString($j++,'Unidad','H');
		$excel->writeString($j++,'Puntos Baremo','H');
		$excel->writeString($j++,'Materiales (CoP$)','H');
		$excel->writeString($j++,'Cantidad','H');
		$excel->writeString($j++,'SubTotal Baremos','H');
		$excel->writeString($j++,'SubTotal Materiales (CoP$)','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT p.numero,b.item,b.descripcion,b.unidad,a.puntos,a.material,a.cantidad,TRUNCATE(a.puntos*a.cantidad,8) sb,
		TRUNCATE(a.material*a.cantidad,8) sm
		FROM actividadesxpresupuesto a, baremo b,presupuesto p
		WHERE a.idpresupuesto=p.id AND a.idbaremo=b.id ORDER By a.idpresupuesto, a.idbaremo";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['item']);
			$excel->writeString($j++,$row['descripcion']);
			$excel->writeString($j++,$row['unidad']);
			$excel->writeNumber($j++,$row['puntos']);
			$excel->writeNumber($j++,$row['material']);
			$excel->writeNumber($j++,$row['cantidad']);
			$excel->writeNumber($j++,$row['sb']);
			$excel->writeNumber($j++,$row['sm']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 40.00 + (($i / $rows) * 20.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Materiales
		$i=1;$j=1;
		$excel->openSheet('Materiales');
		$excel->openRow($i++);
		$excel->writeString($j++,'Presupuesto','H');
		$excel->writeString($j++,'Codigo','H');
		$excel->writeString($j++,'Descripcion','H');
		$excel->writeString($j++,'Tipo','H');
		$excel->writeString($j++,'Unidad','H');
		$excel->writeString($j++,'Costo (CoP$)','H');
		$excel->writeString($j++,'Cantidad','H');
		$excel->writeString($j++,'SubTotal(CoP$)','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT p.numero,ma.codigo,ma.item,ma.tipo,mo.unidad,mo.valor,mo.movistar,mo.valor*mo.movistar sm
		FROM materialesxpresupuesto mo, material ma,presupuesto p
		WHERE mo.movistar>0 AND mo.idmaterial>0 AND mo.idmaterial=ma.id AND mo.idpresupuesto=p.id
		ORDER BY mo.idpresupuesto,mo.idmaterial";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['codigo']);
			$excel->writeString($j++,$row['item']);
			$excel->writeString($j++,$row['tipo']);
			$excel->writeString($j++,$row['unidad']);
			$excel->writeNumber($j++,$row['valor']);
			$excel->writeNumber($j++,$row['movistar']);
			$excel->writeNumber($j++,$row['sm']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 60.00 + (($i / $rows) * 20.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Cronograma
		$i=1;$j=1;
		$excel->openSheet('Cronograma');
		$excel->openRow($i++);
		$excel->writeString($j++,'Presupuesto','H');
		$excel->writeString($j++,'Inicio Cronograma','H');
		$excel->writeString($j++,'Nombre','H');
		$excel->writeString($j++,'Antesesor','H');
		$excel->writeString($j++,'Duracion(dias)','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT p.numero,p.fecha_solicitud,tt.nombre,t1.nombre antecesor,pt.duracion
		FROM precronograma pc, pretareas pt
		LEFT JOIN pretareas p1 ON pt.antecesor=p1.id
		LEFT JOIN tipotarea t1 ON p1.idtipo=t1.id,presupuesto p, tipotarea tt
		WHERE pt.active='Si' AND pc.idpresupuesto=p.id AND pt.idcrono=pc.id AND pt.idtipo=tt.id ORDER BY p.id,pt.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['fecha_solicitud']);
			$excel->writeString($j++,$row['nombre']);
			$excel->writeString($j++,$row['antecesor']);
			$excel->writeNumber($j++,$row['duracion']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 80.00 + (($i / $rows) * 20.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();
		$excel->writeFooter();
		session_start();
		$_SESSION['BK_ACTION'] = "$fileName, "." Memoria Usada: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB, Duracion: ". number_format(microtime(true) - $time_start,2) . " seg";
		$_SESSION['BK_PROGRESS'] = "100";
		session_write_close();
		echo $_SESSION['BK_ACTION'];
	break;
	case 'ordenes':
		$fileName = "Ordenes_" . date("Y-m-d-H-i-s") . ".xml";

		session_start();
		$_SESSION['BK_ACTION'] = "Generando Reporte de Ordenes...";
		$_SESSION['BK_PROGRESS'] = "0";
		session_write_close();

		$excel = new ExcelXML($fileName,$filePath);
		$excel->writeHeader('Ordenes Detallados');

		//Ordenes
		$i=1;$j=1;
		$excel->openSheet('Ordenes');
		$excel->openRow($i++);
		$excel->writeString($j++,'Numero','H');
		$excel->writeString($j++,'Solicitada','H');
		$excel->writeString($j++,'Requerida','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'EECC','H');
		$excel->writeString($j++,'Zona','H');
		$excel->writeString($j++,'Departamento','H');
		$excel->writeString($j++,'Localidad','H');
		$excel->writeString($j++,'Tipo','H');
		$excel->writeString($j++,'Nombre','H');
		$excel->writeString($j++,'Tipo Red','H');
		$excel->writeString($j++,'Proyecto','H');
		$excel->writeString($j++,'Segmento','H');
		$excel->writeString($j++,'Contrato','H');
		$excel->writeString($j++,'Direccion','H');
		$excel->writeString($j++,'Viabiliad','H');
		$excel->writeString($j++,'DS','H');
		$excel->writeString($j++,'EPRO','H');
		$excel->writeString($j++,'TRS','H');
		$excel->writeString($j++,'Tipo Proyecto','H');
		$excel->writeString($j++,'Notas','H');
		$excel->writeString($j++,'Cod. Distribuidor','H');
		$excel->writeString($j++,'Nombre Distribuidor','H');
		$excel->writeString($j++,'Armario','H');
		$excel->writeString($j++,'Cable','H');
		$excel->writeString($j++,'POP','H');
		$excel->writeString($j++,'Pares Primarios','H');
		$excel->writeString($j++,'Pares Secundarios','H');
		$excel->writeString($j++,'Par/Km','H');
		$excel->writeString($j++,'Km Fibra','H');
		$excel->writeString($j++,'Mts Ducto','H');
		$excel->writeString($j++,'Vel. Max BA','H');
		$excel->writeString($j++,'DSLAM-Caja(m)','H');
		$excel->writeString($j++,'DSLAM-Arm.(m)','H');
		$excel->writeString($j++,'Viviendas','H');
		$excel->writeString($j++,'Torres','H');
		$excel->writeString($j++,'Bocas','H');
		$excel->writeString($j++,'Sol. Verticales','H');
		$excel->writeString($j++,'Creador','H');
		$excel->writeString($j++,'Alerta','H');
		$excel->writeString($j++,'Registro','H');
		$excel->writeString($j++,'Nombre PEP','H');
		$excel->writeString($j++,'PEP M.O.','H');
		$excel->writeString($j++,'PEP Cable','H');
		$excel->writeString($j++,'PEP Otros','H');
		$excel->writeString($j++,'Total MO','H');
		$excel->writeString($j++,'Total MA','H');
		$excel->writeString($j++,'Total OT','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.id,o.numero,s.nombre AS segmento,o.fecha_solicitud,o.fecha_requerida,o.nombre,o.active,eo.nombre estado,tot.nombre req,
		ee.nombre eecc,z.nombre zona, d.nombre depto,l.nombre localidad,tr.nombre red,cp.nombre proyecto,cp.nombre proyecto,ct.numero contrato,
		o.direccion,o.idviabilidad,ds,epro,trs,tpy.nombre tipoproyecto,notas,dd.codigo coddist,dd.nombre distribuidor,armario,o.cable,parprim,
		parsec,pp.nombre pop,parkm,kmfibra,mtsducto,vl.nombre velmax,distarm,di.nombre distcaja,viviendas,torres,bocas,verticales,u.nombre creador,
		tp.tmo,tp.tma,IF(o.idestadoot NOT IN($OT_ST_CANCELADA,$OT_ST_TERMINADA,$OT_ST_CERRADA),IF(CURRENT_DATE > o.fecha_requerida,'rojo',
			IF(DATEDIFF(o.fecha_requerida,CURRENT_DATE) <= 2,'amarillo','verde')),'') alerta,o.registro,pep.nombre pepnombre,pep.mo pepmo,
			pep.cable pepcable,pep.otros pepotros
			FROM ordenes o LEFT JOIN eecc ee ON o.ideecc=ee.id
			LEFT JOIN zonas z ON o.idzona=z.id LEFT JOIN deptos d ON o.iddepto=d.id
			LEFT JOIN localidades l ON o.idlocalidad=l.id
			LEFT JOIN tipored tr ON o.idtipored=tr.id
			LEFT JOIN claseproyecto cp ON o.idclaseproyecto=cp.id
			LEFT JOIN totalesxorden tp ON (tp.idorden=o.id AND tp.version=$OT_VER_GENERADA)
			LEFT JOIN contratos ct ON o.idcontrato=ct.id
			LEFT JOIN tipoproyecto tpy ON o.idtipoproyecto=tpy.id
			LEFT JOIN distribuidores dd ON o.iddistribuidor=dd.id
			LEFT JOIN pops pp ON o.idpop=pp.id
			LEFT JOIN velocidad vl ON o.idvelmaxba=vl.id
			LEFT JOIN distancia di ON o.iddistcaja=di.id
			LEFT JOIN peps pep ON (o.idpep=pep.id),tipoot tot,estadoot eo,segmentos s,usuarios u
			WHERE o.idtipoot=tot.id AND o.idestadoot=eo.id AND o.idsegmento=s.id AND o.create_user=u.id ORDER BY id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['fecha_solicitud']);
			$excel->writeString($j++,$row['fecha_requerida']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeString($j++,$row['eecc']);
			$excel->writeString($j++,$row['zona']);
			$excel->writeString($j++,$row['depto']);
			$excel->writeString($j++,$row['localidad']);
			$excel->writeString($j++,$row['req']);
			$excel->writeString($j++,$row['nombre']);
			$excel->writeString($j++,$row['red']);
			$excel->writeString($j++,$row['proyecto']);
			$excel->writeString($j++,$row['segmento']);
			$excel->writeString($j++,$row['contrato']);
			$excel->writeString($j++,$row['direccion']);
			$excel->writeString($j++,$row['idviabilidad']);
			$excel->writeString($j++,$row['ds']);
			$excel->writeString($j++,$row['epro']);
			$excel->writeString($j++,$row['trs']);
			$excel->writeString($j++,$row['tipoproyecto']);
			$excel->writeString($j++,$row['notas']);
			$excel->writeString($j++,$row['coddist']);
			$excel->writeString($j++,$row['distribuidor']);
			$excel->writeString($j++,$row['armario']);
			$excel->writeString($j++,$row['cable']);
			$excel->writeString($j++,$row['pop']);
			$excel->writeNumber($j++,$row['parprim']);
			$excel->writeNumber($j++,$row['parsec']);
			$excel->writeNumber($j++,$row['parkm']);
			$excel->writeNumber($j++,$row['kmfibra']);
			$excel->writeNumber($j++,$row['mtsducto']);
			$excel->writeString($j++,$row['velmax']);
			$excel->writeString($j++,$row['distcaja']);
			$excel->writeString($j++,$row['distarm']);
			$excel->writeNumber($j++,$row['viviendas']);
			$excel->writeNumber($j++,$row['torres']);
			$excel->writeNumber($j++,$row['bocas']);
			$excel->writeNumber($j++,$row['verticales']);
			$excel->writeString($j++,$row['creador']);
			$excel->writeString($j++,$row['alerta']);
			$excel->writeString($j++,$row['registro']);
			$excel->writeString($j++,$row['pepnombre']);
			$excel->writeString($j++,$row['pepmo']);
			$excel->writeString($j++,$row['pepcable']);
			$excel->writeString($j++,$row['pepotros']);
			$excel->writeNumber($j++,$row['tmo']);
			$excel->writeNumber($j++,$row['tma']);
			$excel->writeNumber($j++,$row['tmo'] + $row['tma']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = ($i / $rows) * 10.00;
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Total Baremos
		$i=1;$j=1;
		$excel->openSheet('Total Baremos');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Clase Mano de Obra','H');
		$excel->writeString($j++,'Unidad','H');
		$excel->writeString($j++,'Valor/Unitario (CoP$)','H');
		$excel->writeString($j++,'Costo Directo (CoP$)','H');
		$excel->writeString($j++,'Puntos/Baremo Generados','H');
		$excel->writeString($j++,'Valor Total Generado (CoP$)','H');
		$excel->writeString($j++,'Puntos/Baremo Ejecutado','H');
		$excel->writeString($j++,'Valor Total Ejecutado (CoP$)','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.numero,cm.id,pp1.unidad,cm.nombre,pp1.valor,pp1.costo,pp1.puntos puntos1,pp2.puntos puntos2,pp1.puntos*pp1.valor total1,
		pp2.puntos*pp2.valor total2
		FROM preciosxorden pp1 LEFT JOIN preciosxorden pp2
		ON ( pp1.idorden=pp2.idorden AND pp1.idclase=pp2.idclase AND pp2.version=$OT_VER_EJECUCION), clasemanoobra cm,ordenes o
		WHERE pp1.idorden=o.id AND pp1.idclase=cm.id AND pp1.version=$OT_VER_GENERADA ORDER BY pp1.idorden,pp1.idclase";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['nombre']);
			$excel->writeString($j++,$row['unidad']);
			$excel->writeNumber($j++,$row['valor']);
			$excel->writeNumber($j++,$row['costo']);
			$excel->writeNumber($j++,$row['puntos1']);
			$excel->writeNumber($j++,$row['total1']);
			$excel->writeNumber($j++,$row['puntos2']);
			$excel->writeNumber($j++,$row['total2']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 10.00 + (($i / $rows) * 10.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Actividades
		$i=1;$j=1;
		$excel->openSheet('Actividades');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Item','H');
		$excel->writeString($j++,'Descripcion','H');
		$excel->writeString($j++,'Unidad','H');
		$excel->writeString($j++,'Puntos Baremo','H');
		$excel->writeString($j++,'Materiales (CoP$)','H');
		$excel->writeString($j++,'Cantidad Generada','H');
		$excel->writeString($j++,'Baremos Generados','H');
		$excel->writeString($j++,'Materiales Generados (CoP$)','H');
		$excel->writeString($j++,'Cantidad Ejecutado','H');
		$excel->writeString($j++,'Baremos Ejecutados','H');
		$excel->writeString($j++,'Materiales Ejecutado (CoP$)','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.numero,b.id,b.idclase,b.item,b.descripcion,b.unidad,b.puntos,b.material,IFNULL(a.cantidad1,0) cantidad1,IFNULL(a.cantidad2,0) cantidad2,TRUNCATE(b.puntos*a.cantidad1,12) sb,TRUNCATE(b.material*a.cantidad1,12) sm,TRUNCATE(b.puntos*a.cantidad2,12) sb2,TRUNCATE(b.material*a.cantidad2,12) sm2 FROM (
					SELECT a1.idorden,a1.idbaremo,a1.cantidad1,a2.cantidad2 FROM (
							SELECT idorden,idbaremo,SUM(cantidad) cantidad1 FROM actividadesxorden WHERE version=$OT_VER_GENERADA GROUP BY idorden,idbaremo
					 ) a1
					LEFT JOIN (
							SELECT idorden,idbaremo,SUM(cantidad) cantidad2 FROM actividadesxorden WHERE version=$OT_VER_EJECUCION GROUP BY idorden,idbaremo
					) a2
					ON (a1.idorden=a2.idorden AND a1.idbaremo=a2.idbaremo)
					UNION
					SELECT a2.idorden,a2.idbaremo,a1.cantidad1,a2.cantidad2 FROM (
							SELECT idorden,idbaremo,SUM(cantidad) cantidad2 FROM actividadesxorden WHERE version=$OT_VER_EJECUCION GROUP BY idorden,idbaremo
					 ) a2
					LEFT JOIN (
							SELECT idorden,idbaremo,SUM(cantidad) cantidad1 FROM actividadesxorden WHERE version=$OT_VER_GENERADA GROUP BY idorden,idbaremo
					) a1
					ON (a2.idorden=a1.idorden AND a2.idbaremo=a1.idbaremo)
					WHERE a1.idbaremo IS NULL
			)a, baremo b, ordenes o
			WHERE a.idbaremo=b.id AND a.idorden=o.id ORDER BY o.id,b.item";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['item']);
			$excel->writeString($j++,$row['descripcion']);
			$excel->writeString($j++,$row['unidad']);
			$excel->writeNumber($j++,$row['puntos']);
			$excel->writeNumber($j++,$row['material']);
			$excel->writeNumber($j++,$row['cantidad1']);
			$excel->writeNumber($j++,$row['sb']);
			$excel->writeNumber($j++,$row['sm']);
			$excel->writeNumber($j++,$row['cantidad2']);
			$excel->writeNumber($j++,$row['sb2']);
			$excel->writeNumber($j++,$row['sm2']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 20.00 + (($i / $rows) * 10.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Materiales
		$i=1;$j=1;
		$excel->openSheet('Materiales');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Codigo','H');
		$excel->writeString($j++,'Descripcion','H');
		$excel->writeString($j++,'Tipo','H');
		$excel->writeString($j++,'Unidad','H');
		$excel->writeString($j++,'Costo (CoP$)','H');
		$excel->writeString($j++,'Cantidad Generado','H');
		$excel->writeString($j++,'SubTotal Generado (CoP$)','H');
		$excel->writeString($j++,'Cantidad Ejecutado','H');
		$excel->writeString($j++,'SubTotal Ejecutado (CoP$)','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.numero,ma.codigo,ma.item,ma.tipo,ma.unidad,ma.valor,mo.idmaterial,IFNULL(mo.cantidad1,0) cantidad1,TRUNCATE(IFNULL(mo.cantidad1,0)*ma.valor,8) total1,IFNULL(mo.cantidad2,0) cantidad2,TRUNCATE(IFNULL(mo.cantidad2,0)*ma.valor,8) total2 FROM (
				SELECT m1.idorden,m1.idmaterial,m1.cantidad1,m2.cantidad2 FROM (
						SELECT idorden,idmaterial,SUM(movistar) cantidad1 FROM materialesxorden WHERE movistar>0 AND idmaterial>0 AND version=$OT_VER_GENERADA GROUP BY idorden,idmaterial
				) m1
				LEFT JOIN (
						SELECT idorden,idmaterial,SUM(movistar) cantidad2 FROM materialesxorden WHERE movistar>0 AND idmaterial>0 AND version=$OT_VER_EJECUCION GROUP BY idorden,idmaterial
				) m2
				ON (m1.idorden=m2.idorden AND m1.idmaterial=m2.idmaterial)
				UNION
				SELECT m2.idorden,m2.idmaterial,m1.cantidad1,m2.cantidad2 FROM (
						SELECT idorden,idmaterial,SUM(movistar) cantidad2 FROM materialesxorden WHERE movistar>0 AND idmaterial>0 AND version=$OT_VER_EJECUCION GROUP BY idorden,idmaterial
				) m2
				LEFT JOIN (
						SELECT idorden,idmaterial,SUM(movistar) cantidad1 FROM materialesxorden WHERE movistar>0 AND idmaterial>0 AND version=$OT_VER_GENERADA GROUP BY idorden,idmaterial
				) m1
				ON (m2.idorden=m1.idorden AND m2.idmaterial=m1.idmaterial)
				WHERE m1.idmaterial IS NULL
		)mo, material ma, ordenes o
		WHERE mo.idmaterial=ma.id AND mo.idorden=o.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['codigo']);
			$excel->writeString($j++,$row['item']);
			$excel->writeString($j++,$row['tipo']);
			$excel->writeString($j++,$row['unidad']);
			$excel->writeNumber($j++,$row['valor']);
			$excel->writeNumber($j++,$row['cantidad1']);
			$excel->writeNumber($j++,$row['total1']);
			$excel->writeNumber($j++,$row['cantidad2']);
			$excel->writeNumber($j++,$row['total2']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 30.00 + (($i / $rows) * 10.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();
		//Reservas
		$i=1;$j=1;
		$excel->openSheet('Reservas');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Numero','H');
		$excel->writeString($j++,'Tipo','H');
		$excel->writeString($j++,'Fecha','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'Codigo','H');
		$excel->writeString($j++,'Descripcion','H');
		$excel->writeString($j++,'Cantidad','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.numero orden,s.numero,s.tipo,s.fecha,e.nombre estado,m.codigo,m.item,s.cantidad FROM `reservas` s, estadores e, material m,ordenes o WHERE s.idorden=o.id AND s.idestadores=e.id AND s.idmaterial=m.id ORDER BY s.idorden,s.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['orden']);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['tipo']);
			$excel->writeString($j++,$row['fecha']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeString($j++,$row['codigo']);
			$excel->writeString($j++,$row['item']);
			$excel->writeNumber($j++,$row['cantidad']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 40.00 + (($i / $rows) * 10.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Solicitudes
		$i=1;$j=1;
		$excel->openSheet('Solicitudes');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Fecha','H');
		$excel->writeString($j++,'Item','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'Valor','H');
		$excel->writeString($j++,'Justificacion','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.numero,s.create_date,s.justificacion,e.nombre estado,s.valor,b.descripcion FROM solicitudesh s, estadosol e, baremo b,ordenes o WHERE s.idorden=o.id AND s.idestadosol=e.id AND s.idbaremo=b.id ORDER BY s.idorden,s.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['create_date']);
			$excel->writeString($j++,$row['descripcion']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeNumber($j++,$row['valor']);
			$excel->writeString($j++,$row['justificacion']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 50.00 + (($i / $rows) * 10.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Cronograma
		$i=1;
		$excel->openSheet('Cronograma');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Inicio Cronograma','H');
		$excel->writeString($j++,'Nombre','H');
		$excel->writeString($j++,'Antesesor','H');
		$excel->writeString($j++,'Duracion(dias)','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.numero,o.fecha_solicitud,tt.nombre,t1.nombre antecesor,t.duracion
		FROM cronograma c, tareas t LEFT JOIN tareas p1 ON t.antecesor=p1.id
		LEFT JOIN tipotarea t1 ON p1.idtipo=t1.id,ordenes o, tipotarea tt
		WHERE t.active='Si' AND c.version=$OT_VER_GENERADA AND c.idorden=o.id AND t.idcrono=c.id AND t.idtipo=tt.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['fecha_solicitud']);
			$excel->writeString($j++,$row['nombre']);
			$excel->writeString($j++,$row['antecesor']);
			$excel->writeNumber($j++,$row['duracion']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 60.00 + (($i / $rows) * 10.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Causaciones
		$i=1;$j=1;
		$excel->openSheet('Causaciones');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'ID Causacion','H');
		$excel->writeString($j++,'Numero','H');
		$excel->writeString($j++,'Fecha Causacion','H');
		$excel->writeString($j++,'Fecha Liquidacion','H');
		$excel->writeString($j++,'Tipo Liquidacion','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'Total Baremos','H');
		$excel->writeString($j++,'Mano de Obra $','H');
		$excel->writeString($j++,'Materiales $','H');
		$excel->writeString($j++,'Total $','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT l.id,o.numero orden,l.fecha_causacion,l.fecha_liquidacion,l.tipo,l.numero,l.totalba,l.totalmo,l.totalma,
		l.totalba+l.totalmo+l.totalma total,e.nombre estado
		FROM liquidaciones l, estadoliq e,ordenes o
		WHERE l.idestadoliq=e.id AND l.idorden=o.id ORDER BY l.idorden,l.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['orden']);
			$excel->writeString($j++,$row['id']);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['fecha_causacion']);
			$excel->writeString($j++,$row['fecha_liquidacion']);
			$excel->writeString($j++,$row['tipo']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeNumber($j++,$row['totalba']);
			$excel->writeNumber($j++,$row['totalmo']);
			$excel->writeNumber($j++,$row['totalma']);
			$excel->writeNumber($j++,$row['total']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 70.00 + (($i / $rows) * 10.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Pedidos
		$i=1;$j=1;
		$excel->openSheet('Pedidos');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Numero','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'Fecha Pedido','H');
		$excel->writeString($j++,'Fecha Programada','H');
		$excel->writeString($j++,'Codigo','H');
		$excel->writeString($j++,'Material','H');
		$excel->writeString($j++,'Unidad','H');
		$excel->writeString($j++,'Cantidad','H');
		$excel->writeString($j++,'Fecha Entrega','H');
		$excel->writeString($j++,'Traslado','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.numero orden,p.numero,e.nombre estado,p.create_date,p.fecha_programada,m.codigo,m.item,m.unidad,p.traslado,
		cantidad,p.fecha_entrega
		FROM pedidosxorden p, estadoped e,material m,ordenes o
		WHERE p.active='Si' AND p.idestadoped=e.id AND p.idmaterial=m.id AND p.idorden=o.id ORDER BY p.idorden,p.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['orden']);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeString($j++,$row['create_date']);
			$excel->writeString($j++,$row['fecha_programada']);
			$excel->writeString($j++,$row['codigo']);
			$excel->writeString($j++,$row['item']);
			$excel->writeString($j++,$row['unidad']);
			$excel->writeNumber($j++,$row['cantidad']);
			$excel->writeString($j++,$row['fecha_entrega']);
			$excel->writeString($j++,$row['traslado']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 80.00 + (($i / $rows) * 10.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Seguimiento
		$i=1;$j=1;
		$excel->openSheet('Seguimiento');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Fecha','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'Avance','H');
		$excel->writeString($j++,'Usuario','H');
		$excel->writeString($j++,'Notas','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.numero,s.create_date,e.nombre estado,s.avance,u.nombre usuario,s.notas
		FROM seguimientoot s, ordenes o, estadoot e, usuarios u
		WHERE s.idorden=o.id AND s.idestadoot=e.id AND s.idusuario=u.id ORDER BY s.idorden,s.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['create_date']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeNumber($j++,$row['avance']);
			$excel->writeString($j++,$row['usuario']);
			$excel->writeString($j++,$row['notas']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 90.00 + (($i / $rows) * 10.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();
		$excel->writeFooter();
		session_start();
		$_SESSION['BK_ACTION'] = "$fileName, "." Memoria Usada: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB, Duracion: ". number_format(microtime(true) - $time_start,2) . " seg";
		$_SESSION['BK_PROGRESS'] = "100";
		session_write_close();
		echo $_SESSION['BK_ACTION'];
	break;
	case 'causaciones':
		$fileName = "Causaciones_" . date("Y-m-d-H-i-s") . ".xml";

		session_start();
		$_SESSION['BK_ACTION'] = "Generando Reporte de Causaciones...";
		$_SESSION['BK_PROGRESS'] = "0";
		session_write_close();

		$excel = new ExcelXML($fileName,$filePath);
		$excel->writeHeader('Causaciones Detallados');

		//Causaciones
		$i=1;$j=1;
		$excel->openSheet('Causaciones');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Consecutivo','H');
		$excel->writeString($j++,'Id Liq','H');
		$excel->writeString($j++,'Numero Liq','H');
		$excel->writeString($j++,'Fecha Liq','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'Contrato','H');
		$excel->writeString($j++,'EECC','H');
		$excel->writeString($j++,'Zona','H');
		$excel->writeString($j++,'Depto','H');
		$excel->writeString($j++,'Localidad','H');
		$excel->writeString($j++,'TipoRed','H');
		$excel->writeString($j++,'TipoOT','H');
		$excel->writeString($j++,'NombrePEP','H');
		$excel->writeString($j++,'M.O.PEP','H');
		$excel->writeString($j++,'Fecha Causacion','H');
		$excel->writeString($j++,'Valor Sin Utilidad','H');
		$excel->writeString($j++,'Base Grabable','H');
		$excel->writeString($j++,'Valor Facturado (sin iva)','H');
		$excel->writeString($j++,'Iva','H');
		$excel->writeString($j++,'Tipo','H');
		$excel->writeString($j++,'Pedido','H');
		$excel->writeString($j++,'Migo','H');
		$excel->writeString($j++,'Factura','H');
		$excel->writeString($j++,'Total Baremos','H');
		$excel->writeString($j++,'Total Mano de Obra','H');
		$excel->writeString($j++,'Total Materiales','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT l.id,o.numero orden,l.version,l.fecha_liquidacion,l.fecha_causacion,c.numero contrato,ex.nombre eecc,z.nombre zona,
		 d.nombre depto, lo.nombre localidad,tr.nombre tipored,tot.nombre tipoot,pe.nombre pep,pe.mo,e.nombre estado,l.tipo,l.numero,l.pedido,
		 l.migo,l.factura,l.valor,l.grabable,l.facturado,l.iva,l.active,l.totalba,l.totalmo,l.totalma
		 FROM liquidaciones l, estadoliq e, ordenes o,contratos c, eecc ex,zonas z,deptos d,localidades lo,tipored tr,tipoot tot,peps pe
		 WHERE l.idestadoliq=e.id AND l.idorden=o.id AND o.idcontrato=c.id AND c.ideecc=ex.id AND o.idzona=z.id AND o.iddepto=d.id
		 AND o.idlocalidad=lo.id AND o.idtipored=tr.id AND o.idtipoot=tot.id AND o.idpep=pe.id ORDER BY o.id,l.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['orden']);
			$excel->writeNumber($j++,$row['version']);
			$excel->writeNumber($j++,$row['id']);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['fecha_liquidacion']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeString($j++,$row['contrato']);
			$excel->writeString($j++,$row['eecc']);
			$excel->writeString($j++,$row['zona']);
			$excel->writeString($j++,$row['depto']);
			$excel->writeString($j++,$row['localidad']);
			$excel->writeString($j++,$row['tipored']);
			$excel->writeString($j++,$row['tipoot']);
			$excel->writeString($j++,$row['pep']);
			$excel->writeString($j++,$row['mo']);
			$excel->writeString($j++,$row['fecha_causacion']);
			$excel->writeString($j++,$row['valor']);
			$excel->writeString($j++,$row['grabable']);
			$excel->writeString($j++,$row['facturado']);
			$excel->writeString($j++,$row['iva']);
			$excel->writeString($j++,$row['tipo']);
			$excel->writeString($j++,$row['pedido']);
			$excel->writeString($j++,$row['migo']);
			$excel->writeString($j++,$row['factura']);
			$excel->writeNumber($j++,$row['totalba']);
			$excel->writeNumber($j++,$row['totalmo']);
			$excel->writeNumber($j++,$row['totalma']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = ($i / $rows) * 30.00;
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Actividades
		$i=1;$j=1;
		$excel->openSheet('Actividades');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Consecutivo','H');
		$excel->writeString($j++,'Item','H');
		$excel->writeString($j++,'Descripcion','H');
		$excel->writeString($j++,'Unidad','H');
		$excel->writeString($j++,'Puntos Baremo','H');
		$excel->writeString($j++,'Materiales (CoP$)','H');
		$excel->writeString($j++,'Cantidad','H');
		$excel->writeString($j++,'SubTotal Baremos','H');
		$excel->writeString($j++,'SubTotal Materiales (CoP$)','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.numero,a.version,b.item,b.descripcion,b.unidad,a.puntos,a.material,a.cantidad,TRUNCATE(a.puntos*a.cantidad,8) sb,
		TRUNCATE(a.material*a.cantidad,8) sm
		FROM actividadesxorden a, baremo b,ordenes o
		WHERE a.version>$OT_VER_EJECUCION AND a.idorden=o.id AND a.idbaremo=b.id
		ORDER By a.idorden, a.idbaremo";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeNumber($j++,$row['version']);
			$excel->writeString($j++,$row['item']);
			$excel->writeString($j++,$row['descripcion']);
			$excel->writeString($j++,$row['unidad']);
			$excel->writeNumber($j++,$row['puntos']);
			$excel->writeNumber($j++,$row['material']);
			$excel->writeNumber($j++,$row['cantidad']);
			$excel->writeNumber($j++,$row['sb']);
			$excel->writeNumber($j++,$row['sm']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 30.00 + (($i / $rows) * 30.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Materiales
		$i=1;$j=1;
		$excel->openSheet('Materiales');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Consecutivo','H');
		$excel->writeString($j++,'Codigo','H');
		$excel->writeString($j++,'Descripcion','H');
		$excel->writeString($j++,'Tipo','H');
		$excel->writeString($j++,'Unidad','H');
		$excel->writeString($j++,'Costo (CoP$)','H');
		$excel->writeString($j++,'Cantidad','H');
		$excel->writeString($j++,'SubTotal(CoP$)','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.numero,mo.version,ma.codigo,ma.item,ma.tipo,mo.unidad,mo.valor,mo.movistar,TRUNCATE(mo.valor*mo.movistar,8) sm
		FROM materialesxorden mo, material ma,ordenes o
		WHERE mo.version>$OT_VER_EJECUCION AND mo.movistar>0 AND mo.idmaterial>0 AND mo.idmaterial=ma.id AND mo.idorden=o.id
		ORDER BY mo.idorden,mo.idmaterial";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['version']);
			$excel->writeString($j++,$row['codigo']);
			$excel->writeString($j++,$row['item']);
			$excel->writeString($j++,$row['tipo']);
			$excel->writeString($j++,$row['unidad']);
			$excel->writeNumber($j++,$row['valor']);
			$excel->writeNumber($j++,$row['movistar']);
			$excel->writeNumber($j++,$row['sm']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 60.00 + (($i / $rows) * 20.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Seguimiento
		$i=1;$j=1;
		$excel->openSheet('Seguimiento');
		$excel->openRow($i++);
		$excel->writeString($j++,'Id Liq','H');
		$excel->writeString($j++,'Liquidacion','H');
		$excel->writeString($j++,'Fecha','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'Usuario','H');
		$excel->writeString($j++,'Notas','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT s.idliquidacion,o.numero,s.create_date,e.nombre estado,u.nombre usuario,s.notas
		FROM seguimientoliq s, liquidaciones o, estadoliq e, usuarios u
		WHERE s.idliquidacion=o.id AND s.idestadoliq=e.id AND s.idusuario=u.id
		ORDER BY s.idliquidacion,s.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeNumber($j++,$row['idliquidacion']);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['create_date']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeString($j++,$row['usuario']);
			$excel->writeString($j++,$row['notas']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 80.00 + (($i / $rows) * 20.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();
		$excel->writeFooter();
		session_start();
		$_SESSION['BK_ACTION'] = "$fileName, "." Memoria Usada: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB, Duracion: ". number_format(microtime(true) - $time_start,2) . " seg";
		$_SESSION['BK_PROGRESS'] = "100";
		session_write_close();
		echo $_SESSION['BK_ACTION'];
	break;
	case 'solicitudes':
		$fileName = "Solicitudes_" . date("Y-m-d-H-i-s") . ".xml";

		session_start();
		$_SESSION['BK_ACTION'] = "Generando Reporte de Solicitudes H...";
		$_SESSION['BK_PROGRESS'] = "0";
		session_write_close();

		$excel = new ExcelXML($fileName,$filePath);
		$excel->writeHeader('Solicitudes Detallados');

		//Solicitudes
		$i=1;$j=1;
		$excel->openSheet('Solicitudes');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Numero','H');
		$excel->writeString($j++,'Contrato','H');
		$excel->writeString($j++,'EECC','H');
		$excel->writeString($j++,'Zona','H');
		$excel->writeString($j++,'Depto','H');
		$excel->writeString($j++,'Localidad','H');
		$excel->writeString($j++,'Valor','H');
		$excel->writeString($j++,'Fecha Ingreso','H');
		$excel->writeString($j++,'Solicitante','H');
		$excel->writeString($j++,'Estado Solicitud','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT s.id,o.numero orden,c.numero contrato,ec.nombre eecc,z.nombre zona, d.nombre depto,l.nombre localidad,s.valor,
		s.create_date creado, u.nombre creador,e.nombre estado
		FROM solicitudesh s, estadosol e, ordenes o, usuarios u, contratos c,	deptos d,eecc ec,zonas z,localidades l
		WHERE s.idorden=o.id AND s.idestadosol=e.id AND o.idcontrato=c.id AND o.iddepto=d.id AND s.create_user=u.id AND o.ideecc=ec.id
		AND o.idzona=z.id AND o.idlocalidad=l.id ORDER BY o.id,s.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['orden']);
			$excel->writeString($j++,"SOL-".padZeroLeft($row['id'],8));
			$excel->writeString($j++,$row['contrato']);
			$excel->writeString($j++,$row['eecc']);
			$excel->writeString($j++,$row['zona']);
			$excel->writeString($j++,$row['depto']);
			$excel->writeString($j++,$row['localidad']);
			$excel->writeNumber($j++,$row['valor']);
			$excel->writeString($j++,$row['creado']);
			$excel->writeString($j++,$row['creador']);
			$excel->writeString($j++,$row['estado']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = (($i / $rows) * 70.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Seguimiento
		$i=1;$j=1;
		$excel->openSheet('Seguimiento');
		$excel->openRow($i++);
		$excel->writeString($j++,'Solicitud','H');

		$excel->writeString($j++,'Fecha','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'Usuario','H');
		$excel->writeString($j++,'Notas','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.id,s.create_date,e.nombre estado,u.nombre usuario,s.notas
		FROM seguimientosol s, solicitudesh o, estadosol e, usuarios u
		WHERE s.idsolicitud=o.id AND s.idestadosol=e.id AND s.idusuario=u.id ORDER BY s.idsolicitud,s.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,"SOL-".padZeroLeft($row['id'],8));
			$excel->writeString($j++,$row['create_date']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeString($j++,$row['usuario']);
			$excel->writeString($j++,$row['notas']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 70.00 + (($i / $rows) * 30.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();
		$excel->writeFooter();
		session_start();
		$_SESSION['BK_ACTION'] = "$fileName, "." Memoria Usada: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB, Duracion: ". number_format(microtime(true) - $time_start,2) . " seg";
		$_SESSION['BK_PROGRESS'] = "100";
		session_write_close();
		echo $_SESSION['BK_ACTION'];
	break;
	default:
	echo htmlspecialchars($_REQUEST["mode"]);
	break;
	case 'pedidos':
		$fileName = "Pedidos_" . date("Y-m-d-H-i-s") . ".xml";

		session_start();
		$_SESSION['BK_ACTION'] = "Generando Reporte de Pedidos...";
		$_SESSION['BK_PROGRESS'] = "0";
		session_write_close();

		$excel = new ExcelXML($fileName,$filePath);
		$excel->writeHeader('Pedidos Detallados');

		//Pedidos
		$i=1;$j=1;
		$excel->openSheet('Pedidos');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Numero','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'Fecha Pedido','H');
		$excel->writeString($j++,'Fecha Programada','H');
		$excel->writeString($j++,'Alerta','H');
		$excel->writeString($j++,'EECC','H');
		$excel->writeString($j++,'Zona','H');
		$excel->writeString($j++,'Depto','H');
		$excel->writeString($j++,'Localidad','H');
		$excel->writeString($j++,'Codigo','H');
		$excel->writeString($j++,'Material','H');
		$excel->writeString($j++,'Und','H');
		$excel->writeString($j++,'Cantidad','H');
		$excel->writeString($j++,'Traslado','H');
		$excel->writeString($j++,'Fecha Entrega','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT p.numero,o.numero orden,e.nombre estado,DATE_TRUNCATE(p.create_date,'%Y-%m-%d') create_date,p.fecha_programada,
		ex.nombre eecc,z.nombre zona, d.nombre depto, l.nombre localidad,m.codigo,m.item,m.unidad,p.traslado,cantidad,p.fecha_entrega,
		p.active,IF(e.nombre!='Entregado' AND e.nombre!='Cancelado',IF(CURRENT_DATE > p.fecha_programada,'rojo',
			IF(DATEDIFF(p.fecha_programada,CURRENT_DATE) <= 2,'amarillo','verde')),'') alerta
			FROM pedidosxorden p, ordenes o, estadoped e,material m,contratos c, eecc ex,zonas z,deptos d,localidades l
			WHERE p.idorden=o.id AND p.idestadoped=e.id AND p.idmaterial=m.id AND o.idcontrato=c.id AND c.ideecc=ex.id
			AND o.idzona=z.id AND o.iddepto=d.id AND o.idlocalidad=l.id ORDER BY p.idorden,p.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['orden']);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeString($j++,$row['create_date']);
			$excel->writeString($j++,$row['fecha_programada']);
			$excel->writeString($j++,$row['alerta']);
			$excel->writeString($j++,$row['eecc']);
			$excel->writeString($j++,$row['zona']);
			$excel->writeString($j++,$row['depto']);
			$excel->writeString($j++,$row['localidad']);
			$excel->writeString($j++,$row['codigo']);
			$excel->writeString($j++,$row['item']);
			$excel->writeString($j++,$row['unidad']);
			$excel->writeNumber($j++,$row['cantidad']);
			$excel->writeString($j++,$row['traslado']);
			$excel->writeString($j++,$row['fecha_entrega']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = (($i / $rows) * 70.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Seguimiento
		$i=1;$j=1;
		$excel->openSheet('Seguimiento');
		$excel->openRow($i++);
		$excel->writeString($j++,'Pedido','H');
		$excel->writeString($j++,'Fecha','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'Usuario','H');
		$excel->writeString($j++,'Notas','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.numero,s.create_date,e.nombre estado,u.nombre usuario,s.notas
		FROM seguimientoped s, pedidosxorden o, estadoped e, usuarios u
		WHERE s.idpedido=o.id AND s.idestadoped=e.id AND s.idusuario=u.id ORDER BY s.idpedido,s.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['create_date']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeString($j++,$row['usuario']);
			$excel->writeString($j++,$row['notas']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = 70.00 + (($i / $rows) * 30.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();
		$excel->writeFooter();
		session_start();
		$_SESSION['BK_ACTION'] = "$fileName, "." Memoria Usada: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB, Duracion: ". number_format(microtime(true) - $time_start,2) . " seg";
		$_SESSION['BK_PROGRESS'] = "100";
		session_write_close();
		echo $_SESSION['BK_ACTION'];
	break;
	case 'reservas':
		$fileName = "Reservas_" . date("Y-m-d-H-i-s") . ".xml";

		session_start();
		$_SESSION['BK_ACTION'] = "Generando Reporte de Pedidos...";
		$_SESSION['BK_PROGRESS'] = "0";
		session_write_close();

		$excel = new ExcelXML($fileName,$filePath);
		$excel->writeHeader('Reservas Detallados');

		//Reservas
		$i=1;$j=1;
		$excel->openSheet('Reservas');
		$excel->openRow($i++);
		$excel->writeString($j++,'Orden','H');
		$excel->writeString($j++,'Numero','H');
		$excel->writeString($j++,'Estado OT','H');
		$excel->writeString($j++,'EECC','H');
		$excel->writeString($j++,'Zona','H');
		$excel->writeString($j++,'Departamento','H');
		$excel->writeString($j++,'Localidad','H');
		$excel->writeString($j++,'Fecha Reserva','H');
		$excel->writeString($j++,'Codigo','H');
		$excel->writeString($j++,'Material','H');
		$excel->writeString($j++,'Tipo','H');
		$excel->writeString($j++,'Cantidad','H');
		$excel->writeString($j++,'Estado Reserva','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT s.numero,o.numero orden, eo.nombre estadoot, ex.nombre eecc,z.nombre zona, d.nombre depto,l.nombre localidad,s.tipo,
		DATE_TRUNCATE(s.create_date,'%Y-%m-%d') creado, s.cantidad,e.nombre estado,s.active,m.codigo,m.item
		FROM reservas s, estadores e, ordenes o,estadoot eo, contratos c,eecc ex, zonas z, deptos d,localidades l,material m
		WHERE s.idorden=o.id AND s.idestadores=e.id AND o.idestadoot=eo.id AND o.idcontrato=c.id AND c.ideecc=ex.id AND o.idzona=z.id
		AND o.iddepto=d.id AND o.idlocalidad=l.id AND s.idmaterial=m.id  ORDER BY s.idorden,s.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['orden']);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['estadoot']);
			$excel->writeString($j++,$row['eecc']);
			$excel->writeString($j++,$row['zona']);
			$excel->writeString($j++,$row['depto']);
			$excel->writeString($j++,$row['localidad']);
			$excel->writeString($j++,$row['creado']);
			$excel->writeString($j++,$row['codigo']);
			$excel->writeString($j++,$row['item']);
			$excel->writeString($j++,$row['tipo']);
			$excel->writeNumber($j++,$row['cantidad']);
			$excel->writeString($j++,$row['estado']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
				session_start();
				$_SESSION['BK_PROGRESS'] = (($i / $rows) * 70.00);
				session_write_close();
				$excel->flushFile();
			}
		}
		$excel->closeSheet();

		//Seguimiento
		$i=1;$j=1;
		$excel->openSheet('Seguimiento');
		$excel->openRow($i++);
		$excel->writeString($j++,'Reserva','H');
		$excel->writeString($j++,'Fecha','H');
		$excel->writeString($j++,'Estado','H');
		$excel->writeString($j++,'Usuario','H');
		$excel->writeString($j++,'Notas','H');
		$excel->closeRow();

		//Data
		$sql = "SELECT o.numero,s.create_date,e.nombre estado,u.nombre usuario,s.notas
		FROM seguimientores s, reservas o, estadores e, usuarios u
		WHERE s.idreserva=o.id AND s.idestadores=e.id AND s.idusuario=u.id ORDER BY s.idreserva,s.id";
		$query = db_query($sql);
		$rows = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)) {
			$j=1;
			$excel->openRow($i++);
			$excel->writeString($j++,$row['numero']);
			$excel->writeString($j++,$row['create_date']);
			$excel->writeString($j++,$row['estado']);
			$excel->writeString($j++,$row['usuario']);
			$excel->writeString($j++,$row['notas']);
			$excel->closeRow();
			if($i % CHUNK_ROWS == 0){
					session_start();
				$_SESSION['BK_PROGRESS'] = 70.00 + (($i / $rows) * 30.00);
				session_write_close();
			$excel->flushFile();
			}
		}
		$excel->closeSheet();
		$excel->writeFooter();
		session_start();
		$_SESSION['BK_ACTION'] = "$fileName, "." Memoria Usada: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB, Duracion: ". number_format(microtime(true) - $time_start,2) . " seg";
		$_SESSION['BK_PROGRESS'] = "100";
		session_write_close();
		echo $_SESSION['BK_ACTION'];
	break;
}
function padZeroLeft($str, $len){
	return str_pad($str, $len, "0", STR_PAD_LEFT);
}
?>
