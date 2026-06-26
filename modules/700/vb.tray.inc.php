<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'update':

	$id=getVal($_POST['txtId']);
	$fecha_requerida=$_POST['txtRequerida'];
	$txtSegmento = getVal($_POST['txtSegmento']);
	$txtTipoVB = getVal($_POST['txtTipoVB']);
	$txtEntrega = getVal($_POST['txtEntrega'],"null");
	$txtJefatura = getVal($_POST['txtJefatura'],"null");
	$txtJefe = getVal($_POST['txtJefe'],"null");
	$txtRegion = getVal($_POST['txtRegion'],"null");
	$txtDepto = getVal($_POST['txtDepto'],"null");
	$txtLocalidad = getVal($_POST['txtLocalidad'],"null");
	$txtProyecto = getVal($_POST['txtProyecto'],"null");
	$txtNombre = getStrVal($_POST['txtNombre'],"null");
	$txtDireccion = getStrVal($_POST['txtDireccion'],"null");
	$txtConstructora = getStrVal($_POST['txtConstructora'],"null");
	$txtContacto = getStrVal($_POST['txtContacto'],"null");
	$txtTelefono = getStrVal($_POST['txtTelefono'],"null");
	$txtcluster = getVal($_POST['txtcluster']);
  $txtnumerocto = getVal($_POST['txtnumerocto'],"null");//*****************************
	$txtLB = getVal($_POST['txtLB'],"null");
	$txtBA = getVal($_POST['txtBA'],"null");
	$txtTV = getVal($_POST['txtTV'],"null");
	$txtEstrato = getStrVal($_POST['txtEstrato'],"null");
	$txtViviendas = getStrVal($_POST['txtViviendas'],"null");
	$txtEtapa = getStrVal($_POST['txtEtapa'],"null");
	$txtViviendasEtapa = getStrVal($_POST['txtViviendasEtapa'],"null");
	$uid = $appuser->uid;
		//nuevos campos
		$txtCable = getVal($_POST['txtCable'],"null");
		$txtCentral = getVal($_POST['txtCentral'],"null");
		$txtconversor = getPostStr('txtconversor');
		$txtregiion = getVal($_POST['txtregiion'],"null");
		$txtComuna = getVal($_POST['txtComuna'],"null");
		$txtPoligono = getVal($_POST['txtPoligono'],"null");
		$txtcluster = getVal($_POST['txtcluster'],"null");
		$txtsubclus = getPostStr('txtsubclus');
		$txthogarespas = getPostStr('txthogarespas');
		$txtTipoZona = getVal($_POST['txtTipoZona'],"null");
		$txttipo_vb = getVal($_POST['txttipo_vb'],"null");

	$sql = "UPDATE `viabilidades` SET fecha_requerida='$fecha_requerida',entrega='$txtEntrega',
			idtipovb=$txtTipoVB,idjefatura=$txtJefatura,idjefe=$txtJefe,idregion=$txtRegion,
			iddepto=$txtDepto,idlocalidad=$txtLocalidad,nombre=$txtNombre,direccion=$txtDireccion,
			constructora=$txtConstructora,contacto=$txtContacto,telefono=$txtTelefono,/*idcluster=$txtcluster,*/
			lb=$txtLB,ba=$txtBA,tv=$txtTV,estrato=$txtEstrato,total_viviendas=$txtViviendas,
			etapa=$txtEtapa,viviendas_etapa=$txtViviendasEtapa,numcto=$txtnumerocto,/*////////////////////*/
			modify_user=$uid,`modify_date`=CURRENT_TIMESTAMP,cable=$txtCable,idcentral=$txtCentral,
			conversor=$txtconversor,idpoligono=$txtPoligono,idcomuna=$txtComuna,idcluster=$txtcluster,
			Hogares_pasados=$txthogarespas,id_region=$txtregiion,subcluster=$txtsubclus,idtipo_vb=$txttipo_vb,idtipozona=$txtTipoZona WHERE `id`=$id";

	setRefreshUrl("?menu=".getMenu()."&amp;mode=edit&amp;id=".encrypt($id)."");
	//echo $sql;
	$sql_update = db_query($sql);
	$count = getVal($_POST['uploader_count'],"0");
	for($i=0;$i<$count;$i++){
		if($_POST["uploader_${i}_status"] == "done" ){
      $upname=$_POST["uploader_${i}_tmpname"];
			if (copy(realpath(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR .basename($upname)),realpath(VB_FILE_PATH. DIRECTORY_SEPARATOR .basename("$id.".$upname)))) {
				$sql = "INSERT INTO adjuntosvb(idviabilidad,titulo,archivo,create_user) VALUES($id,'".$_POST["uploader_${i}_name"]."','"."$id.".$_POST["uploader_${i}_tmpname"]."',$uid)";
				$sql_update = db_query($sql);
				unlink(realpath(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR .basename($upname)));
			}else{
				printShortMsg("No se pudo cargar el archivo [".$_POST["uploader_${i}_name"]."]<br />Valide con el administrador del servidor que las directorios tengas los permisos adecuados y que existe suficientes espacio.","warn");
			}
		}
	}
	printAndStay("Actualizando base de datos, por favor espere..","ok");
 break;

