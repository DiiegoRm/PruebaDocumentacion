<script type="text/javascript">
    $(function() {
        $("#txtRequerida").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>
<table class="data-ro" id="orden-header">
	<tr>
		<td class="title">Numero:</td><td class="field"><input type="text" id="txtNumero" readonly="readonly" name="txtNumero" value="<?php  echo htmlspecialchars($numero)?>" class="formInputText" tabindex="1" title=""/></td>
		<td class="title">Solicitante:</td><td class="field"><input type="text" id="txtSolicitante" readonly="readonly" name="txtSolicitante" value="<?php  echo htmlspecialchars($nombre_usuario) ?>" class="formInputText" tabindex="1" title=""/></td>
		<td class="title">Estado:</td><td class="field"><input type="text" id="txtEstado" readonly="readonly" name="txtEstado" value="<?php  echo htmlspecialchars($estadoclus) ?>" class="formInputText" tabindex="1" title=""/></td>
	</tr>
	<tr>
		<td class="title">Fecha Solicitud:</td><td class="field"><input type="text" readonly="readonly" name="txtDate" id="txtDate" value="<?php echo htmlspecialchars($fecha_solicitud)?>" class="formInputText" tabindex="1" title="Seleccione una fecha"/></td>

 <td class="title"><span class="<?php echo hasVal($txtano)?"completed":"required"?>">*</span>Año</label></td>
		<td class="input">
			<select name="txtano" id="txtano" <?php echo $disabled?> <?php echo $selwidth; ?> tabindex="1">
			<option value=''>---SELECCIONE---</option>
		<?php
		 $val = @db_query("SELECT d.id,d.nombre,d.active FROM annio d");
			 if (mysqli_num_rows($val) > 0){
			 while($row = mysqli_fetch_array($val)){
				$sel = $row['id'] == $idano?"selected='selected'":"";
				$dis = $row['active'] != 'Si'?"disabled='disabled'":"";
				echo "<option value='".htmlspecialchars($row[id])."' $dis $sel>".htmlspecialchars($row[nombre])."</option>";
			 }
		 }
		?>
</table>
<br class="clear"/>
