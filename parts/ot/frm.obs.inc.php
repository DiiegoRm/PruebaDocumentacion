<script type="text/javascript">
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtIdOrdenx3" );
	var txtNewObs = $( "#txtNewObs" );
	var allFields = $( [] ).add( txtNewObs );
	var tips = $( ".validateTips" );
	var otObsCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtNewObs() {
		if ( txtNewObs.val().length > 0) {
			return true;
		} else {
			txtNewObs.addClass( "ui-state-error" );
			updateTips( "Debe ingresar las observaciones." );
			return false;
		}
	}

	$( "#ot-obs" ).dialog({
		autoOpen: false,
		height: 320,
		width: 550,
		modal: true,
		buttons: {
			"Guardar": function() {
				allFields.removeClass( "ui-state-error" );
				if (otObsCtrl && checktxtNewObs() ) {
					otObsCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=obs"+
							"&txtId="+txtId.val() +
							"&txtNewObs="+encodeURIComponent(txtNewObs.val()),
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
			otObsCtrl = true;
			allFields.val( "" ).removeClass( "ui-state-error" );
			updateTips("Diligencie los siguientes datos.");
			txtNewObs.val("");
		}
	});

	$( "#obs-ot" )
		.button({icons: {primary: 'ui-icon-note'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-obs" ).dialog( "open" );
		});
});
</script>
<div id="ot-obs" title="ADICIOANAR OBSERVACION">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<fieldset>
		<input type="hidden" id="txtIdOrdenx3" name="txtIdOrdenx3" value="<?php echo $id; ?>"/>
		<table class="data-ro" id="obs-ot-header">
			<tr>
				<td class="title"><span class="required">*</span>Observaciones:</td><td class="field"><textarea name='txtNewObs' id="txtNewObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
			</tr>
		</table>
	</fieldset>
</div>
<button id="obs-ot">Observaciones</button>