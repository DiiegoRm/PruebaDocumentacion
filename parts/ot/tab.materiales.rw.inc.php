<?php
//include_once "includes/global.php";
//include_once "includes/database.php";
include_once 'ot.reservas.inc.php';
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
			<th>Reservas</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$total = 0;
		$dataq = @db_query("SELECT ma.id,ma.codigo,ma.item,ma.tipo,mo.unidad,mo.valor,SUM(mo.movistar) cantidad FROM materialesxorden mo, material ma WHERE mo.movistar>0 AND mo.version=$OT_VER_GENERADA AND mo.idmaterial>0 AND mo.idmaterial=ma.id AND idorden=$id GROUP BY ma.id");
		if (mysqli_num_rows($dataq) != 0) {
			$i = 0;
			while($rowq = mysqli_fetch_array($dataq)){
				$total += ($rowq['cantidad']*$rowq['valor']);
				$style = ($i++%2==0)?"odd":"even"; ?>
				<tr class='<?php echo $style; ?>'>
				<td><?php echo htmlspecialchars($rowq['codigo']); ?></td>
				<td><?php echo htmlspecialchars($rowq['item']); ?></td>
				<td><?php echo htmlspecialchars($rowq['tipo']); ?></td>
				<td style="text-align:center"><?php echo htmlspecialchars($rowq['unidad']); ?></td>
				<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowq['valor'],2)); ?></td>
				<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowq['cantidad'],2)); ?></td>
				<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowq['cantidad']*$rowq['valor'],2)); ?></td>
				<td><span class="ui-icon ui-icon-extlink" onclick="openReserva(<?php echo htmlspecialchars($rowq['id']); ?>,<?php echo htmlspecialchars($id); ?>)"></span></td>
				</tr>
			<?php
			}
		}
	?>
	</tbody>
	<tfoot>
	<tr class="ui-state-hover">
		<th colspan="6" style="text-align:right">TOTAL&nbsp;&nbsp;</th>
		<th style="text-align:right"><?php echo htmlspecialchars(number_format($total,2)); ?></th>
		<th></th>
	</tr>
	</tfoot>
</table>
