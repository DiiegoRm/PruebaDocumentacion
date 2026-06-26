<?php
if($idestadosol == $SOL_ST_SOLICITADA) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmPedId" );
	var txt3xObs = $( "#txt3xObs" );
	var tips = $( ".validateTips" );
	var solRechazarCtrl = true;

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

	$( "#sol-rechazar" ).dialog({
		autoOpen: false,
		height: 300,
		width: 550,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (solRechazarCtrl && checktxt3xObs() ) {
					solRechazarCtrl = false;
						$.ajax({
							type: "POST",
							url: "callback/sol.acciones.inc.php",
							data: "mode=rechazar"+
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
			solRechazarCtrl = true;
			tips.text("Esta seguro que desea Rechazar la Solictud?.");
		}
	});

	$( "#rechazar-sol" )
		.button({icons: {primary: 'ui-icon-circle-close'}})
		.click(function(event) {
			event.preventDefault();
			$( "#sol-rechazar" ).dialog( "open" );
		});
});
</script>
<div id="sol-rechazar" title="RECHAZAR SOLICITUD">
	<p class="validateTips">Esta seguro que desea <b>Rechazar</b> la Solictud?.</p>
	<input type="hidden" id="txtFrmPedId" name="txtFrmPedId" value="<?php echo $id; ?>"/>
	<table class="data-ro" id="rechazar-sol-header">
		<tr>
			<td class="title"><span class="required">*</span>Observaciones:</td><td class="field"><textarea name='txt3xObs' id="txt3xObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>
</div>
<button id="rechazar-sol">Rechazar</button>
<?php } ?>