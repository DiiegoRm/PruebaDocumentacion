<?php include_once 'ot.liquidacion.inc.php'; ?>
<?php include_once 'ot.liquidacion.ro.inc.php'; ?>
<style type="text/css">
    #toolbar {
    padding: 10px 4px;
		margin: 0 auto;
    }
</style>
<?php /*echo "liqTotal=$liqTotal;estadoliq=$estadoliq;hcomplete=$hcomplete;hasLiq=$hasLiq;"*/ ?>
<script type="text/javascript">
$(function() {
	$( "#toolbar" ).buttonset();
	$( "#liqparcial" ).button({text: true,icons: {primary: "ui-icon-newwin"}<?php if($liqTotal>0||($estadoliq > 0 && $estadoliq<$LIQ_ST_APROBADA)){ echo ",disabled: true";}?>}).click(function( event ) {
		event.preventDefault();
		openLiquidacion("PARCIAL",<?php echo $id; ?>);
	});
	$( "#liqtotal" ).button({text: true,icons: {primary: "ui-icon-newwin"}<?php if($hcomplete>0||$liqTotal>0||!$appuser->isInState($idestadoot,"$OT_ST_TERMINADA,$OT_ST_REGISTRADA")||($estadoliq > 0 && $estadoliq<$LIQ_ST_APROBADA)){ echo ",disabled: true";}?>}).click(function( event ) {
		event.preventDefault();
		openLiquidacion("TOTAL",<?php echo $id; ?>);
	});
});
</script>
<div class="actionbar">
	<div class="noresultsbar">
		<?php echo $hcomplete>0&&$liqTotal==0?"<span id='message' class='error'>Solo puede realizar Liquidaciones Parciales debido a que Faltan Aprobaciones a Solicitudes H!</span>":""?>
	</div>
</div>
<?php if($appuser->isInRole($LIQUIDAR_OT)){ ?>
	<br class="clear"/>
	<br class="clear"/>
	<span id="toolbar" class="ui-widget-header ui-corner-all">
		<?php
$sqlL=@db_query("SELECT * FROM boton_liq");
$rowL=mysqli_fetch_array($sqlL);
if($rowL['active']=='SI'){?>
  <button id="liqparcial">Liquidacion Parcial</button>
  <button id="liqtotal">Liquidacion Total</button>
  <?php
}else {?>
  <span>En Cierres</span>
<?php
}
 ?>	</span>
	<br class="clear"/>
	<br class="clear"/>
<?php } ?>
<table id="causacion" class="ui-widget ui-widget-content" style="width: 100%">
	<thead>
		<tr class="ui-widget-header ">
			<th>Fecha Causacion</th>
			<th>Fecha Liquidacion</th>
			<th>Tipo Liquidacion</th>
			<th>Numero Liquidacion</th>
			<th>Estado</th>
			<th>Total Baremos</th>
			<th>Mano de Obra $</th>
			<th>Materiales $</th>
			<th>Total $</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$totalba = 0;
		$totalmo = 0;
		$totalma = 0;
		$total = 0;
		$dataq = @db_query("SELECT l.id,l.version,l.fecha_causacion,l.fecha_liquidacion,l.tipo,l.numero,l.totalba,l.totalmo,l.totalma,e.nombre estado,l.idestadoliq FROM liquidaciones l, estadoliq e WHERE l.idestadoliq=e.id AND l.idorden=$id");
		if (mysqli_num_rows($dataq) > 0) {
			while($rowq = mysqli_fetch_array($dataq)){
				$subtotal = 0;
				if($appuser->isInState($rowq['idestadoliq'],$LIQ_ST_ACTIVAS)){
					$totalba += $rowq['totalba'];
					$totalmo += $rowq['totalmo'];
					$totalma += $rowq['totalma'];
					$subtotal = $totalmo + $totalma;
					$total += $subtotal;
				}
				?>
				<tr>
				<td><?php echo htmlspecialchars($rowq['fecha_causacion']); ?></td>
				<td><?php echo htmlspecialchars($rowq['fecha_liquidacion']); ?></td>
				<td><?php echo htmlspecialchars($rowq['tipo']); ?></td>
				<td><?php echo htmlspecialchars($rowq['numero']); ?></td>
				<td><?php echo htmlspecialchars($rowq['estado']); ?></td>
				<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowq['totalba'],2)); ?></td>
				<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowq['totalmo'],2)); ?></td>
				<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowq['totalma'],2)); ?></td>
				<td style="text-align:right"><?php echo htmlspecialchars(number_format($subtotal,2)); ?></td>
				<td><span class="ui-icon ui-icon-extlink" onclick="openLiquidacionRO('<?php echo encrypt($rowq['id'])?>','<?php echo encrypt($id)?>','<?php echo encrypt($rowq['version']); ?>')"></span></td>
				</tr>
			<?php
			}
		}
	?>
	</tbody>
	<tfoot>
	<tr class="ui-state-hover">
		<th colspan="5" style="text-align:right">TOTAL CAUSADO</th>
		<th style="text-align:right"><?php echo htmlspecialchars(number_format($totalba,2)); ?></th>
		<th style="text-align:right"><?php echo htmlspecialchars(number_format($totalmo,2)); ?></th>
		<th style="text-align:right"><?php echo htmlspecialchars(number_format($totalma,2)); ?></th>
		<th style="text-align:right"><?php echo htmlspecialchars(number_format($totalmo+$totalma,2)); ?></th>
		<th>&nbsp;</th>
	</tr>
	</tfoot>
</table>
