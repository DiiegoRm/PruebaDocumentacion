<?php if($idestadovb == $VB_ST_APROBACION||$idestadovb == $VB_ST_REVISION) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtIdOrdenx4" );
	var txtFrm1Obs = $( "#txtFrm1Obs" );
	var tips = $( ".validateTips" );
	var vbAplazarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtFrm1Obs() {
		if ( txtFrm1Obs.val().length > 0) {
			return true;
		} else {
			txtFrm1Obs.addClass( "ui-state-error" );
			updateTips( "Ingrese sus observaciones." );
			return false;
		}
	}

	$( "#vb-aplazar" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Guardar": function() {
				if (vbAplazarCtrl && checktxtFrm1Obs() ) {
					vbAplazarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/vb.acciones.inc.php",
						data: "mode=aplazar"+
							"&txtId="+txtId.val() +
							"&txtFrm1Obs="+encodeURI(txtFrm1Obs.val()),
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
			vbAplazarCtrl = true;
			txtFrm1Obs.val("");
			tips.text("Diligencie los siguientes datos.");
		}
	});

	$( "#aplazar-vb" )
		.button({icons: {primary: 'ui-icon-calendar'}})
		.click(function(event) {
			event.preventDefault();
			$( "#vb-aplazar" ).dialog( "open" );
		});
});
</script>
<div id="vb-aplazar" title="APLAZAR VIABILIDAD">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<input type="hidden" id="txtIdOrdenx4" name="txtIdOrdenx4" value="<?php echo $id; ?>"/>
	<table class="data-ro" id="vb-aplazar">
		<tr>
			<td class="title"><span class="required">**</span>Observaciones:</td>
			<td class="input"><textarea name='txtFrm1Obs' id="txtFrm1Obs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>	
</div>
<button id="aplazar-vb">Aplazar</button>
<?php } ?>