<?php
//echo "$idestadovb == $VB_ST_APROBACION||$idestadovb == $VB_ST_APLAZADA";
if($appuser->isAdmin() || (($idestadoliq == $LIQ_ST_ENCONCILIACION||$idestadoliq == $LIQ_ST_RECHAZADA)&&$appuser->isInRole("$CANCELAR_LIQUIDACION_SIN_CAUSAR"))) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmLiqId7" );
	var txtFrmLiqObs = $( "#txtFrmLiqObs7" );
	var tips = $( ".validateTips" );
	var csCancelarCtrl = true;

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

	$( "#cs-cancelar" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (csCancelarCtrl&&checktxtFrmLiqObs()) {
					csCancelarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/cs.acciones.inc.php",
						data: "mode=cancelar"+
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
			csCancelarCtrl = true;
			txtFrmLiqObs.val("");
			tips.text("Diligencie los siguientes datos.");
		}
	});

	$( "#cancelar-cs" )
		.button({icons: {primary: 'ui-icon-cancel'}})
		.click(function(event) {
			event.preventDefault();
			$( "#cs-cancelar" ).dialog( "open" );
		});
});
</script>
<div id="cs-cancelar" title="CANCELAR LIQUIDACION">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<input type="hidden" id="txtFrmLiqId7" name="txtFrmLiqId7" value="<?php echo $id; ?>"/>
	<label class="formLabel" for="txtFrmLiqObs7">Observaciones<span class="required">*</span></label>
	<textarea name='txtFrmLiqObs7' id="txtFrmLiqObs7" class="formTextArea" style="width:320px;max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea>
</div>
<button id="cancelar-cs">Cancelar</button>
<?php } ?>