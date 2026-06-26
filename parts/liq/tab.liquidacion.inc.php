<table class="data-ro" id="liq-header">
	<tr>
		<td class="title">Numero:</td><td class="field"><?php echo htmlspecialchars($row['numero'])?></td>
		<td class="title">Solicitante:</td><td class="field"><?php  echo getNameById("usuarios",htmlspecialchars($row['create_user']))?></td>
		<td class="title">Estado:</td><td class="field"><?php echo getNameById("estadoliq",htmlspecialchars($row['idestadoliq']))?></td>
	</tr>
	<tr>
		<td class="title">Fecha Causacion:</td><td class="field"><?php echo getVal(htmlspecialchars($row['fecha_causacion']),"Pendiente")?></td>
		<td class="title">Fecha Liquidacion:</td><td class="field"><?php echo htmlspecialchars($row['fecha_liquidacion'])?></td>
		<td class="title">Tipo:</td><td class="field"><?php echo  htmlspecialchars($row['tipo'])?></td>
	</tr>
	<tr>
		<td class="title">Baremos:</td><td class="field"><?php echo number_format(htmlspecialchars($row['totalba']),2)?></td>
		<td class="title">Mano de Obra:</td><td class="field">$ <?php echo number_format(htmlspecialchars($row['totalmo']),2)?></td>
		<td class="title">Materiales:</td><td class="field">$ <?php echo number_format(htmlspecialchars($row['totalma']),2)?></td>
	</tr>
	<tr>
		<td class="title">Valor Sin utilidad:</td><td class="field">$ <?php echo number_format(htmlspecialchars($row['valor']),2)?></td>
		<td></td><td></td>
		<td class="title">Base Grabable:</td><td class="field">$ <?php echo number_format(htmlspecialchars($row['grabable']),2)?></td>
	</tr>
	<tr>
		<td class="title">Valor Facturado:</td><td class="field">$ <?php echo number_format(htmlspecialchars($row['facturado']),2)?></td>
		<td></td><td></td>
		<td class="title">Iva:</td><td class="field">$ <?php echo number_format(htmlspecialchars($row['iva']),2)?></td>
	</tr>
	<tr>
		<td class="title">Pedido:</td><td class="field"><?php echo htmlspecialchars($row['pedido'])?></td>
		<td class="title">Migo:</td><td class="field"><?php echo htmlspecialchars($row['migo'])?></td>
		<td class="title">Factura:</td><td class="field"><?php echo htmlspecialchars($row['factura'])?></td>
	</tr>
</table>
