<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'view':

	$id=getVal($_GET['id']);
	$r =  db_query("SELECT s.justificacion,s.valor,s.idestadosol,e.nombre estado,s.idorden,s.create_date,s.modify_date
    FROM `solicitudesh` s, estadosol e
    WHERE s.idestadosol=e.id AND s.`id` = '$id'");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$justificacion = $row['justificacion'];
		$valor = $row['valor'];
		$idestadosol = $row['idestadosol'];
		$estado = $row['estado'];
		$orden = $row['idorden'];

        $created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Gestionar Solictud</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=aprove">
				<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({cache:true});
				});
				</script>
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">Solicitud</a></li>
						<li><a href="#tabs-2">Seguimiento</a></li>
					</ul>
					<div id="tabs-1">
						<table class="data-ro" id="sol-info">
							<tr>
								<td class="title">ID:</td>
								<td class="id"><?php echo htmlspecialchars($id)?>&nbsp;&nbsp;-&nbsp;[Creado: <?php echo htmlspecialchars($created)?>&nbsp;|&nbsp;Modificado: <?php echo htmlspecialchars($modified)?>]&nbsp;-</td>
							</tr>
							<tr>
								<td class="title">Justificacion:</td>
								<td class="field"><?php echo htmlspecialchars($justificacion); ?></td>
							</tr>
							<tr>
								<td class="title">Estado:</td>
								<td class="field"><?php echo htmlspecialchars($estado); ?></td>
							</tr>
							<tr>
								<td class="title">Valor:</td>
								<td class="field">$<?php echo number_format(htmlspecialchars($valor),2); ?></td>
							</tr>
							<tr>
								<td class="title">Cotizaciones:</td>
								<td class="field">
									<table id="cotizaciones" class="ui-widget ui-widget-content" style="width: 100%">
										<thead>
											<tr class="ui-widget-header ">
												<th>#</th>
												<th>Empresa</th>
												<th>Adjunto</th>
											</tr>
										</thead>
										<tbody>
										<?php
											$dataq = @db_query("SELECT id,empresa,titulo,archivo FROM cotizaciones WHERE idsolicitud=$id");
											if (mysqli_num_rows($dataq) != 0) {
												$i = 1;
												while($rowq = mysqli_fetch_array($dataq)){?>
													<tr>
													<td><?php echo $i++; ?></td>
													<td><?php echo htmlspecialchars($rowq['empresa']); ?></td>
													<td><a href="includes/descarga.inc.php?document=<?=htmlspecialchars($rowq['archivo'])?>&amp;ruta=<?=SOL_FILE_WEB?>&amp;name=<?=htmlspecialchars($rowq['titulo'])?>"><?php echo htmlspecialchars($rowq['titulo']); ?></a></td>	
												</tr>
												<?php
												}
											}
										?>
										</tbody>
									</table>
								</td>
							</tr>
						</table>
					</div>
					<div id="tabs-2">
						<?php include_once "parts/sol/tab.seguimiento.inc.php"; ?>
					</div>
				</div>
				<br class="clear"/>
				<div class="formbuttons">
					<?php
					include_once "parts/form.dummy.inc.php";
					include_once "parts/sol/frm.aprobar.inc.php";
					include_once "parts/sol/frm.rechazar.inc.php";
					include_once "parts/sol/frm.cancelar.inc.php";
					include_once "parts/frm.regresar.inc.php";
					?>
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
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
default:
//echo "hiii";
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");
	$rowsxPage=100;
	/*if($_POST['delState']){
		$del = $_POST['chkLocID'];
		$n = count($del);
		for ($i=0; $i < $n; $i++){
			switch($_POST['delState']){
				case 'DeleteMode':
					$sql_update = db_query("DELETE FROM `sectores` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `sectores` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `sectores` SET `active`='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {*/
		$trayfilter = $appuser->getTrayFilter("s.id","idsolicitud","bandejash");
		$locationfilter = $appuser->getLocationFilterOT("o.");
		$sql = "SELECT s.id,s.idorden,o.numero orden,c.numero contrato,ec.nombre eecc,z.nombre zona, d.nombre depto,l.nombre localidad,s.valor,s.create_date creado, u.nombre creador,s.idestadosol,e.nombre estado,s.active,UNIX_TIMESTAMP(CURRENT_TIMESTAMP)-IFNULL(UNIX_TIMESTAMP(s.modify_date),UNIX_TIMESTAMP(s.create_date)) secs FROM solicitudesh s, estadosol e, ordenes o, usuarios u, contratos c,deptos d,eecc ec,zonas z,localidades l WHERE s.idorden=o.id AND s.idestadosol=e.id AND o.idcontrato=c.id AND o.iddepto=d.id AND s.create_user=u.id AND o.ideecc=ec.id AND o.idzona=z.id AND s.active='Si'AND o.idlocalidad=l.id $trayfilter $locationfilter".getAllSQLFilters().getSQLSort("s.create_date","DESC");
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("o.numero"=>"Orden","s.id"=>"Solicitud","c.numero"=>"Contrato","ec.nombre"=>"EECC","z.nombre"=>"Zona","d.nombre"=>"Depto","l.nombre"=>"Localidad","s.valor"=>"Valor","s.create_date"=>"Fecha Ingreso","u.nombre"=>"Solicitante","e.nombre"=>"Estado Solicitud","secs"=>"Tiempo","s.active"=>"Activo");
		$hash = getRandomString();
		setReport($hash,"Solicitudes",$sql);
?>
<?php
include_once "parts/sol/frm.aprobar.mas.inc.php";
include_once "parts/sol/frm.rechazar.mas.inc.php";
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Solicitudes Puntos Pactados en Mi Bandeja</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<button type="button" onclick="returnFilter();">Buscar</button>
			<button type="button" onclick="clearFilter();">Limpiar</button>
			<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
		  <button type="button" onclick="openAprobarSol()">Aprobar</button>
		  <button type="button" onclick="openRechazarSol()">Rechazar</button>
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
					echo "<td ><input type=\"checkbox\" id='".htmlspecialchars($row['id'])."_".htmlspecialchars($row['idestadosol'])."' class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row['id'])."\" onclick=\"unCheckMain();\" /></td>\n";
					echo "<td><a href=\"?menu=$MENU_OT_SRC&amp;mode=show&amp;id=".encrypt(htmlspecialchars($row['idorden']))."\">".htmlspecialchars($row['orden'])."</a></td>\n";
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=view&amp;id=".htmlspecialchars($row['id'])."\">SOL-".padZeroLeft(htmlspecialchars($row['id']),8)."</a></td>\n";
					echo "<td>".htmlspecialchars($row['contrato'])."</td>\n";
					echo "<td>".htmlspecialchars($row['eecc'])."</td>\n";
					echo "<td>".htmlspecialchars($row['zona'])."</td>\n";
					echo "<td>".htmlspecialchars($row['depto'])."</td>\n";
					echo "<td>".htmlspecialchars($row['localidad'])."</td>\n";
					echo "<td>$".number_format(htmlspecialchars($row['valor']),2)."</td>\n";
					echo "<td>".htmlspecialchars($row['creado'])."</td>\n";
					echo "<td>".htmlspecialchars($row['creador'])."</td>\n";
					echo "<td>".htmlspecialchars($row['estado'])."</td>\n";
					echo "<td>".formatSeconds(htmlspecialchars($row['secs']))."</td>\n";
					echo "<td>".htmlspecialchars($row['active'])."</td>\n";
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
	//}
} // end switch
//------------------------------------------------------------------------------------------
?>
