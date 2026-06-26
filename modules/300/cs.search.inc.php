<?php
ob_start();

switch($_REQUEST["mode"]){
	 case 'view':

	$id=decrypt(getVal($_GET['id']));
	$r =  db_query("SELECT l.*, o.numero AS ot FROM liquidaciones l LEFT JOIN ordenes o ON l.idorden=o.id WHERE l.id = '$id'");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$idorden = $row['idorden'];
		$version = $row['version'];
		$numeroot = $row['ot'];
?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Ver Liquidacion - <?php echo htmlspecialchars(" Orden: $numeroot"); ?></h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=update">
				<input type="hidden" id="txtId" name="txtId" value="<?php echo $id?>"/>
				<input type="hidden" id="txtClose" name="txtClose" value=""/>
				<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({
						cache:true,
						beforeLoad: function(event, ui) {
							ui.panel.html(getSpinner());
						}
					});
				});
				</script>
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">Liquidacion</a></li>
						<li><a href="parts/liq/tab.baremos.ro.inc.php?id=<?php echo encrypt($idorden); ?>&amp;ver=<?php echo encrypt($version); ?>"><span>Act. Baremos</span></a></li>
						<li><a href="parts/liq/tab.materiales.ro.inc.php?id=<?php echo encrypt($idorden); ?>&amp;ver=<?php echo encrypt($version); ?>"><span>Materiales</span></a></li>
						<li><a href="parts/liq/tab.seguimiento.ro.inc.php?id=<?php echo encrypt($id); ?>"><span>Seguimiento</span></a></li>
					</ul>
					<div id="tabs-1">
						<?php include_once "parts/liq/tab.liquidacion.inc.php"; ?>
					</div>
				</div>
				<br class="clear"/>
				<div class="formbuttons">
					<?php
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
default:
	if ($_POST["enviado"]=='Boton'){
			$variable="";
		} else {
			$variable=" AND o.id='-1'";
		}
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
					$sql_update = db_query("DELETE FROM `liquidaciones` WHERE id={$del[$i]}");
					break;
				case 'EnableMode':
					$sql_update = db_query("UPDATE `liquidaciones` SET `active`='Si' WHERE id={$del[$i]}");
					break;
				case 'DisableMode':
					$sql_update = db_query("UPDATE `liquidaciones` SET `active`='No' WHERE id={$del[$i]}");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		$eeccfilter = $appuser->getEeccFilterOT("ideecc","o.");
		$locationfilter = $appuser->getLocationFilterOT("o.");
		$sql = "SELECT l.id,l.idorden,o.numero orden,l.fecha_liquidacion,l.fecha_causacion,c.numero contrato,ex.nombre eecc,z.nombre zona,
		d.nombre depto, lo.nombre localidad,tr.nombre tipored,tot.nombre tipoot,u.nombre as'Solicitante' ,pe.nombre pep, o.nombre ,pe.mo,
		e.nombre estado,l.tipo,l.numero,l.pedido,l.migo,l.factura,l.valor,l.grabable,l.facturado,l.iva,l.totalma,l.active,o.pm_orden,
		o.pm_solped,  UNIX_TIMESTAMP(CURRENT_TIMESTAMP)-IFNULL(UNIX_TIMESTAMP(l.modify_date),UNIX_TIMESTAMP(l.create_date)) secs
		FROM liquidaciones l, estadoliq e, ordenes o,contratos c, eecc ex,zonas z,deptos d,localidades lo,tipored tr,tipoot tot,peps pe,usuarios u
		WHERE l.idestadoliq=e.id AND o.fecha_solicitud>='2017-03-01' AND l.idorden=o.id $variable AND o.idcontrato=c.id AND c.ideecc=ex.id AND o.idzona=z.id
		AND u.id=o.create_user AND o.iddepto=d.id AND o.idlocalidad=lo.id AND o.idtipored=tr.id AND o.idtipoot=tot.id
		AND o.idpep=pe.id  $eeccfilter $locationfilter".getAllSQLFilters().getSQLSort("l.create_date","DESC");
		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);
		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("o.numero"=>"Orden","l.numero"=>"Numero","l.fecha_liquidacion"=>"Fecha Liquidacion","e.nombre"=>"Estado","l.modify_date"=>"Tiempo","c.numero"=>"Contrato","ex.nombre"=>"EECC","z.nombre"=>"Zona","d.nombre"=>"Depto","lo.nombre"=>"Localidad","tr.nombre"=>"TipoRed","tot.nombre"=>"TipoOT","pe.nombre"=>"NombrePEP","o.nombre"=>"Nombre Proyecto","pe.mo"=>"M.O.PEP","l.fecha_causacion"=>"Fecha Causacion","l.valor"=>"Valor Sin Utilidad","l.grabable"=>"Base Grabable","l.facturado"=>"Valor Facturado (sin iva)","l.iva"=>"Iva","l.totalma"=>"Materiales","l.tipo"=>"Tipo","l.pedido"=>"Pedido","l.migo"=>"Migo","l.factura"=>"Factura","o.pm_orden"=>"Orden PM","o.pm_solped"=>"Solped PM" );
		$hash = getRandomString();
		setReport($hash,"Causaciones",$sql);
