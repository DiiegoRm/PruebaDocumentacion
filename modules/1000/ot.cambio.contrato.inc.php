<?php
ob_start();

switch($_REQUEST["mode"]){
 case 'save':

	$id=getVal($_POST['txtId']);
	$txtContrato=getVal($_POST['txtContrato'],"null");
	$txtZona=getVal($_POST['txtZona'],"null");
	if(hasVal($txtContrato)||hasVal($txtZona)){
		$sql = "UPDATE `ordenes` SET idcontrato=$txtContrato,idzona=$txtZona WHERE id=$id";
		$sql_update = db_query($sql);
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
		printMessage("No ha completado los campos obligatorios...","error");
	}
 break;
 case 'update':
	$id=decrypt(getVal($_GET['id'],"0"));
	$r =  db_query("SELECT o.numero,o.idzona,o.idcontrato FROM ordenes o where o.id=$id");
  $rot = mysqli_fetch_array($r);
	if (count($rot)>0) {
		$numero = $rot['numero'];
		$idzona = $rot['idzona'];
		$idcontrato = $rot['idcontrato'];
 ?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Cambiar Responsables a [<?php echo htmlspecialchars($numero)?>] </h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<table class="data-ro" id="tables-all" style="width: 60%">
					<tr>
						<td class="title">Orden:</td>
						<td class="id">
							<?php echo htmlspecialchars($numero)?>
							<?php echo getInputHidden('txtId',htmlspecialchars($id))?>
						</td>
					</tr>
					<tr>
				<td class="title"><span class="required">*</span>Contrato:</td>
				<td class="input">
					<?php echo getComboBox("SELECT c.id,CONCAT (c.numero,' | ',e.nombre) nombre,'Si' active FROM contratos c, eecc e WHERE c.ideecc=e.id ","txtContrato",htmlspecialchars($idcontrato)); ?>
				</td>
					</tr>
					<tr>
				<td class="title"><span class="required">*</span>Zona:</td>
				<td class="input">
					<?php echo getComboBox("SELECT id,nombre,'Si' active FROM zonas ","txtZona",htmlspecialchars($idzona)); ?>
				</td>
					</tr>
				</table>
				<br class="clear"/>
				<div class="formbuttons">
					<button type="submit">Guardar</button>
					<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>
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
	$locationfilter = $appuser->getAllFilterOT("o.");
	$sql = "SELECT o.id,o.numero,o.fecha_solicitud,o.fecha_requerida,o.nombre,o.active,eo.nombre estado,tot.nombre req,ee.nombre eecc,z.nombre zona, d.nombre depto,l.nombre localidad,tr.nombre red,cp.nombre proyecto,tp.tmo,tp.tma,IF(o.idestadoot NOT IN($OT_ST_CANCELADA,$OT_ST_TERMINADA,$OT_ST_CERRADA),IF(CURRENT_DATE > o.fecha_requerida,'rojo',IF(DATEDIFF(o.fecha_requerida,CURRENT_DATE) <= 2,'amarillo','verde')),'') alerta FROM ordenes o LEFT JOIN eecc ee ON o.ideecc=ee.id LEFT JOIN zonas z ON o.idzona=z.id LEFT JOIN deptos d ON o.iddepto=d.id LEFT JOIN localidades l ON o.idlocalidad=l.id LEFT JOIN tipored tr ON o.idtipored=tr.id LEFT JOIN claseproyecto cp ON o.idclaseproyecto=cp.id LEFT JOIN totalesxorden tp ON (tp.idorden=o.id AND tp.version=$OT_VER_GENERADA),tipoot tot,estadoot eo WHERE o.idtipoot=tot.id AND o.idestadoot > $OT_ST_ENCREACION AND (o.idcontrato IS NULL OR o.idzona IS NULL) AND o.idestadoot=eo.id $locationfilter".getAllSQLFilters().getSQLSort("o.create_date","DESC");
	$q = db_query($sql);
	$regCount = mysqli_num_rows($q);

	$maxPage = ceil($regCount/$rowsxPage);
	$rowFrom = (($pageNO-1) * $rowsxPage);
	$fields = array("o.numero"=>"Numero","o.fecha_solicitud"=>"Solicitada","o.fecha_requerida"=>"Requerida","eo.nombre"=>"Estado","ee.nombre"=>"EECC","z.nombre"=>"Zona","d.nombre"=>"Depto","l.nombre"=>"Localidad","tot.nombre"=>"Tipo","o.nombre"=>"Nombre","tr.nombre"=>"TipoRed","cp.nombre"=>"Proyecto","tp.tmo"=>"Total MO","tp.tma"=>" Total MA");
	$hash = getRandomString();
	setReport($hash,"Ordenes",$sql);
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Cambio de Contrato/Zona en Ordenes</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<button type="button" onclick="returnFilter();">Buscar</button>
			<button type="button" onclick="clearFilter();">Limpiar</button>
			<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
			<?php echo date('Y-m-d H:i:s') ?>
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
					if($row['idesatdoot']!=$OT_ST_CERRADA){
						echo "<td><a href=\"?menu=".getMenu()."&amp;mode=update&amp;id=".encrypt(htmlspecialchars($row['id']))."\">".htmlspecialchars($row[numero])."</a></td>\n";
					} else{
						echo "<td>".htmlspecialchars($row[numero])."</td>\n";
					}
					echo "<td>".htmlspecialchars($row[fecha_solicitud])."</td>\n";
					echo "<td class='".htmlspecialchars($row[alerta])."'>".htmlspecialchars($row[fecha_requerida])."</td>\n";
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
} // end switch
//------------------------------------------------------------------------------------------
?>
