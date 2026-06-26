<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";
$id=decrypt(getVal($_GET['id'],"0"));
?>
<div id="table-tatalb" class="ui-widget">
	<table id="tactbar" class="ui-widget ui-widget-content" style="width: 100%;">
		<thead class="ui-widget-header">
			<tr>
				<th style="width:300px;">Clase Mano de Obra</th>
				<th>Unidad</th>
				<th>Valor/Unitario (CoP$)</th>
				<th>Costo Directo (CoP$)</th>
				<th>Puntos/Baremo</th>
				<th>Valor Total (CoP$)</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$t =  db_query("SELECT cm.id,pp.unidad,cm.nombre,pp.valor,pp.costo,pp.puntos,pp.puntos*pp.valor total
				FROM preciosxpresupuesto pp, clasemanoobra cm
				WHERE pp.idclase=cm.id AND pp.idpresupuesto=$id");
			$i = 0;
			while ($row = mysqli_fetch_array($t)) {
				$style = ($i++%2==0)?"odd":"even"; ?>
				<tr class='<?php echo $style; ?>'>
				<td style="text-align:left"><?php echo htmlspecialchars($row['nombre'])?></td>
				<td><?php echo htmlspecialchars($row['unidad'])?></td>
				<td style="text-align:right">$<?php echo number_format(htmlspecialchars($row['valor']),2)?></td>
				<td style="text-align:right">$<?php echo number_format(htmlspecialchars($row['costo']),2)?></td>
				<td style="text-align:right"><?php echo number_format(htmlspecialchars($row['puntos']),4)?></td>
				<td style="text-align:right">$<?php echo number_format(htmlspecialchars($row['total']),4)?></td>
			</tr>
		<?php } ?>
		</tbody>
		<?php
			$tt =  db_query("SELECT * FROM totalesxpresupuesto WHERE idpresupuesto=$id");
			$rt = mysqli_fetch_array($tt);
			if (count($rt)>0) {
		?>
		<tfoot class="ui-widget-content">
		<tr class="ui-state-highlight">
			<th colspan="5" style="text-align:left">Factor Departamento</th>
			<th style="text-align:right"><?php echo number_format(htmlspecialchars($rt['fdepto']),2); ?></th>
		</tr>
		<tr class="ui-state-highlight">
			<th colspan="5" style="text-align:left">Costo Directo(No incluye Puntos Pactados)</th>
			<th style="text-align:right">$<?php echo number_format(htmlspecialchars($rt['cdirecto']),2); ?></th>
		</tr>
		<tr class="ui-state-highlight">
			<th colspan="5" style="text-align:left">Costo Directo + AIU</th>
			<th style="text-align:right">$<?php echo number_format(htmlspecialchars($rt['costoaiu']),2); ?></th>
		</tr>
		<tr class="ui-state-highlight">
			<th colspan="5" style="text-align:left">Utilidad (<?php echo number_format(htmlspecialchars($rt['utilidadp']),2); ?>%)</th>
			<th style="text-align:right">$<?php echo number_format(htmlspecialchars($rt['utilidad']),2); ?></th>
		</tr>
		<tr class="ui-state-highlight">
			<th colspan="5" style="text-align:left">IVA (<?php echo number_format(htmlspecialchars($rt['ivap'])*100,2); ?>%)</th>
			<th style="text-align:right">$<?php echo number_format(htmlspecialchars($rt['iva']),2); ?></th>
		</tr>
		<tr><th></th></tr>
		<tr class="ui-state-highlight">
			<th colspan="4" style="text-align:right">Valor Total M.O.($)</th>
			<th colspan="2" style="text-align:right"><?php echo number_format(htmlspecialchars($rt['tmo']),2); ?></th>
		</tr>
		<tr><th></th></tr>
		<tr>
			<th colspan="4" style="text-align:right">Valor Cable($)</th>
			<th colspan="2" style="text-align:right"><?php echo number_format(htmlspecialchars($rt['tca']),2); ?></th>
		</tr>
		<tr>
			<th colspan="4" style="text-align:right">Valor Otros Materiales($)</th>
			<th colspan="2" style="text-align:right"><?php echo number_format(htmlspecialchars($rt['totros']),2); ?></th>
		</tr>
		<tr class="ui-state-hover">
			<th colspan="4" style="text-align:right">VALOR TOTAL DEL PROYECTO($)</th>
			<th colspan="2" style="text-align:right"><?php echo number_format(htmlspecialchars($rt['tpry']),2); ?></th>
		</tr>
		</tfoot>
		<?php } ?>
	</table>
</div>