?>
<?php
	include_once "parts/liq/frm.exportar.mas.inc.php";
	include_once "parts/liq/frm.tipo.inc.php";
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Causaciones</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="enviado" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<button type="button" onclick="returnFilterLoad();">Buscar</button>
			<button type="button" onclick="clearFilter();">Limpiar</button>
			<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
			<?php if($appuser->isAdmin() || $appuser->isInRole($ADMINISTRACION)) { ?>
			<button type="button" onclick="exportarAct();">Exportar Actividades</button>
			<?php }  if($appuser->isAdmin() || $appuser->isInRole($ADMINISTRACION)) { ?>
				<button type="button" onclick="openTipoLiq();" class="ui-button ui-corner-all ui-widget">
					<span class="ui-button-icon ui-icon ui-icon-pencil"></span>
					<span class="ui-button-icon-space"> </span>
					Modificar Tipo
				</button>
			<?php } ?>
		</div>

		<div class="actionbar">
			<div class="actionbuttons">
			</div>
			<div class="noresultsbar"><?php echo  htmlspecialchars($regCount)==0?"No hay registros para mostrar!":""?></div>
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
					echo  "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row[id])."\" onclick=\"unCheckMain();\" /></td>\n";
					echo  "<td nowrap='true'><a href=\"?menu=$MENU_OT_SRC&amp;mode=show&amp;id=".encrypt(htmlspecialchars($row['idorden']))."\">".htmlspecialchars($row[orden])."</a></td>\n";
					echo  "<td nowrap='true'><a href=\"?menu=".getMenu()."&amp;mode=view&amp;id=".encrypt(htmlspecialchars($row['id']))."\">".htmlspecialchars($row[numero])."</a></td>\n";
					echo  "<td class='".htmlspecialchars($row[alerta])."'>".htmlspecialchars($row[fecha_liquidacion])."</td>\n";
					echo  "<td>".htmlspecialchars($row[estado])."</td>\n";
					echo  "<td>".formatSeconds(htmlspecialchars($row['secs']))."</td>\n";
					echo  "<td nowrap='true'>".htmlspecialchars($row[contrato])."</td>\n";
					echo  "<td>".htmlspecialchars($row[eecc])."</td>\n";
					echo  "<td nowrap='true'>".htmlspecialchars($row[zona])."</td>\n";
					echo  "<td nowrap='true'>".htmlspecialchars($row[depto])."</td>\n";
					echo  "<td nowrap='true'>".htmlspecialchars($row[localidad])."</td>\n";
					echo  "<td>".htmlspecialchars($row[tipored])."</td>\n";
					echo  "<td>".htmlspecialchars($row[tipoot])."</td>\n";
					echo  "<td nowrap='true'>".htmlspecialchars($row[pep])."</td>\n";
                                    echo  "<td>".htmlspecialchars($row[nombre])."</td>\n";
					echo  "<td nowrap='true'>".htmlspecialchars($row[mo])."</td>\n";
					echo  "<td nowrap='true'>".htmlspecialchars($row[fecha_causacion])."</td>\n";
					echo  "<td>$".number_format(htmlspecialchars($row['valor'],2))."</td>\n";
					echo  "<td>$".number_format(htmlspecialchars($row['grabable'],2))."</td>\n";
					echo  "<td>$".number_format(htmlspecialchars($row['facturado'],2))."</td>\n";
					echo  "<td>$".number_format(htmlspecialchars($row['iva'],2))."</td>\n";
					echo  "<td>$".number_format(htmlspecialchars($row['totalma'],2))."</td>\n";
					echo  "<td>".htmlspecialchars($row[tipo])."</td>\n";
					echo  "<td>".htmlspecialchars($row[pedido])."</td>\n";
					echo  "<td>".htmlspecialchars($row[migo])."</td>\n";
					echo  "<td>".htmlspecialchars($row[factura])."</td>\n";
					echo  "<td>".htmlspecialchars($row[pm_orden])."</td>\n";
					echo  "<td>".htmlspecialchars($row[pm_solped])."</td>\n";
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
