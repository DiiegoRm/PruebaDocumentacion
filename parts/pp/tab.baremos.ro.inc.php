<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";
$id=decrypt(getVal($_GET['id'],"0"));
$ver=decrypt(getVal($_GET['ver'],"0"));
?>
<table id="tactbar" class="ui-widget ui-widget-content">
	<thead>
	<tr class="ui-widget-header">
		<th>#Item</th>
		<th style="width:370px;">Descripcion</th>
		<th>Unidad</th>
		<th>Puntos Baremo</th>
		<th>Materiales (CoP$)</th>
		<th>Cantidad</th>
		<th>SubTotal Baremos</th>
		<th>SubTotal Materiales (CoP$)</th>
	</tr>
	</thead>
	<tbody>
		<?php
			$tb = 0;
			$tm = 0;
			$datar = @db_query("SELECT b.id,b.item,b.descripcion,b.unidad,a.puntos,a.material,SUM(a.cantidad) cantidad,SUM(a.puntos*a.cantidad) sb,SUM(a.material*a.cantidad) sm
			FROM actividadesxpresupuesto a, baremo b
			WHERE a.idpresupuesto=$id AND a.idbaremo=b.id
			GROUP BY b.id,b.item,b.descripcion,b.unidad,a.puntos,a.material ORDER BY b.item");
			if (mysqli_num_rows($datar) != 0) {
				$i = 0;
				while($rowr = mysqli_fetch_array($datar)){
					$style = ($i++%2==0)?"odd":"even"; ?>
					<tr class='<?php echo $style; ?>'>
					<td><?php echo htmlspecialchars($rowr['item']); ?></td>
					<td><?php echo htmlspecialchars($rowr['descripcion']); ?></td>
					<td style="text-align:center"><?php echo htmlspecialchars($rowr['unidad']); ?></td>
					<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['puntos']),2); ?></td>
					<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['material']),2); ?></td>
					<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['cantidad']),3); ?></td>
					<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['sb']),2); ?></td>
					<td style="text-align:right">$<?php echo number_format(htmlspecialchars($rowr['sm']),2); ?></td>
					</tr>
				<?php
					$tb += $rowr['sb'];
					$tm += $rowr['sm'];
				}
			}?>
	</tbody>
	<tfoot>
	<tr class="ui-state-hover">
		<th colspan="6" style="text-align:right">SubTotal&nbsp;&nbsp;</th>
		<th style="text-align:right"><?php echo number_format($tb,2); ?></th>
		<th style="text-align:right">$<?php echo number_format($tm,2); ?></th>
	</tr>
	</tfoot>
</table>
