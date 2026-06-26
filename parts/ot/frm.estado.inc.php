<?php if($appuser->isInState($idestadoot,$OT_PERMISOS)) { ?>
<script type="text/javascript">
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	var txtId = $( "#txtIdOrdenx2" );
	var txtChgEstadoOT = $( "#txtChgEstadoOT" );
	var lbChgEstadoOT = $( "#lbChgEstadoOT" );
	var txtChgObs = $( "#txtChgObs" );
	var allFields = $( [] ).add( lbChgEstadoOT ).add( txtChgObs );
	var tips = $( ".validateTips" );
	var otCambioCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtChgEstadoOT() {
		if ( txtChgEstadoOT.val() != "") {
			return true;
		} else {
			txtChgEstadoOT.addClass( "ui-state-error" );
			updateTips( "Seleccione un estado." );
			return false;
		}
	}
	function checktxtChgObs() {
		if ( txtChgObs.val().length > 0) {
			return true;
		} else {
			txtChgObs.addClass( "ui-state-error" );
			updateTips( "Debe ingresar las observaciones." );
			return false;
		}
	}

	$( "#ot-cambio" ).dialog({
		autoOpen: false,
		height: 320,
		width: 550,
		modal: true,
		buttons: {
			"Guardar": function() {
				var bValid = true;
				allFields.removeClass( "ui-state-error" );

				bValid = bValid && checktxtChgEstadoOT();
				bValid = bValid && checktxtChgObs();

				if (otCambioCtrl && bValid ) {
					otCambioCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=cambio"+
							"&txtId="+txtId.val() +
							"&txtChgEstadoOT="+txtChgEstadoOT.val() +
							"&txtChgObs="+encodeURIComponent(txtChgObs.val()),
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								loadCurrentTab($("#tabs").tabs('option', 'active'));
							}
							else updateTips(returnData);
						}
					});
				}
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
			}
		},
		open: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
			updateTips("Diligencie los siguientes datos.");
			txtChgObs.val("");
		}
	});

	$( "#cambio-ot" )
		.button({icons: {primary: 'ui-icon-flag'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-cambio" ).dialog( "open" );
		});
	$("#txtChgEstadoOT").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1
	});
});
</script>
<div id="ot-cambio" title="CAMBIAR ESTADO">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<fieldset>
		<input type="hidden" id="txtIdOrdenx2" name="txtIdOrdenx2" value="<?php echo $id; ?>"/>
		<table class="data-ro" id="ot-estado">
			<tr>
				<td class="title">Estado:</td><td class="field">
					<select name="txtChgEstadoOT" id="txtChgEstadoOT" class="wideFormSelect" tabindex="1">
					<?php
					echo "<option value=''>---SELECCIONE---</option>";
					$val = @db_query("SELECT id,nombre,active
						FROM estadoot
						WHERE id IN($OT_ST_OBRASCLIENTE,$OT_ST_PENDIENTEMATERIALES,$OT_ST_PERMISOSENTIDADNACIONAL,$OT_ST_PERMISOSENTIDADMUNICIPAL,$OT_ST_PERMISOSCOMERCIALES,
							$OT_ST_CONSULTASPREVIAS, $OT_ST_PENDIENTEPRESUPUESTOMATERIAL, $OT_ST_PUERTO_PON, $OT_ST_REMPLANTEADA, $OT_ST_CONORDENDETRABAJO, $OT_ST_PENDIENTEMATERIALESADICION)");
					 if (mysqli_num_rows($val) > 0){
						 while($row = mysqli_fetch_array($val)){
							$sel = $row['id'] == $idestadoot?"selected='selected'":"";
							$dis = $row['active'] != 'Si'?"disabled='disabled'":"";
							echo "<option value='".htmlspecialchars($row[id])."' $dis $sel>".htmlspecialchars($row[nombre])."</option>";
						 }
					 }
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="title">Observaciones:</td><td class="field"><textarea name='txtChgObs' id="txtChgObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
			</tr>
		</table>
	</fieldset>
</div>
<button id="cambio-ot">Modificar Estado</button>
<?php } ?>
