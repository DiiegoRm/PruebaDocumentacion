<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";
$id=decrypt(getVal($_GET['id'],"0"));
$ver=decrypt(getVal($_GET['ver'],"0"));
//echo $id . "-" . $ver;
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
			$datar = @db_query("SELECT b.id,b.item,b.descripcion,b.unidad,a.puntos,a.material,a.cantidad,a.puntos*a.cantidad sb,a.material*a.cantidad sm FROM actividadesxorden a, baremo b WHERE a.idorden=$id AND a.version=$ver AND a.idbaremo=b.id ORDER BY b.idclase,b.id");
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
