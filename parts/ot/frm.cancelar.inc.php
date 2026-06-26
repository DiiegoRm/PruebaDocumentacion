<?php if($appuser->isAdmin() || (($appuser->uid == $create_user)&&$hasLiq==0&&$appuser->isInState($idestadoot,"$OT_PUEDE_CANCELAR,$OT_ST_APLAZADA,$OT_ST_SOLICITUDCANCELACION"))) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmOTIdx2" );
	var txt3xObs = $( "#txt3xObs" );
	var tips = $( ".validateTips" );
	var otCancelarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	function checktxt3xObs() {
		if ( txt3xObs.val().length > 0) {
			return true;
		} else {
			txt3xObs.addClass( "ui-state-error" );
			updateTips( "Debe ingresar las observaciones." );
			return false;
		}
	}

	$( "#ot-cancelar" ).dialog({
		autoOpen: false,
		height: 300,
		width: 550,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (otCancelarCtrl && checktxt3xObs() ) {
					otCancelarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=cancelar"+
							"&id="+txtId.val()+
							"&txtObs="+encodeURIComponent(txt3xObs.val()),
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
			otCancelarCtrl = true;
			tips.text("Esta seguro que desea Cancelar la Orden?.");
		}
	});

	$( "#cancelar-ot" )
		.button({icons: {primary: 'ui-icon-circle-close'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-cancelar" ).dialog( "open" );
		});
});
</script>
<div id="ot-cancelar" title="CANCELAR ORDEN">
	<p class="validateTips">Esta seguro que desea <b>Cancelar</b> la Orden?.</p>
	<input type="hidden" id="txtFrmOTIdx2" name="txtFrmOTIdx2" value="<?php echo $id; ?>"/>
	<table class="data-ro" id="cancelar-ot-header">
		<tr>
			<td class="title"><span class="required">*</span>Observaciones:</td><td class="field"><textarea name='txt3xObs' id="txt3xObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>
</div>
<button id="cancelar-ot">Cancelar Orden</button>
<?php } ?>