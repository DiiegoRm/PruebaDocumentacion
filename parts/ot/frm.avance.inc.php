<?php if($appuser->isInState($idestadoot,$OT_PERMISOS)&&$appuser->isInRole("$AVANCES")) { ?>
<script type="text/javascript">
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtIdOrdenx1" );
    	var txrequerida = $( "#txtIdOrdenx1requerida" );
	var txtAvance = $( "#txtAvance" );
	var txtAvanceObs = $( "#txtAvanceObs" );
	txtAvance.keyup(checktxtAvance);
	txtAvance.blur(checktxtAvance);
	var allFields = $( [] ).add( txtAvance ).add( txtAvanceObs );
	var tips = $( ".validateTips" );
	var otAvancesCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtAvance() {
		var percent = parseFloat(txtAvance.val());
		if ( !isNaN(percent) && (percent >=0 && percent <= 100)) {
			txtAvance.val(percent);
			txtAvance.removeClass( "ui-state-error" );
			updateTips("Diligencie los siguientes datos.");
			return true;
		} else {
			txtAvance.addClass( "ui-state-error" );
			updateTips( "Ingrese un valor entre 0 y 100 para % Avance." );
			return false;
		}
	}

	function checktxtAvanceObs() {
		if ( txtAvanceObs.val().length > 0) {
			return true;
		} else {
			txtAvanceObs.addClass( "ui-state-error" );
			updateTips( "Debe ingresar las observaciones." );
			return false;
		}
	}

	$( "#ot-avances" ).dialog({
		autoOpen: false,
		height: 320,
		width: 550,
		modal: true,
		buttons: {
			"Guardar": function() {
				var bValid = true;
				allFields.removeClass( "ui-state-error" );

				bValid = bValid && checktxtAvance();
				bValid = bValid && checktxtAvanceObs();

				if (otAvancesCtrl && bValid ) {
					otAvancesCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=avance"+
							"&txtId="+txtId.val() +
							"&txtAvance="+txtAvance.val() +
							"&txtAvanceObs="+encodeURIComponent(txtAvanceObs.val())+
                            			"&requerida="+txrequerida.val(),
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								loadCurrentTab($("#tabs").tabs('option', 'active'));
							}
							else{
								otAvancesCtrl = true;
								updateTips(returnData);
							}
						}
					});
				}
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
			}
		},
		open: function() {
			otAvancesCtrl = true;
			allFields.val( "" ).removeClass( "ui-state-error" );
			updateTips("Diligencie los siguientes datos");
			txtAvance.val("");
			txtAvanceObs.val("");
		}
	});

	$( "#nuevo-avance" )
		.button({icons: {primary: 'ui-icon-comment'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-avances" ).dialog( "open" );
		});
});
</script>
<div id="ot-avances" title="NUEVO AVANCE">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<fieldset>
		<input type="hidden" id="txtIdOrdenx1" name="txtIdOrdenx1" value="<?php echo $id; ?>"/>
        	<input type="hidden" id="txtIdOrdenx1requerida" name="txtIdOrdenx1requerida" value="<?php echo $fecha_requerida; ?>"/>
		<table class="data-ro" id="frm-avance-header">
			<tr>
				<td class="title"><span class="required">*</span>% Avance:</td><td class="field"><input type="text" id="txtAvance" name="txtAvance" value="" class="wideFormInputText" tabindex="1" title=""/></td>
			</tr>
			<tr>
				<td class="title"><span class="required">*</span>Observaciones:</td><td class="field"><textarea name='txtAvanceObs' id="txtAvanceObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
			</tr>
			</tr>
		</table>
	</fieldset>
</div>
<button id="nuevo-avance">Avance</button>
<?php } ?>