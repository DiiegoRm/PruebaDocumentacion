<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'view':

	$id=getVal($_GET['id']);
	$r =  db_query("SELECT s.numero,s.tipo,s.fecha,s.idestadores,e.nombre estado,s.idorden,s.create_date,s.modify_date, CONCAT(m.codigo,'|',m.item) material,s.fecha,s.cantidad,s.lote,s.peso FROM `reservasretal` s, estadores e, material m WHERE s.idestadores=e.id AND s.idmaterial=m.id AND s.`id` = '$id'");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$numero = $row['numero'];
		$tipo = $row['tipo'];
		$material = $row['material'];
		$idestadores = $row['idestadores'];
		$estado = $row['estado'];
		$orden = $row['idorden'];
		$fecha = $row['fecha'];
		$cantidad = $row['cantidad'];
		$lote = $row['lote'];
		$peso = $row['peso'];

		$created = $row['create_date'];
		$modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Gestionar Reserva</h2></div>
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
						<table class="data-ro" id="res-tray-header">
							<tr>
								<td class="title">ID:</td><td colspan="3" class="id"><?php echo htmlspecialchars($id)?>&nbsp;&nbsp;-&nbsp;[Creado: <?php echo htmlspecialchars($created)?>&nbsp;|&nbsp;Modificado: <?php echo htmlspecialchars($modified)?>]&nbsp;-</span></td>
							</tr>
							<tr>
								<td class="title">Numero:</td><td class="field"><?php  echo htmlspecialchars($numero)?></td>
								<td class="title">Fecha:</td><td class="field"><?php  echo htmlspecialchars($fecha)?></td>
							</tr>
							<tr>
								<td class="title">Tipo:</td><td class="field"><?php echo htmlspecialchars($tipo)?></td>
								<td class="title">Estado:</td><td class="field"><?php echo htmlspecialchars($estado)?></td>
							</tr>
							<tr>
								<td class="title">Material:</td><td class="field"><?php echo htmlspecialchars($material)?></td>
								<td class="title">Cantidad:</td><td class="field"><?php echo htmlspecialchars($cantidad)?></td>
							</tr>
							<tr>
								<td class="title">Lote:</td><td class="field"><?php echo htmlspecialchars($lote)?></td>
								<td class="title">Peso:</td><td class="field"><?php echo htmlspecialchars($peso)?></td>
							</tr>
						</table>
					</div>
					<div id="tabs-2">
						<?php include_once "parts/res/tab.seguimiento.retal.inc.php"; ?>
					</div>
				</div>
				<br class="clear"/>
				<div class="formbuttons">
					<?php
					$RETAL=1;
					include_once "parts/form.dummy.inc.php";
					include_once "parts/res/frm.contabilizar.inc.php";
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
		$trayfilter = $appuser->getTrayFilter("s.id","idreserva","bandejasresret");
		$locationfilter = $appuser->getLocationFilterOT("o.");
		$sql = "SELECT s.id,s.numero,s.idorden,o.numero orden, eo.nombre estadoot, ex.nombre eecc,z.nombre zona, d.nombre depto,l.nombre localidad,s.fecha,s.tipo,DATE_FORMAT(s.create_date,'%Y-%m-%d') creado, s.cantidad,e.nombre estado,s.active,m.codigo,m.item,o.pm_orden,o.pm_reserva FROM reservasretal s, estadores e, ordenes o,estadoot eo, contratos c,eecc ex, zonas z, deptos d,localidades l,material m WHERE s.idorden=o.id AND s.idestadores=e.id AND o.idestadoot=eo.id AND o.idcontrato=c.id AND c.ideecc=ex.id AND o.idzona=z.id AND o.iddepto=d.id AND o.idlocalidad=l.id AND s.idmaterial=m.id AND s.idestadores=$RES_ST_CREADA $trayfilter $locationfilter".getAllSQLFilters().getSQLSort("s.create_date","DESC");

		$q = db_query($sql);
		$regCount = mysqli_num_rows($q);

		$maxPage = ceil($regCount/$rowsxPage);
		$rowFrom = (($pageNO-1) * $rowsxPage);
		$fields = array("o.numero"=>"Orden","eo.nombre"=>"Estado OT","ex.nombre"=>"EECC","z.nombre"=>"Zona","d.nombre"=>"Departamento","l.nombre"=>"Localidad","s.numero"=>"Reserva","s.fecha"=>"Fecha Reserva","m.codigo"=>"Codigo","m.item"=>"Material","s.tipo"=>"Tipo","s.cantidad"=>"Cantidad","o.pm_orden"=>"Orden PM","o.pm_reserva"=>"Reserva PM","e.nombre"=>"Estado Reserva");
		$hash = getRandomString();
		setReport($hash,"Reservas",$sql);
?>
<?php
$RETAL=1;
include_once "parts/res/frm.contabilizar.mas.inc.php" ?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Reserva de Materiales en Mi Bandeja</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<button type="button" onclick="returnFilter();">Buscar</button>
			<button type="button" onclick="clearFilter();">Limpiar</button>
			<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
		  <button type="button" onclick="openContabilizar()">Contabilizar</button>
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
					echo "<td><a href=\"?menu=$MENU_OT_SRC&amp;mode=show&amp;id=".encrypt(htmlspecialchars($row['idorden']))."\">".htmlspecialchars($row[orden])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[estadoot])."</td>\n";
					echo "<td>".htmlspecialchars($row[eecc])."</td>\n";
					echo "<td>".htmlspecialchars($row[zona])."</td>\n";
					echo "<td>".htmlspecialchars($row[depto])."</td>\n";
					echo "<td>".htmlspecialchars($row[localidad])."</td>\n";
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=view&amp;id=".htmlspecialchars($row[id])."\">".htmlspecialchars($row['numero'])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[creado])."</td>\n";
					echo "<td>".htmlspecialchars($row[codigo])."</td>\n";
					echo "<td>".htmlspecialchars($row[item])."</td>\n";
					echo "<td>".htmlspecialchars($row[tipo])."</td>\n";
					echo "<td style='text-align:right'>".number_format(htmlspecialchars($row['cantidad']),2)."</td>\n";
					echo "<td>".htmlspecialchars($row[pm_orden])."</td>\n";
					echo "<td>".htmlspecialchars($row[pm_reserva])."</td>\n";
					echo "<td>".htmlspecialchars($row[estado])."</td>\n";
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
