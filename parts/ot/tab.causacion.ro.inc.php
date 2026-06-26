<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";
include_once "../../includes/user.class.inc.php";

$id=decrypt(getVal($_GET['id'],"0"));
$appuser = getAppUser();
?>
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
		</tr>
	</thead>
	<tbody>
	<?php
		$totalba = 0;
		$totalmo = 0;
		$totalma = 0;
		$total = 0;
		$dataq = @db_query("SELECT l.fecha_causacion,l.fecha_liquidacion,l.tipo,l.numero,l.totalba,l.totalmo,l.totalma,e.nombre estado,l.idestadoliq FROM liquidaciones l, estadoliq e WHERE l.idestadoliq=e.id AND l.idorden=$id");
		if (mysqli_num_rows($dataq) != 0) {
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
				<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowq['totalba']),2); ?></td>
				<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowq['totalmo']),2); ?></td>
				<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowq['totalma']),2); ?></td>
				<td style="text-align:right"><?php echo number_format(htmlspecialchars($subtotal),2); ?></td>
				</tr>
			<?php
			}
		}
	?>
	</tbody>
	<tfoot>
	<tr class="ui-state-hover">
		<th colspan="5" style="text-align:right">TOTAL CAUSADO</th>
		<th style="text-align:right"><?php echo number_format(htmlspecialchars($totalba),2); ?></th>
		<th style="text-align:right"><?php echo number_format(htmlspecialchars($totalmo),2); ?></th>
		<th style="text-align:right"><?php echo number_format(htmlspecialchars($totalma),2); ?></th>
		<th style="text-align:right"><?php echo number_format(htmlspecialchars($totalmo)+htmlspecialchars($totalma),2); ?></th>
	</tr>
	</tfoot>
</table>
