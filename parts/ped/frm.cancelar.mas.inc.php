<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var tips = $( ".validateTips" );
	var pedCancelarMasCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#ped-cancelar-mas" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (pedCancelarMasCtrl) {
					pedCancelarMasCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ped.acciones.inc.php",
						data: "mode=cancelarmas"+formSerialize(),
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
			pedCancelarMasCtrl = true;
			tips.text("Esta seguro que desea Cancelar los pedidos seleccionados?.");
		}
	});
	function formSerialize(){
		var attrs = "";
		with (document.frmSubmit) {
				for (var i=0; i < elements.length; i++) {
						if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
							attrs += "&ped_"+i+"="+elements[i].value;
						}
				}
		}
		return attrs;
	}
});
function checkCancelar() {
	$check = 0;
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
				$check = 1;
				var inputid = elements[i].id.split("_");
				if (inputid[1]!='<?php echo $PED_ST_PENDIENTE?>' && inputid[1]!='<?php echo $PED_ST_GESTIONADO?>') {
					$check = 2;
					break;
				}
			}
		}
	}
	return $check;
}
function openCancelarPed() {
	var val = checkCancelar();
	if (val === 0){
		alert("Debe seleccionar minimo un registro para cancelar!");
	} else if (val === 1) {
		$( "#ped-cancelar-mas" ).dialog( "open" );
	} else {
		alert("Solo puede Cancelar los pedidos en estado Pendiente o Gestionado!");
	}
}
</script>
<div id="ped-cancelar-mas" title="CANCELAR PEDIDO(S)">
	<p class="validateTips">Esta seguro que desea <b>Cancelar</b> los pedidos seleccionados?.</p>
</div>