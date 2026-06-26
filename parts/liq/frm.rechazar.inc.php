<?php
//echo "$idestadovb == $VB_ST_APROBACION||$idestadovb == $VB_ST_APLAZADA";
if(($idestadoliq == $LIQ_ST_ENCONCILIACION||$idestadoliq == $LIQ_ST_GESTIONRESERVAS)&&$appuser->isInRole("$APROBAR_LIQUID,$APROBAR_RESERVAS")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmLiqId2" );
	var txtFrmLiqObs = $( "#txtFrmLiqObs2" );
	var tips = $( ".validateTips" );
	var csRechazarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtFrmLiqObs() {
		if ( txtFrmLiqObs.val().length > 0) {
			return true;
		} else {
			txtFrmLiqObs.addClass( "ui-state-error" );
			updateTips( "Ingrese sus observaciones." );
			return false;
		}
	}

	$( "#cs-rechazar" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (csRechazarCtrl&&checktxtFrmLiqObs() ) {
					csRechazarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/cs.acciones.inc.php",
						data: "mode=rechazar"+
							"&id="+txtId.val() +
							"&obs="+encodeURI(txtFrmLiqObs.val()),
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
			csRechazarCtrl = true;
			txtFrmLiqObs.val("");
			tips.text("Diligencie los siguientes datos.");
		}
	});

	$( "#rechazar-cs" )
		.button({icons: {primary: 'ui-icon-circle-close'}})
		.click(function(event) {
			event.preventDefault();
			$( "#cs-rechazar" ).dialog( "open" );
		});
});
</script>
<div id="cs-rechazar" title="RECHAZAR LIQUIDACION">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<input type="hidden" id="txtFrmLiqId2" name="txtFrmLiqId2" value="<?php echo $id; ?>"/>
	<input type="hidden" id="txtTipoVB2" name="txtTipoVB2" value="<?php echo $idtipovb; ?>"/>
	<label class="formLabel" for="txtFrmLiqObs2">Observaciones<span class="required">*</span></label>
	<textarea name='txtFrmLiqObs2' id="txtFrmLiqObs2" class="formTextArea" style="width:320px;max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea>
</div>
<button id="rechazar-cs">Rechazar</button>
<?php } ?>