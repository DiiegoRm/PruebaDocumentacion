<?php
//echo "$idestadovb == $VB_ST_APROBACION||$idestadovb == $VB_ST_APLAZADA";
if($idestadoliq == $LIQ_ST_CAUSADA&&$appuser->isInRole("$FACTURA")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmLiqId6" );
	var txtFrmFactura = $( "#txtFrmFactura6" );
	var tips = $( ".validateTips" );
	var csFacturarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtFrmFactura() {
		if ( txtFrmFactura.val().length > 0) {
			txtFrmFactura.removeClass( "ui-state-error" );
			return true;
		} else {
			txtFrmFactura.addClass( "ui-state-error" );
			updateTips( "Ingrese un valor para Pedido." );
			return false;
		}
	}

	$( "#cs-facturar" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (csFacturarCtrl&&checktxtFrmFactura()) {
					csFacturarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/cs.acciones.inc.php",
						data: "mode=facturar"+
							"&id="+txtId.val() +
							"&fact="+txtFrmFactura.val(),
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
			csFacturarCtrl = true;
			txtFrmFactura.val("");
			tips.text("Diligencie los siguientes datos.");
		}
	});

	$( "#facturar-cs" )
		.button()
		.click(function(event) {
			event.preventDefault();
			$( "#cs-facturar" ).dialog( "open" );
		});
});
</script>
<div id="cs-facturar" title="SUBIR FACTURA LIQUIDACION">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<input type="hidden" id="txtFrmLiqId6" name="txtFrmLiqId6" value="<?php echo $id; ?>"/>
	<label class="formLabel" for="txtFrmFactura5">Numero Factura<span class="required">*</span></label>
	<input type="text" id="txtFrmFactura6" name="txtFrmFactura6" value="" maxlength="20" class="wideFormInputText"/>
</div>
<button id="facturar-cs">Subir Factura</button>
<?php } ?>