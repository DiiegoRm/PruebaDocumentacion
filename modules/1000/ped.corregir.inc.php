<?php
ob_start();

switch($_REQUEST["mode"]){
default:
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");//
	$pageNO=mysqli_real_escape_string($dbsgp,$pageNO);//KIUWAN
	$rowsxPage=100;
	if($_POST['delState']){
		$del = $_POST['chkLocID'];//
		$del=mysqli_real_escape_string($dbsgp,$del);//KIUWAN
		$n = count($del);
		for ($i=0; $i < $n; $i++){
			switch($_POST['delState']){
				case 'CorrectMode':
					$sql_update = db_query("INSERT INTO bandejasped(idpedido, idgrupo) VALUES({$del[$i]},$GRP_INGENIERIA),({$del[$i]},$GRP_GESTOR_OTS),({$del[$i]},$GRP_SOPORTE_TECNICO)");
					break;
			}
		}
		printMessage("Actualizando base de datos, por favor espere..","ok");
	}
	else {
	$locationfilter = $appuser->getLocationFilterOT("o.");
	$sql = "SELECT p.id,p.numero,p.idorden,o.numero orden,e.nombre estado,DATE_FORMAT(p.create_date,'%Y-%m-%d') create_date,p.fecha_programada,ex.nombre eecc,z.nombre zona, d.nombre depto, l.nombre localidad,m.codigo,m.item,m.unidad,p.traslado,cantidad,p.fecha_entrega,p.active,IF(e.nombre!='Entregado' AND e.nombre!='Cancelado',IF(CURRENT_DATE > p.fecha_programada,'rojo',IF(DATEDIFF(p.fecha_programada,CURRENT_DATE) <= 2,'amarillo','verde')),'') alerta,o.pm_orden FROM pedidosxorden p, ordenes o, estadoped e,material m,contratos c, eecc ex,zonas z,deptos d,localidades l WHERE p.idestadoped<3 AND p.id NOT IN ( SELECT idpedido FROM bandejasped) AND p.idorden=o.id AND p.idestadoped=e.id AND p.idmaterial=m.id AND o.idcontrato=c.id AND c.ideecc=ex.id AND o.idzona=z.id AND o.iddepto=d.id AND o.idlocalidad=l.id $locationfilter".getAllSQLFilters().getSQLSort("p.create_date","DESC");
	$q = db_query($sql);
	$regCount = mysqli_num_rows($q);

	$maxPage = ceil($regCount/$rowsxPage);
	$rowFrom = (($pageNO-1) * $rowsxPage);
	$fields = array("o.numero"=>"Orden","p.numero"=>"Pedido","e.nombre"=>"Estado","p.create_date"=>"Fecha Pedido","p.fecha_programada"=>"Fecha Programada","ex.nombre"=>"EECC","z.nombre"=>"Zona","d.nombre"=>"Depto","l.nombre"=>"Localidad","m.codigo"=>"Codigo","m.item"=>"Material","m.unidad"=>"Und","p.cantidad"=>"Cantidad");
	$hash = getRandomString();
	setReport($hash,"Pedidos",$sql);
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Pedidos Sin Bandeja</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo $sort;?>&amp;order=<?php echo $order;?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo $pageNO;?>" />

		<div class="searchbox">
			<button type="button" onclick="returnCorregir();">Corregir</button>
			<button type="button" onclick="returnFilter();">Buscar</button>
			<button type="button" onclick="clearFilter();">Limpiar</button>
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
					echo "<td>".htmlspecialchars($row[orden])."</td>\n";
					echo "<td>".htmlspecialchars($row[numero])."</td>\n";
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
				echo "</tr>\n";
				}
?>
			</tbody>
		</table>
	</form>
</div>
</div>
</div>
<?php
	}
} // end switch
//------------------------------------------------------------------------------------------
?>
