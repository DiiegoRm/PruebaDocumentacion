<?php if($appuser->isInRole("$ASIGNAR_QUITAR_MES_CAUSADO")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtFrmPedido = $( "#txtFrmPedido5" );
	var txtFrmMigo = $( "#txtFrmMigo5" );
	var tips = $( ".validateTips" );
	var csCausarMasCtrl = true;

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

	$( "#cs-causar-mas" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (csCausarMasCtrl && checktxtFrmPedido() && checktxtFrmMigo() ) {
					csCausarMasCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/cs.acciones.inc.php",
						data: "mode=causarmas"+formSerialize()+
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
			csCausarMasCtrl = true;
			txtFrmPedido.val("");
			txtFrmMigo.val("");
			tips.text("Esta seguro que desea Causar las liquidaciones seleccionadas?.");
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
function checkCausarLiq() {
	$check = 0;
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
				$check = 1;
				var inputid = elements[i].id.split("_");
				if (inputid[1]!='<?php echo $LIQ_ST_APROBADA?>' || inputid[2].length === 0) {
					$check = 2;
					break;
				}
			}
		}
	}
	return $check;
}
function openCausarLiq() {
	var val = checkCausarLiq();
	if (val === 0){
		alert("Debe seleccionar minimo un registro para causar!");
	} else if (val === 1) {
		$( "#cs-causar-mas" ).dialog( "open" );
	} else {
		alert("Solo puede Causar las liquidaciones en estado 'Aprobada' y con Fecha de Causacion Asignada!");
	}
}
</script>
<div id="cs-causar-mas" title="CAUSAR LIQUIDACIONES">
	<p class="validateTips">Esta seguro que desea <b>Causar</b> las liquidaciones seleccionadas?.</p>
	<label class="formLabel" for="txtFrmPedido5">Pedido<span class="required">*</span></label>
	<input type="text" id="txtFrmPedido5" name="txtFrmPedido5" value="" class="wideFormInputText"/>
	<label class="formLabel" for="txtFrmMigo5">MIGO<span class="required">*</span></label>
	<input type="text" id="txtFrmMigo5" name="txtFrmMigo5"  value="" class="wideFormInputText"/>
</div>
<?php } ?>