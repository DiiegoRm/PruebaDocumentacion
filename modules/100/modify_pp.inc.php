<?php
ob_start();

switch($_REQUEST["mode"]){
case 'clone':
		$id=getVal($_GET['id'],"0");
		$id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	if($id > 0){
		$sql = "INSERT INTO presupuesto (`numero`,`fecha_solicitud`,`estado`,`idsegmento`,`idtipoot`,`fecha_requerida`,
			`idcontrato`,`idzona`,`iddepto`			,`idlocalidad`,`idtipored`,`nombre`,`direccion`,`ds`,`epro`,`trs`,`idclaseproyecto`,
			`idtipoproyecto`,`notas`,`ideecc`,`eeccxresponsable`,`resp_movistar`,`resp_eecc`,`iddistribuidor`,`armario`,`cable`,
			`idpop`,`parprim`,`parsec`,`parkm`,`kmfibra`,`mtsducto`,`idvelmaxba`,`distarm`,`iddistcaja`,`viviendas`,`torres`,
			`bocas`,`verticales`,latitud,longitud,atrib_dist,atrib_arm,`create_user`,`modify_user`)
		SELECT '0',CURRENT_DATE,'INICIADO',`idsegmento`,`idtipoot`,`fecha_requerida`,`idcontrato`,`idzona`,`iddepto`,
		`idlocalidad`,`idtipored`,`nombre`,`direccion`,`ds`,`epro`,`trs`,`idclaseproyecto`,`idtipoproyecto`,'Presupuesto Iniciado',
		`ideecc`,`eeccxresponsable`,`resp_movistar`,`resp_eecc`,`iddistribuidor`,`armario`,`cable`,`idpop`,`parprim`,`parsec`,
		`parkm`,`kmfibra`,`mtsducto`,`idvelmaxba`,`distarm`,`iddistcaja`,`viviendas`,`torres`,`bocas`,`verticales`,latitud,
		longitud,atrib_dist,atrib_arm,$appuser->uid,$appuser->uid
	  FROM presupuesto  WHERE estado='CREADO' AND `id` = $id";
		if(db_query($sql) > 0){
			$lastot = getLastId();
			$lastcrono = getSQLValue("SELECT IFNULL(id,0) FROM precronograma WHERE idpresupuesto=$id");
			db_query("INSERT INTO precronograma(idpresupuesto) VALUES($lastot)");
			$newcrono = getLastId();
			db_query("INSERT INTO pretareas(idcrono,idtipo,duracion,antecesor) SELECT $newcrono,t.idtipo,t.duracion,t.antecesor FROM pretareas t, precronograma c WHERE t.idcrono=c.id AND c.idpresupuesto=$id");
			$lastminid = getSQLValue("SELECT MIN(id) FROM pretareas WHERE idcrono=$lastcrono");
			$newminid = getSQLValue("SELECT MIN(id) FROM pretareas WHERE idcrono=$newcrono");
			db_query("UPDATE pretareas SET antecesor = $newminid + (antecesor - $lastminid) WHERE idcrono=$newcrono AND antecesor IS NOT NULL");
			$numero = "PRE-".padZeroLeft($lastot,8);
			db_query("UPDATE `presupuesto` SET numero='$numero' WHERE id=$lastot");

			db_query("INSERT INTO materialesxpresupuesto
					 (`idpresupuesto`,`idbaremo`,`idmaterial`,`factor`,`unidad`,`valor`,`cantidad`,`movistar`,`parkm`,`mtsducto`,`puntoa`,`puntob`,`v1`,`v2`,`v3`,`v4`,`v5`,`v6`,`empalmes`)
					SELECT $lastot,`idbaremo`,`idmaterial`,`factor`,`unidad`,`valor`,`cantidad`,`movistar`,`parkm`,`mtsducto`,`puntoa`,`puntob`,`v1`,`v2`,`v3`,`v4`,`v5`,`v6`,`empalmes`
					FROM materialesxpresupuesto WHERE idpresupuesto=$id");
			db_query("INSERT INTO retalxpresupuesto
					 (`idpresupuesto`,`idbaremo`,`idmaterial`,`factor`,`unidad`,`valor`,`cantidad`,`movistar`,`parkm`,`mtsducto`,`puntoa`,`puntob`,`v1`,`v2`,`v3`,`v4`,`v5`,`v6`,`empalmes`)
					SELECT $lastot,`idbaremo`,`idmaterial`,`factor`,`unidad`,`valor`,`cantidad`,`movistar`,`parkm`,`mtsducto`,`puntoa`,`puntob`,`v1`,`v2`,`v3`,`v4`,`v5`,`v6`,`empalmes`
					FROM retalxpresupuesto WHERE idpresupuesto=$id");
			db_query("INSERT INTO actividadesxpresupuesto (`idpresupuesto`,`idbaremo`,`puntos`,`material`,`cantidad`,`suplemento`,`mtsducto`,`pares`,`empalmes`,depende)
					SELECT $lastot,`idbaremo`,`puntos`,`material`,`cantidad`,`suplemento`,`mtsducto`,`pares`,`empalmes`,IF(depende IS NOT NULL AND depende!='',CONCAT('$lastot','-',SUBSTRING_INDEX(depende,'-',-1)),'')
					FROM actividadesxpresupuesto WHERE idpresupuesto=$id");

			db_query("INSERT INTO totalesxpresupuesto(idpresupuesto,fdepto,cdirecto,claseh,costoaiu,utilidadp,utilidad,ivap,iva,tpb,tmo,tca,tma,totros,tpry) SELECT $lastot,fdepto,cdirecto,claseh,costoaiu,utilidadp,utilidad,ivap,iva,tpb,tmo,tca,tma,totros,tpry FROM totalesxpresupuesto WHERE idpresupuesto=$id");

			db_query("INSERT INTO preciosxpresupuesto(idpresupuesto,idclase,unidad,valor,costo,puntos) SELECT $lastot,idclase,unidad,valor,costo,puntos FROM preciosxpresupuesto WHERE idpresupuesto=$id");

			calcularPresupuesto($lastot);
			setRefreshUrl("?menu=".getMenu()."&amp;mode=edit&amp;id=".encrypt($lastot));
			printAndStay("Actualizando base de datos, por favor espere..<br />Se guardo la Orden de Trabajo $numero","ok");
		}
	}
	break;
case 'save':
$txtMake = getPostNum('txtMake');
$txtId = getPostNum('txtId');

if($txtMake == "100"){

	$mtsducto = getPreMtsDucto($txtId);
	$parkm = getPreParKM($txtId);
	$kmfibra = getPreKmFibra($txtId);

	db_query("UPDATE presupuesto SET parkm=$parkm,kmfibra=$kmfibra,mtsducto=$mtsducto,estado='CREADO' WHERE id=$txtId");

    setRefreshUrl("?menu=$MENU_PPTO_SRC&amp;mode=show&amp;id=".encrypt($txtId));
	printAndStay("Actualizando base de datos, por favor espere..<br />Se guardo el Presupuesto","ok");

} else {
	$txtSegmento = getPostNum('txtSegmento');
	$txtTipoOT = getPostNum('txtTipoOT');
	$txtContrato = getPostNum('txtContrato');
	$txtResponsable = getPostNum('txtResponsable');
	$txtTipoRed = getPostNum('txtTipoRed');
	$txtZona = getPostNum('txtZona');
	$txtDepto = getPostNum('txtDepto');
	$txtLocalidad = getPostNum('txtLocalidad');
	$txtNombre = getPostStr('txtNombre');
	$txtDireccion = getPostStr('txtDireccion');
	$txtDs = getPostStr('txtDs');
	$txtEpro = getPostStr('txtEpro');
	$txtTrs = getPostStr('txtTrs');
	$txtClase = getPostNum('txtClase');
	$txtTipo = getPostNum('txtTipo');
	$txtRespMovistar = getPostNum('txtRespMovistar');
	$txtRespEECC = getPostNum('txtRespEECC');
	$txtObs = getPostStr('txtObs');
	$txtDistribuidor = getPostNum('txtDistribuidor');
	$txtPOP = getPostNum('txtPOP');
	$txtArmario = getPostStr('txtArmario');
	$txtCable = getPostStr('txtCable');
	$txtPares1 = getPostStr('txtPares1');
	$txtPares2 = getPostStr('txtPares2');
	$txtVelMax = getPostStr('txtVelMax');
	$txtDist2 = getPostStr('txtDist2');
	$txtDist1 = getPostStr('txtDist1');
	$txtVivienda = getPostStr('txtVivienda');
	$txtTorres = getPostStr('txtTorres');
	$txtBocas = getPostStr('txtBocas');
	$txtVerticales = getPostStr('txtVerticales');
	$txtLatitud = getPostStr('txtLatitud');
	$txtLongitud = getPostStr('txtLongitud');
	$CheckAtriArm = getPostStr('checkArm');
	$CheckAtriDist = getPostStr('checkDist');


	if(hasVal($txtTipoOT)&&hasVal($txtSegmento)){
		$uid = $appuser->uid;
		$sql_ins = db_query("UPDATE `presupuesto` SET `fecha_solicitud`=CURRENT_DATE,`idsegmento`=$txtSegmento,`idtipoot`=$txtTipoOT,`idcontrato`=$txtContrato,`idzona`=$txtZona,`iddepto`=$txtDepto,`idlocalidad`=$txtLocalidad,`idtipored`=$txtTipoRed,`nombre`=$txtNombre,`direccion`=$txtDireccion,`ds`=$txtDs,`epro`=$txtEpro,`trs`=$txtTrs,`idclaseproyecto`=$txtClase,`idtipoproyecto`=$txtTipo,resp_movistar=$txtRespMovistar,resp_eecc=$txtRespEECC,iddistribuidor=$txtDistribuidor,idpop=$txtPOP,armario=$txtArmario,cable=$txtCable,parprim=$txtPares1,parsec=$txtPares2,idvelmaxba=$txtVelMax,distarm=$txtDist1,iddistcaja=$txtDist2,viviendas=$txtVivienda,torres=$txtTorres,bocas=$txtBocas,verticales=$txtVerticales,notas=$txtObs,latitud=$txtLatitud,longitud=$txtLongitud,atrib_dist=$CheckAtriDist,atrib_arm=$CheckAtriArm WHERE id=$txtId");
		if($sql_ins > 0){
			//calcularPresupuesto($txtId);
		}
		setRefreshUrl("?menu=".getMenu()."&amp;mode=edit&amp;id=".encrypt($txtId));
		printAndStay("Actualizando base de datos, por favor espere..<br />Se guardo el Presupuesto","ok");
	} else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
}
break;
 case 'edit':

    $tipo=decrypt(getVal($_GET['tipo']));
	$id=decrypt(getVal($_GET['id']));
	$id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
    $txid=getVal($_GET['id']);
    if ($tipo=="2"){
        db_query("UPDATE presupuesto SET /*estado='INICIADO'*/ WHERE id=$txid");

        setRefreshUrl("?menu=$MENU_PPTO_TRAY&amp;mode=edit&amp;id=".encrypt($txid));
	    printAndStay("Actualizando base de datos, por favor espere..<br />Se guardo el Presupuesto","ok");
      }
	$userfilter = (!$appuser->isAdmin())?"AND create_user=$appuser->uid ":"";
    $r =  db_query("SELECT * FROM `presupuesto` WHERE /*estado='INICIADO' AND */`id` = '$id' $userfilter");
		$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$estado = $row['estado'];
		$numero = $row['numero'];
		$nombre_usuario = getNameById("usuarios",$row['create_user']);
		$tel_usuario = getNameById("usuarios",$row['create_user'],"telefono");

		$fecha_solicitud = $row['fecha_solicitud'];
		$fecha_requerida = $row['fecha_requerida'];

		$idsegmento = $row['idsegmento'];
		$idtipoot = $row['idtipoot'];
		$idcontrato = $row['idcontrato'];
		$ideecc = $row['ideecc'];
		$idresponsable = $row['eeccxresponsable'];
		$idtipored = $row['idtipored'];

		$idzona = $row['idzona'];
		$iddepto = $row['iddepto'];
		$idlocalidad = $row['idlocalidad'];

		$nombre = $row['nombre'];
		$direccion = $row['direccion'];
		$ds = $row['ds'];
		$epro = $row['epro'];
		$trs = $row['trs'];

		$idclaseproyecto = $row['idclaseproyecto'];
		$idtipoproyecto = $row['idtipoproyecto'];
		$notas = $row['notas'];
		$resp_movistar = $row['resp_movistar'];
		$resp_eecc = $row['resp_eecc'];

		$iddistribuidor = $row['iddistribuidor'];
		$idpop = $row['idpop'];
		$armario = $row['armario'];
		$cable = $row['cable'];
		$parprim = $row['parprim'];
		$parsec = $row['parsec'];
		$parkm = $row['parkm'];
		$kmfibra = $row['kmfibra '];
		$mtsducto = $row['mtsducto'];
		$idvelmaxba = $row['idvelmaxba'];
		$distarm = $row['distarm'];
		$iddistcaja = $row['iddistcaja'];
		$viviendas = $row['viviendas'];
		$torres = $row['torres'];
		$bocas = $row['bocas'];
		$verticales = $row['bocas'];
		$latitud = $row['latitud'];
		$longitud = $row['longitud'];
		$AtribDist = $row['atrib_dist'];
		$AtribArm = $row['atrib_arm'];

		$disabled = "disabled='disabled'";
        $created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
		$mtsducto = getPreMtsDucto($id);
		$parkm = getPreParKM($id);
		$kmfibra = getPreKmFibra($id);
		$completed = hasVal($idsegmento)&&hasVal($idtipoot)&&hasVal($fecha_requerida)&&
			hasVal($idcontrato)&&hasVal($idzona)&&hasVal($iddepto) &&
			hasVal($idlocalidad)&&hasVal($idtipored)&&hasVal($nombre)&&
			hasVal($direccion)&&hasVal($idclaseproyecto)&&hasVal($idtipoproyecto)&&
			hasVal($resp_movistar)&&hasVal($resp_eecc);
		if($completed){
			$vtotalproy = getSQLValue("SELECT IFNULL(tpry,0) FROM totalesxpresupuesto WHERE idpresupuesto=$id");
			switch($idtipored){
				case $OT_TIPO_RED_COBRE:
					$completed = hasVal($cable)&& strlen($parprim)&&
						strlen($parsec)&&hasVal($parkm)&&
						hasVal($idpop)&&hasVal($idvelmaxba)&&
						strlen($distarm)>0&&hasVal($iddistcaja)&&
						strlen($viviendas)>0&&hasVal($iddistribuidor);
					break;
				case $OT_TIPO_RED_FIBRA:
					$completed = hasVal($kmfibra)&&hasVal($idpop);
					break;
				case $OT_TIPO_RED_TV:
					$completed = strlen($viviendas)>0&& strlen($torres)>0&&
						hasVal($bocas)&&hasVal($verticales);
					break;
			}
		}
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Ver Presupuesto 'Modificacion'</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<input type="hidden" id="txtId" name="txtId" value="<?php echo $id?>"/>
				<input type="hidden" id="txtMake" name="txtMake" value=""/>
				<input type="hidden" id="txtChanged" name="txtChanged" value="NO"/>
				<?php include_once "parts/pp/sec.header.inc.php"; ?>
				<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({
						cache:true,
							beforeLoad: function(event, ui) {
								ui.panel.html(getSpinner());
						},
						select: function(event, ui) {
							var idx = $(this).tabs('option', 'selected');
							if(idx === 0 && $("#txtChanged").val()=="SI"){
								if(!confirm('Si ha realizado cambios de datos debe guardarlos, desea salir?')){
									return false;
								}
							}
							return true;
						}
						<?php if(strlen($_GET['tab'])>0)echo htmlspecialchars(",active:$_GET[tab]"); ?>
					});
				});
				</script>
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">Presupuesto</a></li>
						<li><a href="parts/pp/tab.totales.ro.inc.php?id=<?php echo encrypt($id); ?>"><span>Total Baremos</span></a></li>
						<li><a href="#tabs-3">Actividades Baremos</a></li>
						<li><a href="parts/pp/tab.materiales.ro.inc.php?id=<?php echo encrypt($id); ?>"><span>Materiales</span></a></li>
						<li><a href="parts/pp/tab.retal.ro.inc.php?id=<?php echo encrypt($id); ?>"><span>Retal</span></a></li>
						<li><a href="#tabs-5">Cronograma</a></li>
						<li><a href="#tabs-6">Adjuntos</a></li>
					</ul>
					<div id="tabs-1" style="display: none;">
						<?php include_once "parts/pp/tab.ppto.rw.inc.php"; ?>
					</div>
					<div id="tabs-3" style="display: none;">
						<?php include_once "parts/pp/tab.baremos.rw.inc.php"; ?>
					</div>
					<div id="tabs-5" style="display: none;">
						<?php include_once "parts/pp/tab.cronograma.rw.inc.php"; ?>
						<div id="ganttChart"></div>
						<br /><br />
						<div id="eventMessage"></div>
					</div>
					<div id="tabs-6" style="display: none;">
						<?php include_once "parts/pp/tab.adjuntos.rw.inc.php"; ?>
					</div>
				</div>
				<br class="clear"/>
				<div class="formbuttons">
					<?php if($appuser->isInRole("$GENERAR_OT_CAPEX,$GENERAR_OT_OPEX,$CARGAR_PRESUPUESTO")){
						if($completed){
							if($vtotalproy > 0){ ?>
						<!--<button onclick="makePresupuesto();" >Generar Ppto</button>-->

					<?php
						} else {
							echo "<span id='message' class='msg-box note'>Para generar el presupuesto debe ingresar algunas actividades</span>";
						}
					} else  echo "<span id='message' class='msg-box note'>Complete los campos obligatorios para poder generar el Presupuesto.</span>";
					}?>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>
	</div>
 </div>
<?php
	 }
 break;
