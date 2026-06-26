<?php

//include_once "includes/global.php";
//include_once "includes/database.php";
if(!$mat_rx){
 include_once 'ot.reservas.inc.php';
}
?>
<table id="materiales-ro" class="ui-widget ui-widget-content" style="width: 100%">
	<thead>
		<tr class="ui-widget-header ">
			<th>Codigo</th>
			<th style="width:250px;">Descripcion</th>
			<th>Tipo</th>
			<th>Unidad</th>
			<th>Costo (CoP$)</th>
			<th>Cantidad Generada</th>
			<th>SubTotal(CoP$) Generado</th>
			<th>Cantidad Ejecutado</th>
			<th>SubTotal(CoP$) Ejecutado</th>
			<th>Ejecutado</th>
			<th>Reservas</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$total = 0;
		$total2 = 0;
		$sql="SELECT ma.codigo,ma.item,ma.tipo,ma.unidad,ma.valor,mo.idmaterial,IFNULL(mo.cantidad1,0) cantidad1,IFNULL(mo.cantidad2,0) cantidad2 FROM (
    SELECT m1.idmaterial,m1.cantidad1,m2.cantidad2 FROM (
        SELECT idmaterial,SUM(movistar) cantidad1 FROM materialesxorden WHERE movistar>0 AND idmaterial>0 AND idorden=$id and version=$OT_VER_GENERADA GROUP BY idmaterial
    ) m1
    LEFT JOIN (
        SELECT idmaterial,SUM(movistar) cantidad2 FROM materialesxorden WHERE movistar>0 AND idmaterial>0 AND idorden=$id and version=$OT_VER_EJECUCION GROUP BY idmaterial
    ) m2
    ON (m1.idmaterial=m2.idmaterial)
    UNION
    SELECT m2.idmaterial,m1.cantidad1,m2.cantidad2 FROM (
        SELECT idmaterial,SUM(movistar) cantidad2 FROM materialesxorden WHERE movistar>0 AND idmaterial>0 AND idorden=$id and version=$OT_VER_EJECUCION GROUP BY idmaterial
    ) m2
    LEFT JOIN (
        SELECT idmaterial,SUM(movistar) cantidad1 FROM materialesxorden WHERE movistar>0 AND idmaterial>0 AND idorden=$id and version=$OT_VER_GENERADA GROUP BY idmaterial
    ) m1
    ON (m2.idmaterial=m1.idmaterial)
    WHERE m1.idmaterial IS NULL
)mo, material ma
WHERE mo.idmaterial=ma.id";
		$dataq = @db_query($sql);
		if (mysqli_num_rows($dataq) != 0) {
			$i = 0;
			while($rowq = mysqli_fetch_array($dataq)){
				$total += ($rowq['cantidad1']*$rowq['valor']);
				$total2 += ($rowq['cantidad2']*$rowq['valor']);
				$style = ($i++%2==0)?"odd":"even"; ?>
				<tr class='<?php echo $style; ?>'>
				<td><?php echo htmlspecialchars($rowq['codigo']); ?></td>
				<td><?php echo htmlspecialchars($rowq['item']); ?></td>
				<td><?php echo htmlspecialchars($rowq['tipo']); ?></td>
				<td style="text-align:center"><?php echo htmlspecialchars($rowq['unidad']); ?></td>
				<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowq['valor'],2)); ?></td>
				<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowq['cantidad1'],2)); ?></td>
				<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowq['cantidad1']*$rowq['valor'],2)); ?></td>
				<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowq['cantidad2'],2)); ?></td>
				<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowq['cantidad2']*$rowq['valor'],2)); ?></td>
				<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowq['cantidad1']>0?$rowq['cantidad2']/$rowq['cantidad1']*100:0,2)); ?>%</td>
				<td><?php if(!$mat_rx&&$rowq['cantidad2']>0){ ?><span class="ui-icon ui-icon-extlink" onclick="openReserva(<?php echo $rowq['idmaterial']; ?>,<?php echo $id; ?>)"></span><?php } ?></td>
				</tr>
			<?php
			}
		}
	?>
	</tbody>
	<tfoot>
	<tr class="ui-state-hover">
		<th colspan="6" style="text-align:right">TOTAL&nbsp;&nbsp;</th>
		<th style="text-align:right"><?php echo number_format($total,2); ?></th>
		<th></th>
		<th style="text-align:right"><?php echo number_format($total2,2); ?></th>
		<td style="text-align:right"><?php echo number_format($total>0?$total2/$total*100:0,2); ?>%</td>
		<th></th>
	</tr>
	</tfoot>
</table>
