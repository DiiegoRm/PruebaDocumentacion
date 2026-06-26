<?php if((!$appuser->isInState($idestadoot,"$OT_ST_ENCREACION,$OT_ST_TERMINADA,$OT_ST_CERRADA,$OT_ST_ENAPROBACIONECONOMICA,$OT_ST_REGISTRADA"))&&$appuser->isInRole("$AJUSTAR_CRONOGRAMA")) { ?>


<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmOTIdx4" );
	var txrequerida = $( "#txtFrmOTIdx4requerida" );
	var txt1xObs = $( "#txt1xObs" );
	var tips = $( ".validateTips" );
	var otReprogramarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	function checktxt1xObs() {
		if ( txt1xObs.val().length > 0) {
			return true;
		} else {
			txt1xObs.addClass( "ui-state-error" );
			updateTips( "Debe ingresar las observaciones." );
			return false;
		}
	}
	$( "#ot-reprogramar" ).dialog({
		autoOpen: false,
		height: 300,
		width: 550,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (otReprogramarCtrl && checktxt1xObs() ) {
					otReprogramarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=reprogramar"+
							"&id="+txtId.val()+
                            			"&requerida="+txrequerida.val()+
							"&txtObs="+encodeURIComponent(txt1xObs.val()),
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
			otReprogramarCtrl = true;
			txt1xObs.removeClass( "ui-state-error" );
			tips.text("Esta seguro que desea Reprogramar la Orden?.");
		}
	});

	$( "#reprogramar-ot" )
		.button({icons: {primary: 'ui-icon-calendar'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-reprogramar" ).dialog( "open" );
		});
});
</script>
<div id="ot-reprogramar" title="REPROGRAMAR ORDEN">
	<p class="validateTips">Esta seguro que desea <b>Reprogramar</b> la Orden?.</p>
	<input type="hidden" id="txtFrmOTIdx4" name="txtFrmOTIdx4" value="<?php echo $id; ?>"/>
    <input type="hidden" id="txtFrmOTIdx4requerida" name="txtFrmOTIdx4requerida" value="<?php echo $fecha_requerida; ?>"/>
	<table class="data-ro" id="reprogramar-ot-header">
		<tr>
			<td class="title"><span class="required">*</span>Observaciones:</td><td class="field"><textarea name='txt1xObs' id="txt1xObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>
</div>
<button id="reprogramar-ot">Reprogramar</button>
<?php } ?>