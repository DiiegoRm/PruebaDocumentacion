<?php if($appuser->isInState($idestadoot,$OT_ST_ENREGISTRO)) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmOTIdx7" );
	var txt15xObs = $( "#txt15xObs" );
	var tips = $( ".validateTips" );
	var otSoportesCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	function checktxt15xObs() {
		if ( txt15xObs.val().length > 0) {
			return true;
		} else {
			txt15xObs.addClass( "ui-state-error" );
			updateTips( "Debe ingresar las observaciones." );
			return false;
		}
	}
	$( "#ot-soportes" ).dialog({
		autoOpen: false,
		height: 300,
		width: 550,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (otSoportesCtrl && checktxt15xObs() ) {
					otSoportesCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=soportes"+
							"&id="+txtId.val()+
							"&txtObs="+encodeURIComponent(txt15xObs.val()),
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
			otSoportesCtrl = true;
			txt15xObs.removeClass( "ui-state-error" );
			tips.text("Esta seguro que desearegresar la Orden por Soportes No OK?.");
		}
	});

	$( "#soportes-ot" )
		.button({icons: {primary: 'ui-icon-seek-first'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-soportes" ).dialog( "open" );
		});
});
</script>
<div id="ot-soportes" title="REGISTRO: SOPORTES NO OK">
	<p class="validateTips">Esta seguro que desea regresar la Orden por <b>Soportes No OK</b>?.</p>
	<input type="hidden" id="txtFrmOTIdx7" name="txtFrmOTIdx7" value="<?php echo $id; ?>"/>
	<table class="data-ro" id="soportes-header">
		<tr>
			<td class="title"><span class="required">*</span>Observaciones:</td><td class="field"><textarea name='txt15xObs' id="txt15xObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>
</div>
<button id="soportes-ot">Soportes no OK</button>
<?php } ?>