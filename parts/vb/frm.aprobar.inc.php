<?php
//echo "$idestadovb == $VB_ST_APROBACION||$idestadovb == $VB_ST_APLAZADA";
if(($idestadovb == $VB_ST_APROBACION||$idestadovb == $VB_ST_APLAZADA)&&$appuser->isInRole("$APROBAR_VB")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtIdOrdenx5" );
	var txtTipoVB = $( "#txtTipoVB" );
	var txtFrm3Obs = $( "#txtFrm3Obs" );
	var tips = $( ".validateTips" );
	var vbAprobarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtFrm3Obs() {
		if ( txtFrm3Obs.val().length > 0) {
			return true;
		} else {
			txtFrm3Obs.addClass( "ui-state-error" );
			updateTips( "Ingrese sus observaciones." );
			return false;
		}
	}

	$( "#vb-aprobar" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Guardar": function() {
				if (vbAprobarCtrl && checktxtFrm3Obs() ) {
					vbAprobarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/vb.acciones.inc.php",
						data: "mode=aprobar"+
							"&txtId="+txtId.val() +
							"&txtTipoVB="+txtTipoVB.val()+
							"&txtFrm3Obs="+encodeURI(txtFrm3Obs.val()),
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
			vbAprobarCtrl = true;
			txtFrm3Obs.val("");
			tips.text("Diligencie los siguientes datos.");
		}
	});

	$( "#aprobar-vb" )
		.button({icons: {primary: 'ui-icon-circle-check'}})
		.click(function(event) {
			event.preventDefault();
			$( "#vb-aprobar" ).dialog( "open" );
		});
});
</script>
<div id="vb-aprobar" title="APROBAR VIABILIDAD">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<input type="hidden" id="txtIdOrdenx5" name="txtIdOrdenx5" value="<?php echo $id; ?>"/>
	<input type="hidden" id="txtTipoVB" name="txtTipoVB" value="<?php echo $idtipovb; ?>"/>
	<table class="data-ro" id="vb-aprobar">
		<tr>
			<td class="title"><span class="required">**</span>Observaciones:</td>
			<td class="input"><textarea name='txtFrm3Obs' id="txtFrm3Obs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>	
</div>
<button id="aprobar-vb">Aprobar</button>
<?php } ?>