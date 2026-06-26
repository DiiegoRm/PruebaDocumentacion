<?php
//echo "$idestadovb == $VB_ST_APROBACION||$idestadovb == $VB_ST_APLAZADA";
if($idestadoliq == $LIQ_ST_APROBADA && $hasFecha&&$appuser->isInRole("$ASIGNAR_QUITAR_MES_CAUSADO")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmLiqId5" );
	var txtFrmPedido = $( "#txtFrmPedido5" );
	var txtFrmMigo = $( "#txtFrmMigo5" );
	var tips = $( ".validateTips" );
	var csCausarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtFrmPedido() {
		if ( txtFrmPedido.val().length > 0) {
			txtFrmPedido.removeClass( "ui-state-error" );
			return true;
		} else {
			txtFrmPedido.addClass( "ui-state-error" );
			updateTips( "Ingrese un valor para Pedido." );
			return false;
		}
	}
	function checktxtFrmMigo() {
		if ( txtFrmMigo.val().length > 0) {
			txtFrmMigo.removeClass( "ui-state-error" );
			return true;
		} else {
			txtFrmMigo.addClass( "ui-state-error" );
			updateTips( "Ingrese un valor para Migo." );
			return false;
		}
	}

	$( "#cs-causar" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (csCausarCtrl && checktxtFrmPedido() && checktxtFrmMigo() ) {
					csCausarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/cs.acciones.inc.php",
						data: "mode=causar"+
							"&id="+txtId.val() +
							"&ped="+txtFrmPedido.val()+
							"&migo="+txtFrmMigo.val(),
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
			csCausarCtrl = true;
			txtFrmPedido.val("");
			txtFrmMigo.val("");
			tips.text("Diligencie los siguientes datos.");
		}
	});

	$( "#causar-cs" )
		.button()
		.click(function(event) {
			event.preventDefault();
			$( "#cs-causar" ).dialog( "open" );
		});
});
</script>
<div id="cs-causar" title="CAUSAR LIQUIDACION">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<input type="hidden" id="txtFrmLiqId5" name="txtFrmLiqId5" value="<?php echo $id; ?>"/>
	<label class="formLabel" for="txtFrmPedido5">Pedido<span class="required">*</span></label>
	<input type="text" id="txtFrmPedido5" name="txtFrmPedido5" value="" class="wideFormInputText"/>
	<label class="formLabel" for="txtFrmMigo5">MIGO<span class="required">*</span></label>
	<input type="text" id="txtFrmMigo5" name="txtFrmMigo5"  value="" class="wideFormInputText"/>
</div>
<button id="causar-cs">Causar</button>
<?php } ?>