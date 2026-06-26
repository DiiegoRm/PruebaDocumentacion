<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";

$id=decrypt(getVal($_GET['id'],"0"));
?>
<table id="materiales-ro" class="ui-widget ui-widget-content" style="width: 100%">
	<thead>
		<tr class="ui-widget-header ">
			<th>Codigo</th>
			<th style="width:250px;">Descripcion</th>
			<th>Tipo</th>
			<th>Unidad</th>
			<th>Costo (CoP$)</th>
			<th>Cantidad</th>
			<th>SubTotal(CoP$)</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$total = 0;
		$dataq = @db_query("SELECT ma.id,ma.codigo,ma.item,ma.tipo,m1.idbaremo,m1.idmaterial,m1.factor,m1.unidad,m1.valor,m1.cantidad-IFNULL(m2.cantidad,0) cantidad FROM (
												SELECT idorden,version,idbaremo,idmaterial,factor,unidad,valor,SUM(movistar) cantidad FROM materialesxorden WHERE movistar>0 AND idorden=$id AND version=$OT_VER_EJECUCION GROUP BY idorden,version,idbaremo,idmaterial,factor,unidad,valor
												) m1 LEFT JOIN  (
												SELECT idbaremo,idmaterial,SUM(movistar) cantidad FROM materialesxorden WHERE movistar>0 AND idorden=$id AND version>$OT_VER_EJECUCION AND version NOT IN(SELECT version FROM liquidaciones WHERE idorden=$id AND idestadoliq IN ($LIQ_ST_RECHAZADA,$LIQ_ST_CANCELADA)) GROUP BY idbaremo,idmaterial
												) m2
												ON (m1.idbaremo=m2.idbaremo AND m1.idmaterial=m2.idmaterial), material ma WHERE ma.id=m1.idmaterial AND ABS(m1.cantidad-IFNULL(m2.cantidad,0)) >0");
		if (mysqli_num_rows($dataq) != 0) {
			while($rowq = mysqli_fetch_array($dataq)){
				$total += ($rowq['cantidad']*$rowq['valor']);?>
				<tr>
				<td><?php echo htmlspecialchars($rowq['codigo']); ?></td>
				<td><?php echo htmlspecialchars($rowq['item']); ?></td>
				<td><?php echo htmlspecialchars($rowq['tipo']); ?></td>
				<td style="text-align:center"><?php echo htmlspecialchars($rowq['unidad']); ?></td>
				<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowq['valor']),2); ?></td>
				<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowq['cantidad']),2); ?></td>
				<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowq['cantidad']*$rowq['valor']),2); ?></td>
				</tr>
			<?php
			}
		}
	?>
	</tbody>
	<tfoot>
	<tr class="ui-state-hover">
		<th colspan="6" style="text-align:right">TOTAL&nbsp;&nbsp;</th>
		<th style="text-align:right"><?php echo number_format(htmlspecialchars($total),2); ?></th>
		<th></th>
	</tr>
	</tfoot>
</table>