case 'edit':

	$id=decrypt(getVal($_GET['id']));
	$r =  db_query("SELECT * FROM `viabilidades` WHERE idestadovb > $VB_ST_CREACION AND `id` = '$id'");

	if ($row = mysqli_fetch_array($r)) {
		$numero = $row['numero'];
		$idestadovb = $row['idestadovb'];
		$estadovb = getNameById("estadovb",$idestadovb);
		$fecha_solicitud = $row['fecha_solicitud'];
		$fecha_requerida = $row['fecha_requerida'];
		$nombre_usuario = getNameById("usuarios",$row['create_user']);
		$tel_usuario = getNameById("usuarios",$row['create_user'],"telefono");
		$segmento = getNameById("segmentos",$row['idsegmento']);
        $idsegmento = $row['idsegmento'];
		$idtipovb = $row['idtipovb'];
		$entrega = $row['entrega'];
		$idjefatura = $row['idjefatura'];
		$idjefe = $row['idjefe'];
		$idregion = $row['idregion'];
		$iddepto = $row['iddepto'];
		$idlocalidad = $row['idlocalidad'];
		$idproyectovb = $row['idproyectovb'];
		$nombre = $row['nombre'];
		$direccion = $row['direccion'];
		$constructora = $row['constructora'];
		$contacto = $row['contacto'];
		$telefono = $row['telefono'];
		$idcluster= $row['idcluster'];
    $numcto= $row['numcto'];//******************************************
		$lb = $row['lb'];
		$ba = $row['ba'];
		$tv = $row['tv'];
		$estrato = $row['estrato'];
		$viviendas = $row['total_viviendas'];
		$etapa = $row['etapa'];
		$viviendas_etapa = $row['viviendas_etapa'];


				//nuevos campos
				$cable = $row['cable'];
				$idcentral = $row['idcentral'];
				$conversor = $row['conversor'];
				$idpoligono = $row['idpoligono'];
				$idcomuna = $row['idcomuna'];
				$idcluster = $row['idcluster'];
				$Hogares_pasados = $row['hogares_pasados'];
				$id_region = $row['id_region'];
				$subcluster = $row['subcluster'];
				$idtipo_vb = $row['idtipo_vb'];
				$idtipozona = $row['idtipozona'];

		$notas_seg = $row['notas_seg'];
		$fecha_respuesta=$row['fecha_respuesta'];
		$fecha_presupuesto=$row['fecha_presupuesto'];
		$idpresupuesto = $row['idpresupuesto'];

		$notas_ing=$row['notas_ing'];
		$respuesta=$row['respuesta'];
		$ideecc=$row['ideecc'];
		$idbandeja=$row['idbandeja'];
		$pago = $row['pago'];
		$create_user=$row['create_user'];

		if(hasVal($ideecc)){
			$eecc_asignado= getNameById("eecc",$ideecc);
		}

		if(hasVal($row['idorden'])){
			$ot_asignada= getNameById("ordenes",$row['idorden'],"numero");
		} else {
			$ot_asignada= getSQLValue("SELECT numero FROM ordenes WHERE idviabilidad=$id");
		}

		$disabled = "disabled='disabled'";
		$selwidth="style='width:392px'";
		$created = $row['create_date'];
		$modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
<script type="text/javascript" src="js/ui/viabilidades.js?ver=<?php echo SGP_VERSION?>"></script>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Ver Viabilidad</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=update">
				<input type="hidden" id="txtId" name="txtId" value="<?php echo $id?>"/>
				<input type="hidden" id="txtClose" name="txtClose" value=""/>
				<?php include_once "parts/vb.sec.1.inc.php"; ?>
				<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({
						cache:true,
						beforeLoad: function(event, ui) {
							ui.panel.html(getSpinner());
						}
						<?php if(strlen($_GET['tab'])>0)echo ",active:".htmlspecialchars($_GET[tab]); ?>
					});
				});
				</script>
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1"><span>Solicitud</span></a></li>
					<?php if( $idestadovb >= $VB_ST_EJECUCION){
					//if(($appuser->isInGroup("$GRP_SEGMENTO,$GRP_INGENIERIA")) && $idestadovb <= $VB_ST_TERMINADA){
						?>
						<li><a href="parts/vb/tab.respuesta.inc.php?id=<?php echo encrypt($id); ?>"><span>Respuesta</span></a></li>
					<?php } ?>
						<li><a href="#tabs-3">Archivos</a></li>
						<li><a href="#tabs-4">Seguimiento</a></li>
            <?php if(hasVal($ot_asignada) && ($appuser->isAdmin() || ($idestadovb > $VB_ST_CREACION))){ ?>
  						<li><a href="parts/vb/tab.seguimiento.ot.inc.php?id=<?php echo encrypt($id); ?>"><span>Seguimiento <?php echo  htmlspecialchars($ot_asignada); ?></span></a></li>
  						<li><a href="parts/vb/tab.registro.ot.inc.php?id=<?php echo encrypt($row['idorden']); ?>"><span>Registro <?php echo  htmlspecialchars($ot_asignada); ?></span></a></li>
  					<?php } ?>
					</ul>
					<div id="tabs-1">
					<?php include_once "parts/vb.sec.2.inc.php"; ?>
					<hr />
					<?php include_once "parts/vb.sec.3.inc.php"; ?>
					</div>
					<div id="tabs-3">
						<?php include_once "parts/vb.sec.up.inc.php"; ?>
					</div>
					<div id="tabs-4">
						<?php include_once "parts/vb.sec.6.inc.php"; ?>
					</div>
				</div>
				<br class="clear"/>
				<div class="formbuttons">
				<?php if(($idestadovb==$VB_ST_REVISION || $idestadovb==$VB_ST_APLAZADA) && ($appuser->isAdmin() || $create_user == $appuser->uid || $appuser->isInGroup("$GRP_SEGMENTO"))) { ?>
					<button type="submit">Guardar</button>
				<?php }
						include_once "parts/form.dummy.inc.php";
						include_once "parts/vb/frm.atender.inc.php";
						include_once "parts/vb/frm.asignar.eecc.inc.php";
						include_once "parts/vb/frm.ppto.inc.php";
						include_once "parts/vb/frm.revision.inc.php";
						include_once "parts/vb/frm.aprobar.inc.php";
						include_once "parts/vb/frm.aplazar.inc.php";
						include_once "parts/vb/frm.cancelar.inc.php";
						include_once "parts/vb/frm.obs.inc.php";
						include_once "parts/vb/frm.pagar.inc.php";
						include_once "parts/vb/frm.no_pagar.inc.php";
               			include_once "parts/vb/frm.cerrar.inc.php";
               			include_once "parts/vb/frm.retornar.inc.php";
					?>
					<?php if(!hasVal($ot_asignada)&&$idestadovb==$VB_ST_APROBADA &&(isInTray("vb","idviabilidad","$GRP_INGENIERIA,$GRP_OP_CENTRAL"))) { ?>
						<button type="button" onclick="this.disabled=addOT(<?php echo "$MENU_OT_MAKE,$idpresupuesto,$id"?>)"><span id="editBtn">Generar OT</span></button>
					<?php } ?>
				</div>
			</form>
		</div>
		<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
	</div>
	</div>
 </div>
  <script type="text/javascript" src="js/val/vb.add.js?ver=<?php echo SGP_VERSION?>"></script>

