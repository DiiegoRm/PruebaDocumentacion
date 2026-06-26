<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'view':

	$id=getVal($_GET['id']);
	$r =  db_query("SELECT p.numero,p.idorden,p.idestadoped,p.create_date,p.fecha_programada,p.fecha_entrega,p.traslado,CONCAT(m.codigo,'|',m.item) material FROM `pedidosxorden` p, material m WHERE p.idmaterial=m.id AND p.`id` = '$id'");
	$row = mysqli_fetch_array($r);
	if (count($row)>0) {
		$numero = $row['numero'];
		$idestadoped = $row['idestadoped'];
		$estado = getNameById("estadoped",$idestadoped);
		$orden = $row['idorden'];
		$material = $row['material'];
		$fecha_programada = $row['fecha_programada'];
		$fecha_entrega = $row['fecha_entrega'];
		$traslado = $row['traslado'];

        $created = $row['create_date'];
        $modified = isset($row['modify_date'])?$row['modify_date']:'Nunca';
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Gestionar Pedido Material</h2></div>
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
						<li><a href="#tabs-1">Pedido</a></li>
						<li><a href="#tabs-2">Seguimiento</a></li>
					</ul>
					<div id="tabs-1">
						<table class="data-ro" id="ped-info">
							<tr>
								<td class="title">ID:</td>
								<td class="id"><?php echo htmlspecialchars($id)?>&nbsp;&nbsp;-&nbsp;[Creado: <?php echo htmlspecialchars($created)?>&nbsp;|&nbsp;Modificado: <?php echo htmlspecialchars($modified)?>]&nbsp;-</td>
							</tr>
							<tr>
								<td class="title">Numero Pedido:</td>
								<td class="field"><?php echo htmlspecialchars($numero); ?></td>
							</tr>
							<tr>
								<td class="title">Material:</td>
								<td class="field"><?php echo htmlspecialchars($material); ?></td>
							</tr>
							<tr>
								<td class="title">Fecha Programada:</td>
								<td class="field"><?php echo htmlspecialchars($fecha_programada); ?></td>
							</tr>
							<tr>
								<td class="title">Estado:</td>
								<td class="field"><?php echo htmlspecialchars($estado); ?></td>
							</tr>
							<tr>
								<td class="title">Fecha Entrega:</td>
								<td class="field"><?php echo htmlspecialchars($fecha_entrega); ?></td>
							</tr>
							<tr>
								<td class="title">Traslado:</td>
								<td class="field"><?php echo htmlspecialchars($traslado); ?></td>
							</tr>
						</table>
					</div>
					<div id="tabs-2">
						<?php include_once "parts/ped/tab.seguimiento.inc.php"; ?>
					</div>
				</div>
				<br class="clear"/>
				<div class="formbuttons">
					<?php
					include_once "parts/form.dummy.inc.php";
					include_once "parts/ped/frm.gestionar.inc.php";
					include_once "parts/ped/frm.entregar.inc.php";
					include_once "parts/ped/frm.cancelar.inc.php";
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

	$trayfilter = $appuser->getTrayFilter("p.id","idpedido","bandejasped");
	$locationfilter = $appuser->getLocationFilterOT("o.");
	$sql = "SELECT p.id,p.numero,p.idorden,o.numero orden,p.idestadoped,e.nombre estado,DATE_FORMAT(p.create_date,'%Y-%m-%d') create_date,p.fecha_programada,ex.nombre eecc,z.nombre zona, d.nombre depto, l.nombre localidad,m.codigo,m.item,m.unidad,p.traslado,cantidad,p.fecha_entrega,p.active,IF(e.nombre!='Entregado' AND e.nombre!='Cancelado',IF(CURRENT_DATE > p.fecha_programada,'rojo',IF(DATEDIFF(p.fecha_programada,CURRENT_DATE) <= 2,'amarillo','verde')),'') alerta,o.pm_orden FROM pedidosxorden p, ordenes o, estadoped e,material m,contratos c, eecc ex,zonas z,deptos d,localidades l WHERE p.idorden=o.id AND p.idestadoped=e.id AND p.idmaterial=m.id AND o.idcontrato=c.id AND c.ideecc=ex.id AND o.idzona=z.id AND o.iddepto=d.id AND o.idlocalidad=l.id $trayfilter $locationfilter".getAllSQLFilters().getSQLSort("p.create_date","DESC");
	$q = db_query($sql);
	$regCount = mysqli_num_rows($q);

	$maxPage = ceil($regCount/$rowsxPage);
	$rowFrom = (($pageNO-1) * $rowsxPage);
	$fields = array("o.numero"=>"Orden","p.numero"=>"Pedido","e.nombre"=>"Estado","p.create_date"=>"Fecha Pedido","p.fecha_programada"=>"Fecha Programada","ex.nombre"=>"EECC","z.nombre"=>"Zona","d.nombre"=>"Depto","l.nombre"=>"Localidad","m.codigo"=>"Codigo","m.item"=>"Material","m.unidad"=>"Und","p.cantidad"=>"Cantidad","p.traslado"=>"Traslado","p.fecha_entrega"=>"Fecha Entrega","o.pm_orden"=>"Orden PM");
	$hash = getRandomString();
	setReport($hash,"Pedidos",$sql);
?>
<?php
include_once "parts/ped/frm.gestionar.mas.inc.php";
include_once "parts/ped/frm.entregar.mas.inc.php";
include_once "parts/ped/frm.cancelar.mas.inc.php";
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Pedidos de Materiales en Mi Bandeja</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<button type="button" onclick="returnFilter();">Buscar</button>
			<button type="button" onclick="clearFilter();">Limpiar</button>
			<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
		  <button type="button" onclick="openGestionarPed()">Gestionar</button>
		  <button type="button" onclick="openEntregarPed()">Entregar</button>
		  <button type="button" onclick="openCancelarPed()">Cancelar</button>
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
					echo "<td ><input type=\"checkbox\" id='".htmlspecialchars($row[id])._.htmlspecialchars($row[idestadoped])."' class=\"checkbox\" name=\"chkLocID[]\" value=\"".htmlspecialchars($row[id])."\" onclick=\"unCheckMain();\" /></td>\n";
					echo "<td><a href=\"?menu=$MENU_OT_SRC&amp;mode=show&amp;id=".encrypt(htmlspecialchars($row['idorden']))."\">".htmlspecialchars($row[orden])."</a></td>\n";
					echo "<td><a href=\"?menu=".getMenu()."&amp;mode=view&amp;id=".htmlspecialchars($row[id])."\">".htmlspecialchars($row[numero])."</a></td>\n";
					echo "<td>".htmlspecialchars($row[estado])."</td>\n";
					echo "<td>".htmlspecialchars($row[create_date])."</td>\n";
					echo "<td class='".htmlspecialchars($row[alerta])."'>".htmlspecialchars($row[fecha_programada])."</td>\n";
					echo "<td>".htmlspecialchars($row[eecc])."</td>\n";
					echo "<td>".htmlspecialchars($row[zona])."</td>\n";
					echo "<td>".htmlspecialchars($row[depto])."</td>\n";
					echo "<td>".htmlspecialchars($row[localidad])."</td>\n";
					echo "<td>".htmlspecialchars($row[codigo])."</td>\n";
					echo "<td>".htmlspecialchars($row[item])."</td>\n";
					echo "<td>".htmlspecialchars($row[unidad])."</td>\n";
					echo "<td style='text-align:right'>".number_format(htmlspecialchars($row['cantidad']),2)."</td>\n";
					echo "<td>".htmlspecialchars($row[traslado])."</td>\n";
					echo "<td>".htmlspecialchars($row[fecha_entrega])."</td>\n";
					echo "<td>".htmlspecialchars($row[pm_orden])."</td>\n";
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
} // end switch
//------------------------------------------------------------------------------------------
?>
