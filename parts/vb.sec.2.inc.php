<script type="text/javascript">
    $(function() {
        $("#txtEntrega").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>
<table class="data-ro" id="orden-sec1">
	<tr>
	<?php
    //$ds = $disabled; // Variable comentada
	if(($idestadovb == $VB_ST_REVISION || $idestadovb == $VB_ST_APLAZADA) && ($appuser->isAdmin() || $create_user == $appuser->uid || $appuser->isInGroup($GRP_SEGMENTO))) {
					$disabled = "";
				}
			?>

        <td class="title"><span class="<?php echo hasVal($idtipovb) ? "completed" : "required"; ?>">**</span><label class="formLabel" id="lbTipoVB" for="txtTipoVB">Requerimiento</label></td>
		<td class="input">
		<select name="txtTipoVB" id="txtTipoVB" <?php echo $disabled; ?> <?php echo $selwidth; ?> tabindex="1">
			<option value=''>---SELECCIONE---</option>
		<?php
		 $val = @db_query("SELECT id,nombre,area,active FROM tipovb where active!='No'order by 2");
		 if (mysqli_num_rows($val) > 0){
			 while($row = mysqli_fetch_array($val, MYSQLI_ASSOC)){
				$sel = $row['id'] == $idtipovb ? "selected='selected'" : "";
				$dis = $row['active'] != 'Si' ? "disabled='disabled'" : "";
				echo "<option value='".htmlspecialchars($row['id'])."' $dis $sel>".htmlspecialchars($row['nombre'])."</option>";
			 }
		 }
		?>
		</select>
		</td>

         <td class="title"><span class="<?php echo hasVal($entrega) ? "completed" : "required"; ?>">**</span>Fecha Entrega:</td>
        <td  class="input"><input type="text" readonly="readonly" name="txtEntrega" id="txtEntrega" class="wideFormInputText" value="<?php echo htmlspecialchars($entrega); ?>"  tabindex="1" title="Seleccione una fecha"/></td>
	</tr>
	<tr>
		<td class="title"><span class="<?php echo hasVal($idregion) ? "completed" : "required"; ?>">*</span>Region:</td>
		<td class="input">
			<select name="txtRegion" id="txtRegion" <?php echo $disabled; ?> <?php echo $selwidth; ?> tabindex="1">
				<option value=''>---SELECCIONE---</option>
			<?php
			 $val = @db_query("SELECT id,nombre,active FROM regiones WHERE 1 ".$appuser->getRegionFilterVB());
			 if (mysqli_num_rows($val) > 0){
				 while($row = mysqli_fetch_array($val, MYSQLI_ASSOC)){
					$sel = $row['id'] == $idregion ? "selected='selected'" : "";
					$dis = $row['active'] != 'Si' ? "disabled='disabled'" : "";
					echo "<option value='".htmlspecialchars($row['id'])."' $dis $sel>".htmlspecialchars($row['nombre'])."</option>";
				 }
			 }
			?>
			</select>
		</td>
		<td class="title"><span class="<?php echo hasVal($idjefatura) ? "completed" : "required"; ?>">*</span>Jefatura Comercial:</td>
		<td class="input">
			<select name="txtJefatura" id="txtJefatura" <?php echo $disabled; ?> <?php echo $selwidth; ?> tabindex="1">
			<?php if(!hasVal($idregion)){
				echo "<option value=''>---SELECCIONE---</option>";
			} else {
				$val = @db_query("SELECT id,nombre,active FROM jefaturas WHERE idregion=$idregion ".$appuser->getJefaturaFilterVB());
				if (mysqli_num_rows($val) > 0){
					while($row = mysqli_fetch_array($val, MYSQLI_ASSOC)){
						$sel = $row['id'] == $idjefatura ? "selected='selected'" : "";
						$dis = $row['active'] != 'Si' ? "disabled='disabled'" : "";
						echo "<option value='".htmlspecialchars($row['id'])."' $dis $sel>".htmlspecialchars($row['nombre'])."</option>";
					}
				}
			} ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="title"><span class="<?php echo hasVal($iddepto) ? "completed" : "required"; ?>">*</span>Departamento:</td>
		<td class="input">
			<select name="txtDepto" id="txtDepto" <?php echo $disabled; ?> <?php echo $selwidth; ?> tabindex="1">
			<?php if(isset($idjefatura)){
				echo "<option value=''>---SELECCIONE---</option>";
			 $val = @db_query("SELECT d.id,d.nombre,d.active FROM deptos d, deptosxjefatura dj WHERE dj.idjefatura=$idjefatura AND dj.iddepto=d.id ".$appuser->getDeptoFilterVB("id","d."));
			 if (mysqli_num_rows($val) > 0){
				 while($row = mysqli_fetch_array($val, MYSQLI_ASSOC)){
					$sel = $row['id'] == $iddepto ? "selected='selected'" : "";
					$dis = $row['active'] != 'Si' ? "disabled='disabled'" : "";
					echo "<option value='".htmlspecialchars($row['id'])."' $dis $sel>".htmlspecialchars($row['nombre'])."</option>";
				 }
			 }
			}
			?>
			</select>
		</td>
		<td class="title"><span class="<?php echo hasVal($idjefe) ? "completed" : "required"; ?>">*</span>Jefe Comercial:</td>
		<td class="input">
			<select name="txtJefe" id="txtJefe" <?php echo $disabled; ?> <?php echo $selwidth; ?> tabindex="1">
			<?php if(!hasVal($idjefe)){
				echo "<option value=''>---SELECCIONE---</option>";
			} else { echo getOptionById("jefes",htmlspecialchars($idjefe)); } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="title"><span class="<?php echo hasVal($idlocalidad) ? "completed" : "required"; ?>">*</span>Localidad:</td>
		<td class="input">
			<select name="txtLocalidad" id="txtLocalidad" <?php echo $disabled; ?> <?php echo $selwidth; ?> tabindex="1">
			<?php if(isset($iddepto)){
				echo "<option value=''>---SELECCIONE---</option>";
			 $val = @db_query("SELECT id,nombre,active FROM localidades WHERE iddepto=$iddepto ".$appuser->getLocalidadFilterVB());
			 if (mysqli_num_rows($val) > 0){
				 while($row = mysqli_fetch_array($val, MYSQLI_ASSOC)){
					$sel = $row['id'] == $idlocalidad ? "selected='selected'" : "";
					$dis = $row['active'] != 'Si' ? "disabled='disabled'" : "";
					echo "<option value='".htmlspecialchars($row['id'])."' $dis $sel>".htmlspecialchars($row['nombre'])."</option>";
				 }
			 }
			}
			?>
			</select>
		</td>
		<td class="title"><span class="<?php echo hasVal($idproyectovb) ? "completed" : "required"; ?>">*</span>Proyecto:</td>
		<td class="input">
			<select name="txtProyecto" id="txtProyecto" <?php echo $disabled; ?> <?php echo $selwidth; ?> tabindex="1">
				<option value=''>---SELECCIONE---</option>
			<?php
			 $val = @db_query("SELECT id,nombre,active FROM proyectovb where active!='No' order by 2");
			 if (mysqli_num_rows($val) > 0){
				 while($row = mysqli_fetch_array($val, MYSQLI_ASSOC)){
					$sel = $row['id'] == $idproyectovb ? "selected='selected'" : "";
					$dis = $row['active'] != 'Si' ? "disabled='disabled'" : "";
					echo "<option value='".htmlspecialchars($row['id'])."' $dis $sel>".htmlspecialchars($row['nombre'])."</option>";
				 }
			 }
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="title"><span class="<?php echo hasVal($nombre) ? "completed" : "required"; ?>">*</span>Nombre Proyecto:</td>
		<td class="input">
			<input type="text" id="txtNombre" name="txtNombre" <?php echo $disabled; ?> value="<?php echo htmlspecialchars($nombre); ?>" maxlength='200' class="wideFormInputText" tabindex="1" title=""/>		</td>
		<td class="title"><span class="<?php echo hasVal($direccion) ? "completed" : "required"; ?>">*</span>Direccion:</td>
		<td class="input">
			<input type="text" id="txtDireccion" name="txtDireccion" <?php echo $disabled; ?> value="<?php echo htmlspecialchars($direccion); ?>" maxlength='200' class="wideFormInputText" tabindex="1" title=""/>
		</td>
	</tr>
	<tr>
		<td class="title">Constructora:</td>
		<td class="input">
			<input type="text" id="txtConstructora" name="txtConstructora" <?php echo $disabled; ?> value="<?php echo htmlspecialchars($constructora); ?>" maxlength='200' class="formInputText" tabindex="1" title=""/>
		</td>
		<td class="title">Contacto:</td>
		<td class="input">
			<input type="text" id="txtContacto" name="txtContacto" <?php echo $disabled; ?> value="<?php echo htmlspecialchars($contacto); ?>" maxlength='200' class="formInputText" tabindex="1" title=""/>
		</td>
	</tr>
	<tr>
		<td class="title">Telefono:</td>
		<td class="input">
			<input type="text" id="txtTelefono" name="txtTelefono" <?php echo $disabled; ?> value="<?php echo htmlspecialchars($telefono); ?>" maxlength='200' class="formInputText" tabindex="1" title=""/>
		</td>
	<td class="title">
        <span class="<?php echo hasVal($txtDs) ? "completed" : "required"; ?>">*</span><label class="formLabel" id="lbDs" for="txtDs">DS:</label></td>
        <td class="input">
        <input type="text" id="txtDs" name="txtDs" <?php if ($idtipovb==34 || $idtipovb==35 || $idtipovb==50){ echo ""; }else{ echo "disabled";}?> value="<?php echo htmlspecialchars($Ds); ?>" maxlength='200' class="wideFormInputText" tabindex="1" title=""/></td>
	</tr>
	  <tr>
		<td class="title"><span class="<?php echo hasVal($numcto) ? "completed" : "required"; ?>">*</span>Numero de CTO&#39;s:</td>
		<td class="input">
			<input type="text" id="txtnumerocto" name="txtnumerocto" <?php echo $disabled; ?> value="<?php echo htmlspecialchars($numcto); ?>" maxlength='200' class="wideFormInputText" tabindex="1" title=""/>
		</td>
	</tr>
	</table>
	<br class="clear"/>
	<p> Proyecto Fibra:<hr /></p>
<table class="data-ro" id="orden-sec1">
<tr>
<td class="title"><span class="<?php echo hasVal($cable) ? "completed" : "required"; ?>">*</span><label class="formLabel" id="lbcable" for="txtCable">Cable:</label></td>
<td class="input">
		<input type="text" id="txtCable" name="txtCable" <?php echo $disabled; ?> value="<?php echo htmlspecialchars($cable); ?>" maxlength='20' class="wideFormInputText" tabindex="1" title=""/>
	</td>
<td class="title"><span class="<?php echo hasVal($idcentral) ? "completed" : "required"; ?>">*</span><label class="formLabel" id="lbCentral" for="txtCentral">Central:</label></td>
<td class="input">
			<select name="txtCentral" id="txtCentral" <?php echo $disabled; ?> <?php echo $selwidth; ?> tabindex="1">
				<option value=''>---SELECCIONE---</option>
				<?php
			 $val = @db_query("SELECT id,nombre,active FROM central");
			 if (mysqli_num_rows($val) > 0){

				 while($row = mysqli_fetch_array($val, MYSQLI_ASSOC)){
					$sel = $row['id'] == $idcentral ? "selected='selected'" : "";
					$dis = $row['active'] != 'Si' ? "disabled='disabled'" : "";
					echo "<option value='".htmlspecialchars($row['id'])."' $dis $sel>".htmlspecialchars($row['nombre'])."</option>";
				 }
			 }
			?>
			</select>
		</td>
</tr>
<tr>

<td class="title"><span class="<?php echo hasVal($id_region) ? "completed" : "required"; ?>">*</span><label class="formLabel" id="lbregiion" for="txtregiion">Region:</label></td>
		<td class="input">
			<select name="txtregiion" id="txtregiion"  <?php echo $disabled; ?> <?php echo $selwidth; ?> tabindex="1">
				<option value=''>---SELECCIONE---</option>
				<?php
                // Se ha comentado la llamada a la función que causa el error.
                // DEBES revisar la clase AppUser para implementar getRegionFilterftthVB()
                // o determinar si getRegionFilterVB() es el filtro correcto aquí.
			 $val = @db_query("SELECT id,nombre,active FROM region WHERE 1 /* Removed problematic filter call */");
			 if (mysqli_num_rows($val) > 0){
				 while($row = mysqli_fetch_array($val, MYSQLI_ASSOC)){
					$sel = $row['id'] == $id_region ? "selected='selected'" : "";
					$dis = $row['active'] != 'Si' ? "disabled='disabled'" : "";
					echo "<option value='".htmlspecialchars($row['id'])."' $dis $sel>".htmlspecialchars($row['nombre'])."</option>";
				 }
			 }
			?>
			</select>
		</td>
	<td class="title"><span class="<?php echo hasVal($conversor) ? "completed" : "required"; ?>">*</span><label class="formLabel" id="lbconversor" for="txtconversor">Coinversor:</label></td>
	<td class="input">
		<input type="text" id="txtconversor" name="txtconversor"  <?php echo $disabled; ?> value="<?php echo htmlspecialchars($conversor); ?>" maxlength='200'  class="wideFormInputText" tabindex="1" title=""/>
	</td>

</tr>
<tr>
<td class="title"><span class="<?php echo hasVal($idpoligono) ? "completed" : "required"; ?>">*</span><label class="formLabel" id="lbPoligono" for="txtPoligono">Poligono:</label></td>
	<td class="input" >
			<select name="txtPoligono" id="txtPoligono"  <?php echo $disabled; ?> <?php echo $selwidth; ?>  tabindex="1" maxlength='200'>
			<option value=''>---SELECCIONE---</option>
			<?php if (isset ($id_region)){
	 $val = @db_query("SELECT id,nombre,active FROM poligono WHERE idregion=$id_region ".$appuser->getpoligonoFilterftthVB());
	 if (mysqli_num_rows($val) > 0){
		 while($row = mysqli_fetch_array($val, MYSQLI_ASSOC)){
			$sel = $row['id'] == $idpoligono ? "selected='selected'" : "";
			$dis = $row['active'] != 'Si' ? "disabled='disabled'" : "";
			echo "<option value='".htmlspecialchars($row['id'])."' $dis $sel>".htmlspecialchars($row['nombre'])."</option>";
		         }
			}

		}
			?>
			</select>
		</td>

		<td class="title"><span class="<?php echo hasVal($Hogares_pasados) ? "completed" : "required"; ?>">*</span><label class="formLabel" id="lbhogarespas" for="txthogarespas">Hogares Pasados:</label></td>
		<td class="input">
	<input type="text" id="txthogarespas" name="txthogarespas"  <?php echo $disabled; ?> value="<?php echo htmlspecialchars($Hogares_pasados); ?>" maxlength='200' class="wideFormInputText" tabindex="1" title=""/>
	</td>
</tr>
<tr>
<td class="title"><span class="<?php echo hasVal($idcomuna) ? "completed" : "required"; ?>">*</span><label class="formLabel" id="lbComuna" for="txtComuna">Municipio:</label></td>
	<td class="input">
			<select name="txtComuna" id="txtComuna"  <?php echo $disabled; ?> <?php echo $selwidth; ?> tabindex="1">
			<option value=''>---SELECCIONE---</option>
			<?php if (isset ($idpoligono)){
	 $val = @db_query("SELECT id,nombre,active FROM comuna WHERE idpoligono=$idpoligono ".$appuser->getcomunaFilterftthVB());
	 if (mysqli_num_rows($val) > 0){
		 while($row = mysqli_fetch_array($val, MYSQLI_ASSOC)){
			$sel = $row['id'] == $idcomuna ? "selected='selected'" : "";
			$dis = $row['active'] != 'Si' ? "disabled='disabled'" : "";
			echo "<option value='".htmlspecialchars($row['id'])."' $dis $sel>".htmlspecialchars($row['nombre'])."</option>";
		         }
			}
		}
			?>
			</select>
		</td>
<td class="title"><span class="<?php echo hasVal($subcluster) ? "completed" : "required"; ?>">*</span><label class="formLabel" id="lbsubclus" for="txtsubclus">Sub-Cluster:</label></td>
<td class="input">
<input type="text" id="txtsubclus" name="txtsubclus"  <?php echo $disabled; ?> value="<?php echo htmlspecialchars($subcluster); ?>" maxlength='200' class="wideFormInputText" tabindex="1" title=""/>
</td>
</tr>

<tr>
<td class="title"><span class="<?php echo hasVal($idcluster) ? "completed" : "required"; ?>">*</span><label class="formLabel" id="lbcluster" for="txtcluster">Cluster FTTH:</label></td>
<td class="input">
			<select name="txtcluster" id="txtcluster" <?php echo $disabled; ?> <?php echo $selwidth; ?>  tabindex="1">
			<option value=''>---SELECCIONE---</option>
			<?php if (isset ($idcomuna)){

	 $val = @db_query("SELECT id,nombre,active FROM cluster WHERE idcomuna=$idcomuna ".$appuser->getclusterFilterftthVB());
	 if (mysqli_num_rows($val) > 0){
		 while($row = mysqli_fetch_array($val, MYSQLI_ASSOC)){
			$sel = $row['id'] == $idcluster ? "selected='selected'" : "";
			$dis = $row['active'] != 'Si' ? "disabled='disabled'" : "";
			echo "<option value='".htmlspecialchars($row['id'])."' $dis $sel>".htmlspecialchars($row['nombre'])."</option>";
		         }
			}
		}
			?>
			</select>
		</td>

	<td class="title"><span class="<?php echo hasVal($idtipozona) ? "completed" : "required"; ?>">*</span><label class="formLabel" id="lbTipoZona" for="txtTipoZona">Tipo Zona:</label></td>
<td class="input">
			<select name="txtTipoZona" id="txtTipoZona"  <?php echo $disabled; ?> <?php echo $selwidth; ?> tabindex="1">
				<option value=''>---SELECCIONE---</option>
				<?php
			 $val = @db_query("SELECT id,nombre,active FROM tipozona ");
			 if (mysqli_num_rows($val) > 0){
				 while($row = mysqli_fetch_array($val, MYSQLI_ASSOC)){
					$sel = $row['id'] == $idtipozona ? "selected='selected'" : "";
					$dis = $row['active'] != 'Si' ? "disabled='disabled'" : "";
					echo "<option value='".htmlspecialchars($row['id'])."' $dis $sel>".htmlspecialchars($row['nombre'])."</option>";
				 }
			 }
			?>
			</select>
		</td>
</tr>
<tr>
		<td class="title"><span class="<?php echo hasVal($idtipo_vb) ? "completed" : "required"; ?>">*</span><label class="formLabel" id="lbtipo_vb" for="txttipo_vb">Tipo vb:</label></td>
		<td class="input">
			<select name="txttipo_vb" id="txttipo_vb" <?php echo $disabled; ?> <?php echo $selwidth; ?> tabindex="1">
				<option value=''>---SELECCIONE---</option>
				<?php
			 $val = @db_query("SELECT id,nombre,active FROM tipo_vb");
			 if (mysqli_num_rows($val) > 0){
				 while($row = mysqli_fetch_array($val, MYSQLI_ASSOC)){
					$sel = $row['id'] == $idtipo_vb ? "selected='selected'" : "";
					$dis = $row['active'] != 'Si' ? "disabled='disabled'" : "";
					echo "<option value='".htmlspecialchars($row['id'])."' $dis $sel>".htmlspecialchars($row['nombre'])."</option>";
				 }
			 }
			?>
			</select>
		</td>
</tr>
</table>
<br class="clear"/>
<p>Demanda:<hr /></p>
<table class="data-ro" id="orden-sec2">
	<tr>
		<td class="title"><span class="<?php echo (hasVal($lb)||hasVal($ba)||hasVal($tv)) ? "completed" : "required"; ?>">*</span>Linea Basica:</td><td class="input">
			<input type="text" id="txtLB" name="txtLB"  <?php echo $disabled; ?> value="<?php echo htmlspecialchars($lb); ?>" maxlength='7' class="formInputText" tabindex="1" title=""/>
		</td>
		<td class="title"><span class="<?php echo (hasVal($lb)||hasVal($ba)||hasVal($tv)) ? "completed" : "required"; ?>">*</span>Banda Ancha:</td><td class="input">
			<input type="text" id="txtBA" name="txtBA"   <?php echo $disabled; ?> value="<?php echo htmlspecialchars($ba); ?>" maxlength='7' class="formInputText" tabindex="1" title=""/>
		</td>
		<td class="title"><span class="<?php echo (hasVal($lb)||hasVal($ba)||hasVal($tv)) ? "completed" : "required"; ?>">*</span>Television:</td><td class="input">
			<input type="text" id="txtTV" name="txtTV"   <?php echo $disabled; ?> value="<?php echo htmlspecialchars($tv); ?>" maxlength='7' class="formInputText" tabindex="1" title=""/>
		</td>
	</tr>
</table>
<table class="data-ro" id="orden-sec-31">
	<tr>
		<td class="title"><span class="<?php echo hasVal($estrato) ? "completed" : "required"; ?>">*</span>Estrato:</td>
		<td class="input">
			<select name="txtEstrato" id="txtEstrato"  <?php echo $disabled; ?> <?php echo $selwidth; ?> tabindex="1">
				<option value='' <?php echo $estrato==""?"selected='selected'":""; ?>>---SELECCIONE---</option>
				<option value='1'<?php echo $estrato=="1"?"selected='selected'":""; ?>>Estrato 1</option>
				<option value='2'<?php echo $estrato=="2"?"selected='selected'":""; ?>>Estrato 2</option>
				<option value='3'<?php echo $estrato=="3"?"selected='selected'":""; ?>>Estrato 3</option>
				<option value='4'<?php echo $estrato=="4"?"selected='selected'":""; ?>>Estrato 4</option>
				<option value='5'<?php echo $estrato=="5"?"selected='selected'":""; ?>>Estrato 5</option>
				<option value='6'<?php echo $estrato=="6"?"selected='selected'":""; ?>>Estrato 6</option>
				<option value='Comercial'<?php echo $estrato=="Comercial"?"selected='selected'":""; ?>>Estrato Comercial</option>
			</select>
		</td>
		<td class="title"><span class="<?php echo hasVal($viviendas) ? "completed" : "required"; ?>">*</span>No. Viviendas:</td>
		<td class="input">
			<input type="text" id="txtViviendas" name="txtViviendas"   <?php echo $disabled; ?> value="<?php echo htmlspecialchars($viviendas); ?>" <?php echo $selwidth; ?> maxlength='12' class="formInputText" tabindex="1" title=""/>
		</td>
	</tr>
	<tr>
		<td class="title">Etapa:</td>
		<td class="input">
			<input type="text" id="txtEtapa" name="txtEtapa"  <?php echo $disabled; ?> value="<?php echo htmlspecialchars($etapa); ?>" maxlength='12' class="formInputText" tabindex="1" title=""/>
		</td>
		<td class="title">Viviendas Etapa:</td>
		<td class="input">
			<input type="text" id="txtViviendasEtapa" name="txtViviendasEtapa"   <?php echo $disabled; ?> value="<?php echo htmlspecialchars($viviendas_etapa); ?>" maxlength='12' class="formInputText" tabindex="1" title=""/>
		</td>
	</tr>
</table>
<br class="clear"/>
<?php $disabled=$ds ?>
<hr/>
<table class="data-ro" id="orden-sec4">
	<tr>
		<td class="title">EECC Asignado:</td>
        <td class="input">
			<input type="text" id="txtEeccAsig" name="txtEeccAsig"  <?php echo $disabled; ?> value="<?php  echo htmlspecialchars($eecc_asignado); ?>" maxlength='12' class="formInputText" tabindex="1" title=""/>
		</td>
		<td class="title"># Orden <?php  echo hasVal($ot_asignada)?"Solicitada":"Asignada"; ?>:</td><td class="field"><?php  echo htmlspecialchars($ot_asignada); ?></td>
	</tr>
</table>
<script type="text/javascript">
$("#txtnumerocto").prop('disabled',true);
</script>