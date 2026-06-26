<?php
include_once __DIR__ . "/../../includes/session.php"; 
sessionCheck();
//$fp = getFechaEntregaMateriales($id,$TAREA_ENTREGAMATERIALES,$fecha_solicitud);
include_once 'parts/ot/ot.adicion.inc.php'; ?>
<br class="clear"/>
<br class="clear"/>
<div id="table-ped-1" class="ui-widget">
	<table id="pedidos" class="ui-widget ui-widget-content" style="width: 100%">
		<thead>
			<tr class="ui-widget-header ">
				<th>Tipo OT</th>
				<th>PM</th>
				<th>Almac&eacute;n SAP:</th>
				<th>Codigo</th>
				<th>Material</th>
				<th>Unidad</th>
				<th>Lote</th>
				<th>Cantidad generada</th>
				<th>Cantidad adicionar</th>
				<th>Catidad total</th>
				<th>Estado Adici&oacute;n</th>
				<th>Motivo</th>
				<th>Fecha creaci&oacute;n</th>
				<th>Fecha fin adición</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$dataAdicion = @db_query($sql = " SELECT a.*, m.codigo, m.item, l.nombre lote, e.nombre estado_adicion, 
											   m2.nombre motivo, m.unidad, m.item txtaddMaterialAux
										FROM adicionxorden a 
										JOIN material m ON a.idmaterial = m.id
										JOIN lote l ON a.idlote = l.id
										LEFT JOIN estadoadicion e ON a.idestadoadicion = e.id
										LEFT JOIN motivoadicion m2 ON a.idmotivo = m2.id
										WHERE a.idorden = ".$id ." AND a.active = 'Si'");								
			if (mysqli_num_rows($dataAdicion) != 0) {
				while($rowAdicion = mysqli_fetch_array($dataAdicion)){?>
					<tr>
						<td><?=$adicionTipoOt?></td>
						<td><?php echo htmlspecialchars($rowAdicion['pm']); ?></td>
						<td><?php echo htmlspecialchars($rowAdicion['almacen_sap']); ?></td>
						<td><?php echo htmlspecialchars($rowAdicion['codigo']); ?></td>
						<td><?php echo htmlspecialchars($rowAdicion['item']); ?></td>
						<td><?php echo htmlspecialchars($rowAdicion['unidad']); ?></td>
						<td><?php echo htmlspecialchars($rowAdicion['lote']); ?></td>
						<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowAdicion['cantidadgenerada'],2)); ?></td>
						<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowAdicion['cantidad'],2)); ?></td>
						<td style="text-align:right"><?php echo htmlspecialchars(number_format($rowAdicion['cantidadtotal'],2)); ?></td>
						<td><?php echo htmlspecialchars($rowAdicion['estado_adicion']); ?></td>
						<td><?php echo htmlspecialchars($rowAdicion['motivo']); ?></td>
						<td><?php echo htmlspecialchars($rowAdicion['create_date']); ?></td>
						<td><?php echo htmlspecialchars($rowAdicion['finsolicitudaudicion']); ?></td>
						<?php if($appuser->idgrupo != $CONTRATISTA and (is_null($rowAdicion['idestadoadicion']) and is_null($rowAdicion['idmotivo']) ) and ($appuser->isInState($idestadoot,$OT_ST_PENDIENTEMATERIALESADICION))){ ?>
						<td><span class="ui-icon ui-icon-pencil" onclick="openPedido(<?=$rowAdicion['id']?>, <?=$rowAdicion['idmaterial']?>, '<?=$rowAdicion['pm']?>', '<?=$rowAdicion['almacen_sap']?>',
																					<?=$rowAdicion['idlote']?>, <?=$rowAdicion['cantidad']?>, null, null, <?=$rowAdicion['cantidadtotal']?>, '<?=$rowAdicion['unidad']?>', <?=$rowAdicion['cantidadgenerada']?>, '<?=$rowAdicion['txtaddMaterialAux']?>', '<?=$rowAdicion['lote']?>')"></span></td>
						<?php } else { ?>
						<td></td>
						<?php } ?>
					</tr>
				<?php
				}
			}
		?>
		</tbody>
	</table>
</div>
