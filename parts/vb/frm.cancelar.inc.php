<?php if($appuser->isAdmin()||($idestadovb == $VB_ST_APROBACION||$idestadovb == $VB_ST_APLAZADA||$idestadovb == $VB_ST_REVISION)) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtIdOrdenx8" );
	var txtFrm2Obs = $( "#txtFrm2Obs" );
	var tips = $( ".validateTips" );
	var vbCancelarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtFrm2Obs() {
		if ( txtFrm2Obs.val().length > 0) {
			return true;
		} else {
			txtFrm2Obs.addClass( "ui-state-error" );
			updateTips( "Ingrese sus observaciones." );
			return false;
		}
	}

	$( "#vb-cancelar" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Guardar": function() {
				if (vbCancelarCtrl && checktxtFrm2Obs() ) {
					vbCancelarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/vb.acciones.inc.php",
						data: "mode=cancelar"+
							"&txtId="+txtId.val() +
							"&txtFrm2Obs="+encodeURI(txtFrm2Obs.val()),
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								document.location.href="?menu=<?php echo getMenu()?>";
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
			vbCancelarCtrl = true;
			txtFrm2Obs.val("");
			tips.text("Diligencie los siguientes datos.");
		}
	});

	$( "#cancelar-vb" )
		.button({icons: {primary: 'ui-icon-circle-close'}})
		.click(function(event) {
			event.preventDefault();
			$( "#vb-cancelar" ).dialog( "open" );
		});
});
</script>
<div id="vb-cancelar" title="CANCELAR VIABILIDAD">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<input type="hidden" id="txtIdOrdenx8" name="txtIdOrdenx8" value="<?php echo $id; ?>"/>
	<table class="data-ro" id="vb-cancelar">
		<tr>
			<td class="title"><span class="required">**</span>Observaciones:</td>
			<td class="input"><textarea name='txtFrm2Obs' id="txtFrm2Obs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>	
</div>
<button id="cancelar-vb">Cancelar</button>
<?php } ?>