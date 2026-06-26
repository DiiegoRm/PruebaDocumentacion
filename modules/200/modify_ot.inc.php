<!--formulario para busqueda de ordenes-->
<?php
if ($_POST["enviado"]=='Boton'){
  $variable="";
} else {
  $variable=" AND o.id='-1'";
}

$sort=getVal($_GET['sort'],"0");
$order=getVal($_GET['order'],"null");
$pageNO=getVal($_POST['pageNO'],"1");
$pageNO=mysqli_real_escape_string($dbsgp,$pageNO);//KIUWAN
$rowsxPage=100;
$locationfilter = $appuser->getAllFilterOT("o.");
$sql = "SELECT o.id,o.numero,o.fecha_solicitud,o.fecha_requerida,o.nombre,o.active,eo.nombre estado,tot.nombre req,ee.nombre eecc,
z.nombre zona, d.nombre depto,l.nombre localidad,tr.nombre red,cp.nombre proyecto,u.nombre solicitante,tp.tmo,tp.tma,IF(o.idestadoot NOT IN($OT_ST_CANCELADA,$OT_ST_TERMINADA,$OT_ST_CERRADA,$OT_ST_REGISTRADA),IF(CURRENT_DATE > o.fecha_requerida,'rojo',IF(DATEDIFF(o.fecha_requerida,CURRENT_DATE) <= 2,'amarillo','verde')),'') alerta,o.pm_orden,o.pm_solped, o.trs, o.pm_reserva,o.hh_pasados,  UNIX_TIMESTAMP(CURRENT_TIMESTAMP)-IFNULL(UNIX_TIMESTAMP(o.modify_date),UNIX_TIMESTAMP(o.create_date)) secs
FROM ordenes o LEFT JOIN eecc ee ON o.ideecc=ee.id LEFT JOIN zonas z ON o.idzona=z.id LEFT JOIN deptos d ON o.iddepto=d.id LEFT JOIN localidades l ON o.idlocalidad=l.id LEFT JOIN tipored tr ON o.idtipored=tr.id LEFT JOIN claseproyecto cp ON o.idclaseproyecto=cp.id LEFT JOIN totalesxorden tp ON (tp.idorden=o.id AND tp.version=$OT_VER_GENERADA) LEFT JOIN usuarios u ON (o.create_user=u.id),tipoot tot,estadoot eo
WHERE o.idtipoot=tot.id AND o.fecha_solicitud>='2017-03-01' AND o.idestadoot=eo.id $locationfilter".getAllSQLFilters().getSQLSort("o.create_date","DESC");
$q = db_query($sql);
$regCount = mysqli_num_rows($q);

$maxPage = ceil($regCount/$rowsxPage);
$rowFrom = (($pageNO-1) * $rowsxPage);
$fields = array("o.numero"=>"Numero","o.fecha_solicitud"=>"Solicitada","o.fecha_requerida"=>"Requerida","eo.nombre"=>"Estado","o.modify_date"=>"Tiempo","ee.nombre"=>"EECC","z.nombre"=>"Zona","d.nombre"=>"Depto","l.nombre"=>"Localidad","tot.nombre"=>"Tipo","o.nombre"=>"Nombre","tr.nombre"=>"TipoRed","cp.nombre"=>"Proyecto","u.nombre"=>"Solicitante","tp.tmo"=>"Total MO","tp.tma"=>" Total MA","o.pm_orden"=>"Orden PM","o.pm_solped"=>"PM_Solped", "o.trs"=>"Trs","o.hh_pasados"=>"Hogares Pasados");
$hash = getRandomString();
setReport($hash,"Ordenes",$sql);
?>

<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Ordenes</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo htmlspecialchars($sort);?>&amp;order=<?php echo htmlspecialchars($order);?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="enviado" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo htmlspecialchars($pageNO);?>" />

		<div class="searchbox">
			<button type="button" onclick="returnFilterLoad();">Buscar</button>
			<button type="button" onclick="clearFilter();">Limpiar</button>
			<button type="button" onclick="exportXLS('<?php echo htmlspecialchars($hash); ?>');">Exportarr</button>
		</div>

		<div class="actionbar">
			<div class="actionbuttons">
			</div>
			<div class="noresultsbar"><?php echo htmlspecialchars($regCount==0?"No hay registros para mostrar!":"")?></div>
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
						echo "<td><a href=\"?menu=204&amp;mode=edit&amp;id=".encrypt(htmlspecialchars($row['id']))."\">".htmlspecialchars($row[numero])."</a></td>\n";
					} else{
						echo "<td>".htmlspecialchars($row[numero])."</td>\n";
					}
					echo "<td>".htmlspecialchars($row[fecha_solicitud])."</td>\n";
					echo "<td class='".htmlspecialchars($row[alerta])."'>".htmlspecialchars($row[fecha_requerida])."</td>\n";
					echo "<td>".htmlspecialchars($row[estado])."</td>\n";
					echo "<td>".formatSeconds(htmlspecialchars($row['secs']))."</td>\n";
					echo "<td>".htmlspecialchars($row[eecc])."</td>\n";
					echo "<td>".htmlspecialchars($row[zona])."</td>\n";
					echo "<td>".htmlspecialchars($row[depto])."</td>\n";
					echo "<td>".htmlspecialchars($row[localidad])."</td>\n";
					echo "<td>".htmlspecialchars($row[req])."</td>\n";
					echo "<td>".htmlspecialchars($row[nombre])."</td>\n";
					echo "<td>".htmlspecialchars($row[red])."</td>\n";
					echo "<td>".htmlspecialchars($row[proyecto])."</td>\n";
					echo "<td>".htmlspecialchars($row[solicitante])."</td>\n";
					echo "<td style='text-align:right'>$".number_format(htmlspecialchars($row['tmo'],2))."</td>\n";
					echo "<td style='text-align:right'>$".number_format(htmlspecialchars($row['tma'],2))."</td>\n";
					echo "<td>".htmlspecialchars($row[pm_orden])."</td>\n";
					echo "<td>".htmlspecialchars($row[pm_solped])."</td>\n";

echo "<td>".htmlspecialchars($row[Trs])."</td>\n";
echo "<td>".htmlspecialchars($row[idcluster])."</td>\n";
echo "<td>".htmlspecialchars($row[idsubcluster])."</td>\n";
echo "<td>".htmlspecialchars($row[hh_pasados])."</td>\n";
echo "<td>".htmlspecialchars($row[idmes])."</td>\n";



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
<!--formulario para busqueda de presupuestos-->

<!--formulario para busqueda de viabilidades-->
