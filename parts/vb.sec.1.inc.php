<script type="text/javascript">
    $(function() {
        $("#txtRequerida").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>
<table class="data-ro" id="orden-header">
	<tr>
		<td class="title">Numero:</td><td class="field"><input type="text" id="txtNumero" readonly="readonly" name="txtNumero" value="<?php  echo $numero?>" class="formInputText" tabindex="1" title=""/></td>
		<td class="title">Solicitante:</td><td class="field"><input type="text" id="txtSolicitante" readonly="readonly" name="txtSolicitante" value="<?php  echo $nombre_usuario ?>" class="formInputText" tabindex="1" title=""/></td>
		<td class="title">Estado:</td><td class="field"><input type="text" id="txtEstado" readonly="readonly" name="txtEstado" value="<?php  echo $estadovb?>" class="formInputText" tabindex="1" title=""/></td>
	</tr>
	<tr>
		<td class="title">Fecha Solicitud:</td><td class="field"><input type="text" readonly="readonly" name="txtDate" id="txtDate" value="<?php echo $fecha_solicitud?>" class="formInputText" tabindex="1" title="Seleccione una fecha"/></td>
        <td class="title">Fecha Requerida:</td><td class="field"><input type="text" readonly="readonly" name="txtRequerida" id="txtRequerida" value="<?php echo $fecha_requerida; ?>" class="wideFormInputText" tabindex="1" title="Seleccione una fecha"/></td>

    <?php
    $ds = $disabled;
	if(($idestadovb==$VB_ST_REVISION || $idestadovb==$VB_ST_APLAZADA) && ($appuser->isAdmin() || $create_user == $appuser->uid || $appuser->isInGroup("$GRP_SEGMENTO")))     {
					$disabled = "";
				}
			?>


        <td class="title">

            <span class="<?php echo hasVal($idsegmento)?"completed":"required"?>">**</span>Segmento:</td><td class="field">
		<?php if(!hasVal($idsegmento)||($idestadovb==$VB_ST_REVISION OR $idestadovb==$VB_ST_CREACION OR $idestadovb==$VB_ST_APLAZADA)){          ?>
        <select name="txtSegmento" id="txtSegmento" <?php echo $disabled;?><?php echo $selwidth; ?>  style="width: 200px;" tabindex="1">
				<option value=''>---SELECCIONE---</option>
			<?php
			 $val = @db_query("SELECT id,nombre,active FROM segmentos WHERE 1 ".$appuser->getSegmentoFilterVB());
			 if (mysqli_num_rows($val) > 0){
				 while($row = mysqli_fetch_array($val)){
					$sel = $row['id'] == $idsegmento?"selected='selected'":"";
					$dis = $row['active'] != 'Si'?"disabled='disabled'":"";
					echo "<option value='".htmlspecialchars($row[id])."' $dis $sel>".htmlspecialchars($row[nombre])."</option>";
				 }
			 }
			?>
			</select>
			<?php } else { ?>
			<input type="text" readonly="readonly" name="txtSeg" id="txtSeg" value="<?php echo $segmento?>" class="formInputText"/>
			<?php } ?>
		</td>
	</tr>
</table>
<br class="clear"/>