<?php
	 }
 break;
default:
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");
	$rowsxPage=100;
	if($_POST['delState']){
		$del = $_POST['chkLocID'];
		$n = count($del);
		for ($i=0; $i < $n; $i++){
			switch($_POST['delState']){
				case 'DeleteMode':
					$sql_update = db_query("DELETE FROM `viabilidades` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `viabilidades` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `viabilidades` SET `active`='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		//$idusuario = $appuser->uid;
		$locationfilter = $appuser->getLocationFilterVB("v.");
		$statefilter = "v.idestadovb NOT IN($VB_ST_CREACION,$VB_ST_CANCELADA,$VB_ST_TERMINADA)";
		$trayfilter = $appuser->getTrayFilter("v.id","idviabilidad","bandejasvb");

		$sql = "SELECT v.id,v.numero,v.fecha_solicitud,v.entrega,v.fecha_requerida,v.nombre,v.fecha_respuesta,v.fecha_presupuesto,v.active,ev.nombre estado,u.nombre solicitante
		, s.nombre segmento,py.nombre AS 'proyecto',tv.nombre requerimiento,d.nombre depto,l.nombre localidad,
					 r.nombre region,v.lb,v.total_viviendas,v.constructora,v.etapa, IF(v.idestadovb=$VB_ST_ESTUDIO,IF(v.fecha_requerida 
					 BETWEEN DATE_SUB(current_timestamp,INTERVAL 1 DAY) AND current_timestamp,'amarillo',IF(current_timestamp > v.fecha_requerida,'rojo','verde')),'') alerta,
					IFNULL(e.nombre,'-') eecc,p.numero presupuesto,v.idpresupuesto,o.numero orden,v.idorden,case when v.pago is null then 
					' ' when v.pago ='0' then 'No' when v.pago ='1' then 'Si'end as Pago, v.fecha_pago, v.pedido, v.periodo,UNIX_TIMESTAMP(CURRENT_TIMESTAMP)-IFNULL(UNIX_TIMESTAMP
					(v.modify_date),UNIX_TIMESTAMP(v.create_date)) secs,v.cable cable,c.nombre central
		,v.conversor conversor,re.nombre regiones,po.nombre poligono,co.nombre Municipio,clu.nombre cluster_ftth,v.Hogares_pasados hogares_pasados,v.subcluster,t.nombre tipovb,z.nombre tipozona 
					FROM viabilidades v LEFT JOIN ordenes o ON (v.idorden=o.id) 
					LEFT JOIN presupuesto p ON (v.idpresupuesto=p.id) 
					LEFT JOIN central c on (v.idcentral=c.id) 
                    LEFT JOIN poligono po on v.idpoligono=po.id
                    LEFT JOIN comuna co on v.idcomuna=co.id
                    LEFT JOIN cluster clu on v.idcluster=clu.id 
                    LEFT JOIN region re on v.id_region=re.id 
                    LEFT JOIN tipo_vb t on v.idtipo_vb=t.id 
                    LEFT JOIN tipozona z on v.idtipozona=z.id 
					LEFT JOIN eecc e ON (v.ideecc=e.id), estadovb ev,usuarios u, segmentos s,tipovb tv, localidades l, deptos d, regiones r, proyectovb py
					WHERE $statefilter $trayfilter $locationfilter AND v.idestadovb=ev.id AND v.create_user=u.id AND v.fecha_solicitud>='2017-03-01' AND v.idtipovb=tv.id 
					AND v.idlocalidad=l.id AND v.iddepto=d.id AND v.idsegmento=s.id AND v.idregion=r.id AND py.id=v.idproyectovb".getAllSQLFilters().getSQLSort("v.create_date","DESC");
		
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		
		$fields = array("v.numero"=>"Numero","v.fecha_solicitud"=>"Solicitada","v.fecha_requerida"=>"Requerida","tv.nombre"=>"Requerimiento","v.nombre"=>"Nombre",
			"ev.nombre"=>"Estado","secs"=>"Tiempo","u.nombre"=>"Solicitante","s.nombre"=>"Segmento","py.nombre"=>"proyecto","r.nombre"=>"Region","d.nombre"=>"Depto",
			"l.nombre"=>"Localidad","e.nombre"=>"EECC","p.numero"=>"Presup","o.numero"=>"Orden"
			,"case when v.pago is null then ' ' when v.pago ='0' then 'No' when v.pago ='1' then 'Si' end"=>"Pago", 'fecha_pago' => "Fecha Pago", "pedido" => "Pedido",
			"v.periodo"=>"PeriodoPago","v.entrega"=>"FechaEntrega","v.lb"=>"Linea Basica","v.total_viviendas"=>"N viviendas"
				,"v.constructora"=>"Contructora","v.etapa"=>"Etapa");
			
		$hash = getRandomString();
		setReport($hash,"Viabilidades",$sql);
?>
<?php
	include_once "parts/vb/frm.pago.inc.php";
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Viabilidades en Mi Bandeja</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<button type="button" onclick="returnFilter();">Buscar</button>
			<button type="button" onclick="clearFilter();">Limpiar</button>
			<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
			<?php if($appuser->isAdmin() || $appuser->isInRole($ADMINISTRACION)) { ?>
				<button type="button" onclick="openPagoVb()" class="ui-button ui-corner-all ui-widget">
					<span class="ui-button-icon ui-icon ui-icon-calculator"></span>
					<span class="ui-button-icon-space"> </span>
					Pago
				</button>
			<?php } ?>

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
					echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row['id'])."\" onclick=\"unCheckMain();\" /></td>\n";
					if($row['idesatdovb']!=9){
						echo  "<td><a href=\"?menu=".getMenu()."&amp;mode=edit&amp;id=".encrypt(htmlspecialchars($row['id']))."\">".htmlspecialchars($row['numero'])."</a></td>\n";
					} else{
						echo "<td>".htmlspecialchars($row['numero'])."</td>\n";
					}
					echo "<td>".htmlspecialchars($row['fecha_solicitud'])."</td>\n";
					echo "<td class='".htmlspecialchars($row['alerta'])."'>".htmlspecialchars($row['fecha_requerida'])."</td>\n";
					echo "<td>".htmlspecialchars($row['requerimiento'])."</td>\n";
					echo "<td>".htmlspecialchars($row['nombre'])."</td>\n";
         // echo "<td>".htmlspecialchars($row[direccion])."</td>\n";
					echo "<td>".htmlspecialchars($row['estado'])."</td>\n";
					echo "<td>".formatSeconds(htmlspecialchars($row['secs']))."</td>\n";
					echo "<td>".htmlspecialchars($row['solicitante'])."</td>\n";
					echo "<td>".htmlspecialchars($row['segmento'])."</td>\n";
					echo "<td>".htmlspecialchars($row['proyecto'])."</td>\n";
					echo "<td>".htmlspecialchars($row['region'])."</td>\n";
					echo "<td>".htmlspecialchars($row['depto'])."</td>\n";
					echo "<td>".htmlspecialchars($row['localidad'])."</td>\n";
					echo "<td>".htmlspecialchars($row['eecc'])."</td>\n";
					if(!$appuser->isInGroup($GRP_SEGMENTO)){
						echo "<td><a href=\"?menu=$MENU_PPTO_SRC&amp;mode=show&amp;id=".encrypt(htmlspecialchars($row['idpresupuesto']))."\">".htmlspecialchars($row['presupuesto'])."</a></td>\n";
						echo "<td><a href=\"?menu=$MENU_OT_SRC&amp;mode=show&amp;id=".encrypt(htmlspecialchars($row['idorden']))."\">".htmlspecialchars($row['orden'])."</a></td>\n";
					}
					else {
						echo "<td>".htmlspecialchars($row['presupuesto'])."</td>\n";
						echo "<td>".htmlspecialchars($row['orden'])."</td>\n";
					}
                    			echo "<td>".htmlspecialchars($row['Pago'])."</td>\n";
								echo "<td>".htmlspecialchars($row['fecha_pago'])."</td>\n";
								echo "<td>".htmlspecialchars($row['pedido'])."</td>\n";
                    			echo "<td>".htmlspecialchars($row['periodo'])."</td>\n";
					echo "<td>".htmlspecialchars($row['FechaEntrega'])."</td>\n";
					echo "<td>".htmlspecialchars($row['lb'])."</td>\n";
					echo "<td>".htmlspecialchars($row['total_viviendas'])."</td>\n";
					echo "<td>".htmlspecialchars($row['constructora'])."</td>\n";
					echo "<td>".htmlspecialchars($row['etapa'])."</td>\n";
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