default:
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");
	$pageNO=mysqli_real_escape_string($dbsgp,$pageNO);//KIUWAN
	$rowsxPage=100;
	if($_POST['delState']){
		$del = $_POST['chkLocID'];
		$del=mysqli_real_escape_string($dbsgp,$del);//KIUWAN
		$n = count($del);
		for ($i=0; $i < $n; $i++){
			switch($_POST['delState']){
				case 'DeleteMode':
					db_query("DELETE FROM `actividadesxpresupuesto` WHERE idpresupuesto={$del[$i]}");
					db_query("DELETE FROM `adjuntospp` WHERE idpresupuesto={$del[$i]}");
					db_query("DELETE FROM `materialesxpresupuesto` WHERE idpresupuesto={$del[$i]}");
					db_query("DELETE FROM `preciosxpresupuesto` WHERE idpresupuesto={$del[$i]}");
					db_query("DELETE FROM `pretareas` WHERE id IN (SELECT * FROM (SELECT p.id FROM pretareas p, precronograma c WHERE p.idcrono=c.id AND c.idpresupuesto={$del[$i]}) del)");
					db_query("DELETE FROM `precronograma` WHERE idpresupuesto={$del[$i]}");
					db_query("DELETE FROM `retalxpresupuesto` WHERE idpresupuesto={$del[$i]}");
					db_query("DELETE FROM `totalesxpresupuesto` WHERE idpresupuesto={$del[$i]}");
					db_query("UPDATE `ordenes` SET idpresupuesto=NULL WHERE idpresupuesto={$del[$i]}");
					db_query("UPDATE `viabilidades` SET idpresupuesto=NULL WHERE idpresupuesto={$del[$i]}");
					db_query("DELETE FROM `presupuesto` WHERE id={$del[$i]}");
					break;
				/*case 'EnableMode':
					$sql_update = db_query("UPDATE `presupuesto` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `presupuesto` SET `active`='No' WHERE id={$del[$i]}");
					break;*/
				case 'CancelMode':
					$sql_update = db_query("UPDATE `presupuesto` SET `estado`=4 ,modify_date=CURRENT_TIMESTAMP, modify_user=$appuser->uid WHERE id={$del[$i]}");
					break;
				case 'AnulMode':
					$sql_update = db_query("UPDATE `presupuesto` SET `estado`=5 ,modify_date=CURRENT_TIMESTAMP, modify_user=$appuser->uid WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	} else {
		$userfilter = (!$appuser->isAdmin())?"AND o.create_user=$appuser->uid ":"";
		$sql = "SELECT o.id,o.numero,o.fecha_solicitud,o.fecha_requerida,o.nombre,o.active,o.estado,tot.nombre req,ee.nombre eecc,z.nombre zona, d.nombre depto,l.nombre localidad,tr.nombre red,cp.nombre proyecto,tp.tmo,tp.tma FROM presupuesto o LEFT JOIN eecc ee ON o.ideecc=ee.id LEFT JOIN zonas z ON o.idzona=z.id LEFT JOIN deptos d ON o.iddepto=d.id LEFT JOIN localidades l ON o.idlocalidad=l.id LEFT JOIN tipored tr ON o.idtipored=tr.id LEFT JOIN claseproyecto cp ON o.idclaseproyecto=cp.id LEFT JOIN totalesxpresupuesto tp ON tp.idpresupuesto=o.id,tipoot tot WHERE o.estado='INICIADO' $userfilter AND o.idtipoot=tot.id".getAllSQLFilters().getSQLSort("o.create_date","DESC");
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("o.numero"=>"Numero","o.fecha_solicitud"=>"Solicitada","o.fecha_requerida"=>"Requerida","estado"=>"Estado","ee.nombre"=>"EECC","z.nombre"=>"Zona","d.nombre"=>"Depto","l.nombre"=>"Localidad","tot.nombre"=>"Tipo","o.nombre"=>"Nombre","tr.nombre"=>"TipoRed","cp.nombre"=>"Proyecto","tp.tmo"=>"Total MO","tp.tma"=>" Total MA");
		$hash = getRandomString();
		setReport($hash,"Presupuestos",$sql);
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Presupuestos En Creacion</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<?php printActionButtonBar(array('delete'=>'returnDelete();'))?>
			<button type="button" onclick="returnFilter();">Buscar</button>
			<button type="button" onclick="clearFilter();">Limpiar</button>
			<?php if($appuser->isInRole($GESTIONAR_TABLAS)){ ?>
			<button type="button" onclick="returnCancelpp();">Cancelar</button>
			<button type="button" onclick="returnanulpp();">Anular</button>
			<?php } ?>
			<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
		</div>

		<div class="actionbar">
			<div class="actionbuttons">
			</div>
			<div class="noresultsbar"><?php echo htmlspecialchars($regCount)==0?"No hay registros para mostrar!":""?></div>
			<div class="pagingbar">
				<?php paginate($maxPage, $pageNO, $regCount);?>
			</div>
			<br class="clear" />
		</div>
		<br class="clear" />
		<div id="Layer1" style="width:100%;height:auto;overflow-x:scroll;">
		<table cellspacing="0" cellpadding="0" class="data-table">
			<thead>
			<?php printFilterGrid($fields)?>
			<tr>
				<td width="20">
					<input type="checkbox" name="allCheck" id="allCheck" class="checkbox" style="margin-left:1px" onclick="doHandleAll()" />
				</td>
				<?php printColumns($fields);?>
				</tr>
			</thead>
			<tbody>
<?php
				$query = db_query("$sql LIMIT $rowFrom, $rowsxPage");
				//echo "$sql LIMIT $rowFrom, $rowsxPage";
				$i=0;
				while($row = mysqli_fetch_array($query)) {
					$style = $row['active']=='Si'?($i++%2==0)?"odd":"even":"disabled";
					echo "<tr class=\"$style\">\n";
					echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row[id])."\" onclick=\"unCheckMain();\" /></td>\n";
					if($row['idesatdovb']!=9){
						echo "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".encrypt(htmlspecialchars($row['id']))."\">".htmlspecialchars($row[numero])."</a></td>\n";
					} else{
						echo "<td>".htmlspecialchars($row[numero])."</td>\n";
					}
					echo "<td>".htmlspecialchars($row[fecha_solicitud])."</td>\n";
					echo "<td>".htmlspecialchars($row[fecha_requerida])."</td>\n";
					echo "<td>".htmlspecialchars($row[estado])."</td>\n";
					echo "<td>".htmlspecialchars($row[eecc])."</td>\n";
					echo "<td>".htmlspecialchars($row[zona])."</td>\n";
					echo "<td>".htmlspecialchars($row[depto])."</td>\n";
					echo "<td>".htmlspecialchars($row[localidad])."</td>\n";
					echo "<td>".htmlspecialchars($row[req])."</td>\n";
					echo "<td>".htmlspecialchars($row[nombre])."</td>\n";
					echo "<td>".htmlspecialchars($row[red])."</td>\n";
					echo "<td>".htmlspecialchars($row[proyecto])."</td>\n";
					echo "<td style='text-align:right'>$".number_format(htmlspecialchars($row['tmo']),2)."</td>\n";
					echo "<td style='text-align:right'>$".number_format(htmlspecialchars($row['tma']),2)."</td>\n";
					echo "</tr>\n";
				}
?>
			</tbody>
		</table>
		</div>
	</form>
</div>
</div>
</div>
<?php
	}
} // end switch
//------------------------------------------------------------------------------------------
?>
