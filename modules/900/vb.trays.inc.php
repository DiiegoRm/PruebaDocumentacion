<?php
ob_start();
	$sort=getVal($_GET['sort'],"0");
	$order=getVal($_GET['order'],"null");
	$pageNO=getVal($_POST['pageNO'],"1");
	$rowsxPage=100;
	$locationfilter = $appuser->getLocationFilterVB("v.");
	$sql = "SELECT v.id,v.numero,v.nombre,v.active,e.nombre estado, t.nombre tipo,GROUP_CONCAT(g.nombre SEPARATOR ',') bandejas FROM viabilidades v LEFT JOIN bandejasvb b ON b.idviabilidad=v.id,estadovb e,tipovb t,grupos g WHERE idestadovb NOT IN ($VB_ST_CREACION,$VB_ST_CANCELADA,$VB_ST_TERMINADA) $locationfilter AND v.idestadovb=e.id AND v.idtipovb=t.id AND b.idgrupo=g.id".getAllSQLFilters()." GROUP BY v.id".getSQLSort("v.fecha_solicitud","DESC");
	$q = db_query($sql);
	$regCount = mysqli_num_rows($q);

	$maxPage = ceil($regCount/$rowsxPage);
	$rowFrom = (($pageNO-1) * $rowsxPage);
	$fields = array("numero"=>"Numero","t.nombre"=>"Requerimiento","v.nombre"=>"Nombre","e.nombre"=>"Estado","g.nombre"=>"Bandeja","v.active"=>"Activo");
?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Viabiliades::Bandejas</h2></div>
		<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;sort=<?php echo htmlspecialchars($sort);?>&amp;order=<?php echo htmlspecialchars($order);?>">
		<input type="hidden" name="captureState" value="" />
		<input type="hidden" name="delState" value="" />
		<input type="hidden" name="pageNO" value="<?php echo htmlspecialchars($pageNO);?>" />

		<div class="searchbox">
		</div>

		<div class="actionbar">
			<div class="actionbuttons">
				<button type="button" onclick="returnFilter();">Buscar</button>
				<button type="button" onclick="clearFilter();">Limpiar</button>
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
					if($row['active']=='Si'){
						echo "<td><a href=\"?menu=$MENU_SRCH_VB&amp;mode=view&amp;id=".encrypt(htmlspecialchars($row['id']))."\">".htmlspecialchars($row[numero])."</a></td>\n";
					} else {
						echo "<td>".htmlspecialchars($row[numero])."</td>\n";
					}
					echo "<td>".htmlspecialchars($row[tipo])."</td>\n";
					echo "<td>".htmlspecialchars($row[nombre])."</td>\n";
					echo "<td>".htmlspecialchars($row[estado])."</td>\n";
					echo "<td>".htmlspecialchars($row[bandejas])."</td>\n";
					echo "<td>".htmlspecialchars($row[active])."</td>\n";
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
//------------------------------------------------------------------------------------------
?>
