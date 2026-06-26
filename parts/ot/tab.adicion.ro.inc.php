<?php
include_once "../../includes/session.php";
sessionCheck(); 

include_once "../../includes/global.php";
include_once "../../includes/database.php";

$id=decrypt(getVal(clean_input($_GET['id']),"0"));
?>
<table id="pedidos-ro" class="ui-widget ui-widget-content" style="width: 100%">
	<thead>
		<tr class="ui-widget-header ">
			<th>Tipo OT</th>
			<th>PM</th>
			<th>Almac&eacute;n SAP:</th>
			<th>Material</th>
			<th>Unidad</th>
			<th>Lote</th>
			<th>Cantidad generada</th>
			<th>Cantidad adicionar</th>
			<th>Catidad total</th>
			<th>Estado Adici&oacute;n</th>
			<th>Motivo</th>
			<th>Fecha creaci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$dataq = @db_query($sql = "SELECT a.*, m.item, l.nombre lote, e.nombre estado_adicion, 
											   m2.nombre motivo, m.unidad, p.cantidad cantidadedicion
										FROM adicionxorden a 
										JOIN material m ON a.idmaterial = m.id
										JOIN pedidosxorden p ON m.id = p.idmaterial
										JOIN lote l ON a.idlote = l.id
										JOIN estadoadicion e ON a.idestadoadicion = e.id
										JOIN motivoadicion m2 ON a.idmotivo = m2.id
										WHERE a.idorden = ".$id ." AND a.active = 'Si'");
		if (mysqli_num_rows($dataq) != 0) {
			while($rowq = mysqli_fetch_array($dataq)){?>
				<tr>
					<td><?=$adicionTipoOt?></td>
					<td><?php echo htmlspecialchars($rowAdicion['pm']); ?></td>
					<td><?php echo htmlspecialchars($rowAdicion['almacen_sap']); ?></td>
					<td><?php echo htmlspecialchars($rowAdicion['item']); ?></td>
					<td><?php echo htmlspecialchars($rowAdicion['unidad']); ?></td>
					<td><?php echo htmlspecialchars($rowAdicion['lote']); ?></td>
					<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowAdicion['cantidadedicion'],2)); ?></td>
					<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowAdicion['cantidad'],2)); ?></td>
					<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowAdicion['cantidadtotal'],2)); ?></td>
					<td><?php echo htmlspecialchars($rowAdicion['estado_adicion']); ?></td>
					<td><?php echo htmlspecialchars($rowAdicion['motivo']); ?></td>
					<td><?php echo htmlspecialchars($rowAdicion['create_date']); ?></td>
				</tr>
			<?php
			}
		}
	?>
	</tbody>
</table>
