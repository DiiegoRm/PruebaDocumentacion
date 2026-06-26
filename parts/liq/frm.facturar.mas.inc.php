<?php if($appuser->isInRole("$FACTURA")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtFrmFactura = $( "#txtFrmFactura6" );
	var tips = $( ".validateTips" );
	var csFacturarMasCtrl = true;

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

	$( "#cs-facturar-mas" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (csFacturarMasCtrl&&checktxtFrmFactura()) {
					csFacturarMasCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/cs.acciones.inc.php",
						data: "mode=facturarmas"+formSerialize()+
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
			csFacturarMasCtrl = true;
			txtFrmFactura.val("");
			tips.text("Esta seguro que desea Subir Factura para las liquidaciones seleccionadas?.");
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
function checkFacturarLiq() {
	$check = 0;
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
				$check = 1;
				var inputid = elements[i].id.split("_");
				if (inputid[1]!='<?php echo $LIQ_ST_CAUSADA?>') {
					$check = 2;
					break;
				}
			}
		}
	}
	return $check;
}
function openFacturarLiq() {
	var val = checkFacturarLiq();
	if (val === 0){
		alert("Debe seleccionar minimo un registro para subir factura!");
	} else if (val === 1) {
		$( "#cs-facturar-mas" ).dialog( "open" );
	} else {
		alert("Solo puede Subir Factura para las liquidaciones en estado 'Causada'!");
	}
}
</script>
<div id="cs-facturar-mas" title="SUBIR FACTURA LIQUIDACION">
	<p class="validateTips">Esta seguro que desea <b>Subir Factura</b> para las liquidaciones seleccionadas?.</p>
	<label class="formLabel" for="txtFrmFactura5">Numero Factura<span class="required">*</span></label>
	<input type="text" id="txtFrmFactura6" name="txtFrmFactura6" value="" maxlength="20" class="wideFormInputText"/>
</div>
<?php } ?>