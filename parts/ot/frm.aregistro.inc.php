<?php if($appuser->isInState($idestadoot,$OT_ST_TERMINADA)&&!$appuser->isInState($idtipoot,"$OT_TIPO_DESIGN,$OT_TIPO_DIAGNOSTICO")&&$appuser->isInRole("$CARGAR_REGISTRO")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmPedId" );
	var txt34xObs = $( "#txt34xObs" );
	var tips = $( ".validateTips" );
	var otARegistroCtrl = true;
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	function checktxt34xObs() {
		if ( txt34xObs.val().length > 0) {
			return true;
		} else {
			txt34xObs.addClass( "ui-state-error" );
			updateTips( "Debe ingresar las observaciones." );
			return false;
		}
	}

	$( "#ot-aregistro" ).dialog({
		autoOpen: false,
		height: 300,
		width: 550,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (otARegistroCtrl&&checktxt34xObs()) {
					otARegistroCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=registro"+
							"&id="+txtId.val()+
						"&txtObs="+encodeURIComponent(txt34xObs.val()),
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
			otARegistroCtrl = true;
			tips.text("Esta seguro que desea Enviar a Registro la Orden?.");
		}
	});

	$( "#aregistro-ot" )
		.button({icons: {primary: 'ui-icon-bookmark'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-aregistro" ).dialog( "open" );
		});
});
</script>
<div id="ot-aregistro" title="REPORTAR REGISTRO">
	<p class="validateTips">Esta seguro que desea <b>Enviar a Registro</b> la Orden?.</p>
	<input type="hidden" id="txtFrmPedId" name="txtFrmPedId" value="<?php echo $id; ?>"/>
	<table class="data-ro" id="ot-aregistro-header">
		<tr>
			<td class="title"><span class="required">*</span>Observaciones:</td><td class="field"><textarea name='txt34xObs' id="txt34xObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>
</div>
<button id="aregistro-ot" <?php echo (hasVal($registro)&&($regfiles&&$registro!='NADA'))?"":"disabled='disabled'" ?> >Reportar Registro</button>
<?php } ?>