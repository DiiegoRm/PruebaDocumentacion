<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";
$id=decrypt(getVal($_GET['id'],"0"));
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
			$sql = "SELECT b.id,b.item,b.descripcion,b.unidad,a1.puntos,a1.material,a1.cantidad-IFNULL(a2.cantidad,0) cantidad,a1.puntos*(a1.cantidad-IFNULL(a2.cantidad,0)) sb,a1.material*(a1.cantidad-IFNULL(a2.cantidad,0)) sm FROM (
								SELECT idorden,version,idbaremo,puntos,material,SUM(cantidad) cantidad FROM actividadesxorden WHERE idorden=$id AND version=$OT_VER_EJECUCION GROUP BY idorden,version,idbaremo,puntos,material
								) a1 LEFT JOIN  (
								SELECT idbaremo,puntos,material,SUM(cantidad) cantidad FROM actividadesxorden WHERE idorden=$id AND version>$OT_VER_EJECUCION AND version NOT IN(SELECT version FROM liquidaciones WHERE idorden=$id AND idestadoliq IN ($LIQ_ST_RECHAZADA,$LIQ_ST_CANCELADA)) GROUP BY idbaremo,puntos,material
								) a2
								ON (a1.idbaremo=a2.idbaremo), baremo b WHERE a1.idbaremo=b.id AND ABS(a1.cantidad-IFNULL(a2.cantidad,0)) > 0";
			$datar = @db_query($sql);
//SELECT b.id,b.item,b.descripcion,b.unidad,a.puntos,a.material,a.cantidad,a.puntos*a.cantidad sb,a.material*a.cantidad sm FROM actividadesxorden a, baremo b WHERE a.idorden=$id AND a.version=$ver AND a.idbaremo=b.id ORDER BY b.idclase,b.id");
			if (mysqli_num_rows($datar) != 0) {
				while($rowr = mysqli_fetch_array($datar)){?>
					<tr>
					<td><?php echo htmlspecialchars($rowr['item']); ?></td>
					<td><?php echo htmlspecialchars($rowr['descripcion']); ?></td>
					<td style="text-align:center"><?php echo htmlspecialchars($rowr['unidad']); ?></td>
					<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['puntos']),2); ?></td>
					<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['material']),2); ?></td>
					<td style="text-align:right"><?php echo number_format(htmlspecialchars($rowr['cantidad']),2); ?></td>
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
		<th style="text-align:right"><?php echo number_format(htmlspecialchars($tb),2); ?></th>
		<th style="text-align:right">$<?php echo number_format(htmlspecialchars($tm),2); ?></th>
	</tr>
	</tfoot>
</table>
