<?php if($appuser->uid != $create_user&&$hasLiq==0&&$appuser->isInState($idestadoot,"$OT_PUEDE_CANCELAR")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmOTIdx6" );
	var txt13xObs = $( "#txt13xObs" );
	var tips = $( ".validateTips" );
	var otSolCancelacionCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	function checktxt13xObs() {
		if ( txt13xObs.val().length > 0) {
			return true;
		} else {
			txt13xObs.addClass( "ui-state-error" );
			updateTips( "Debe ingresar las observaciones." );
			return false;
		}
	}

	$( "#ot-solcancelacion" ).dialog({
		autoOpen: false,
		height: 300,
		width: 550,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (otSolCancelacionCtrl && checktxt13xObs() ) {
					otSolCancelacionCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=solcancelacion"+
							"&id="+txtId.val()+
							"&txtObs="+encodeURIComponent(txt13xObs.val()),
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
			otSolCancelacionCtrl = true;
			tips.text("Esta seguro que desea Solicitar Cancelacion a la Orden.");
		}
	});

	$( "#solcancelacion-ot" )
		.button({icons: {primary: 'ui-icon-circle-close'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-solcancelacion" ).dialog( "open" );
		});
});
</script>
<div id="ot-solcancelacion" title="SOLICITAR CANCELACION">
	<p class="validateTips">Esta seguro que desea <b>Solicitar Cancelacion</b> a la Orden?.</p>
	<input type="hidden" id="txtFrmOTIdx6" name="txtFrmOTIdx6" value="<?php echo $id; ?>"/>
	<table class="data-ro" id="solcancelacion-ot-header">
		<tr>
			<td class="title"><span class="required">*</span>Observaciones:</td><td class="field"><textarea name='txt13xObs' id="txt13xObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>
</div>
<button id="solcancelacion-ot">Solicitar Cancelacion</button>
<?php } ?>