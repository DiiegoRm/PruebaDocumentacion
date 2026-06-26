<?php
if($appuser->isInRole("$ADMINISTRACION")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$("#txtFechaPago").datepicker({minDate: 0,dateFormat: 'yy-mm-dd'});

	var txtFechaPago = $("#txtFechaPago");
	var txtPedido = $('#txtPedido');
	var tips = $( ".validateTips" );
	var csPagoVbMasCtrl = true;
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtFechaPago() {
		if ( txtFechaPago.val() != "") {
			return true;
		} else {
			txtFechaPago.addClass( "ui-state-error" );
			updateTips("Ingrese la fecha de pago.");
			return false;
		}
	}

	function checktxtPedido() {
		if ( txtPedido.val() != "") {
			return true;
		} else {
			txtPedido.addClass( "ui-state-error" );
			updateTips("Ingrese el pedido.");
			return false;
		}
	}

	$( "#cs-pago-mas" ).dialog({
		autoOpen: false,
		height: 350,
		width: 500,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (csPagoVbMasCtrl && checktxtFechaPago() && checktxtPedido()) {
					csPagoVbMasCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/vb.acciones.inc.php",
						data: "mode=pago"+formSerialize() + "&txtFechaPago=" + txtFechaPago.val() + "&txtPedido=" + txtPedido.val(),
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
			csPagoVbMasCtrl = true;
			tips.text("Esta seguro que desea cambiar la viabilidad seleccionadas?.");
		}
	});
	function formSerialize(){
		var attrs = "";
		with (document.frmSubmit) {
				for (var i=0; i < elements.length; i++) {
						if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
							attrs += "&cs_"+i+"="+elements[i].value;
						}
				}
		}
		return attrs;
	}
});

function checkPagoVb() {
	$check = 0;
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
				$check = 1;
				var inputid = elements[i].id.split("_");
				if (inputid[1]!='<?php echo $LIQ_ST_GESTIONRESERVAS?>') {
					$check = 2;
					break;
				}
			}
		}
	}
	return $check;
}

function openPagoVb() {
	var val = checkPagoVb();
	if (val === 0){
		alert("Debe seleccionar minimo un registro para modificar la viabilidad!");
	} else {
		$( "#cs-pago-mas" ).dialog( "open" );
	}
}
</script>

<style>
input[type="number"] {
    background: #eaf4fd;
    color: #2e6e9e;
    font-weight: bold;
    font-size: 11px;
    width: 100%;
    height: 20px;
    border: 1px solid #c5dbec;
    border-radius: 4px;
    -o-border-radius: 4px;
    -moz-border-radius: 4px;
    -icab-border-radius: 4px;
    -khtml-border-radius: 4px;
    -webkit-border-radius: 4px;
}
#cs-pago-mas table.data-ro td.title {
    width: 0px;
}
</style>

<div id="cs-pago-mas" title="PAGOS DE VIABILIDAD">
    <form method="POST">
		<table class="data-ro">
			<p class="validateTips">Para editar la Viabilidad debe teclear la Fecha de Pago y el Pedido!.</p>
			<tr>
				<td class="title"><label class="formLabel" for="txtFechaPago">Fecha Pago:<span class="required">*</span></label></td>
				<td class="input"><input type="text" id="txtFechaPago" name="txtFechaPago" value="" maxlength="20" class="wideFormInputText" readonly="readonly"/></td>
			</tr>
			<tr>
				<td class="title"><label class="formLabel" for="txtPedido">Pedido:<span class="required">*</span></label></td>
				<td class="input"><input type="text" id="txtPedido" name="txtPedido" value="" maxlength="20" class="wideFormInputText"/></td>
			</tr>
		</table>
	</form>
</div>
<?php } ?>