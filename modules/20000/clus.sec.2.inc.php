<script type="text/javascript">
    $(function() {
        $("#txtEntrega").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>
<table class="data-ro" id="orden-sec1">
	<tr>
<!--formulario "adicionar cluster" por el modulo de FTTH-->
		 <td class="title"><span class="<?php echo hasVal($txtDepto)?"completed":"required"?>">**</span>Departamento</label></td>
		<td class="input">
			<select name="txtDepto" id="txtDepto" <?php echo $disabled?> <?php echo $selwidth; ?> tabindex="1">
			<option value=''>---SELECCIONE---</option>
		<?php
		 $val = @db_query("SELECT d.id,d.nombre,d.active FROM deptos d  ".$appuser->getDeptoFilterVB("id","d."));
			 if (mysqli_num_rows($val) > 0){
			 while($row = mysqli_fetch_array($val)){
				$sel = $row['id'] == $iddepto?"selected='selected'":"";
				$dis = $row['active'] != 'Si'?"disabled='disabled'":"";
				echo "<option value='".htmlspecialchars($row[id])."' $dis $sel>".htmlspecialchars($row[nombre])."</option>";
			 }
		 }
		?>



		<td class="title"><span class="<?php echo hasVal($txtLocalidad)?"completed":"required"?>">*</span>Localidad:</td>
		<td class="input">
			<select name="txtLocalidad" id="txtLocalidad" <?php echo $disabled?> <?php echo $selwidth; ?> tabindex="1">
			<?php if(isset($iddepto)){
				echo "<option value=''>---SELECCIONE---</option>";
			 $val = @db_query("SELECT l.id,l.nombre,l.active FROM localidades l where l.iddepto=$iddepto");
			 if (mysqli_num_rows($val) > 0){
				 while($row = mysqli_fetch_array($val)){
					$sel = $row['id'] == $idlocalidad?"selected='selected'":"";
					$dis = $row['active'] != 'Si'?"disabled='disabled'":"";
					echo "<option value='".htmlspecialchars($row[id])."' $dis $sel>".htmlspecialchars($row[nombre])."</option>";
				 }
			 }
			}
			?>
			</select>
		</td>

			</select>
		</td>
	</tr>
	<tr>
		<td class="title"><span class="<?php echo hasVal($txtNombre)?"completed":"required"?>">*</span>Nombre Proyecto:</td>
		<td class="input">
			<input type="text" id="txtNombre" name="txtNombre" <?php echo htmlspecialchars($disabled)?> value="<?php echo htmlspecialchars($nombre) ?>" maxlength='200' class="wideFormInputText" tabindex="1" title=""/>		</td>


		<td class="title"><span class="<?php echo hasVal($txtestimado)?"completed":"required"?>">*</span>HHPP Estimado:</td>
		<td class="input">
			<input type="text" id="txtestimado" name="txtestimado" <?php echo htmlspecialchars($disabled)?> value="<?php echo htmlspecialchars($estimado) ?>" maxlength='200' class="wideFormInputText" tabindex="1" title=""/>
		</td>
	</tr>


<tr>
</table>
<br class="clear"/>
<p><hr /></p>
	<tr>

</table>
