<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var tips = $( ".validateTips" );
	var pedGestionarMasCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#ped-gestionar-mas" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (pedGestionarMasCtrl) {
					pedGestionarMasCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ped.acciones.inc.php",
						data: "mode=gestionarmas"+formSerialize(),
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
			pedGestionarMasCtrl = true;
			tips.text("Esta seguro que desea Gestionar los pedidos seleccionados?.");
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
function checkGestionar() {
	$check = 0;
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
				$check = 1;
				var inputid = elements[i].id.split("_");
				if (inputid[1]!='<?php echo $PED_ST_PENDIENTE?>') {
					$check = 2;
					break;
				}
			}
		}
	}
	return $check;
}
function openGestionarPed() {
	var val = checkGestionar();
	if (val === 0){
		alert("Debe seleccionar minimo un registro para gestionar!");
	} else if (val === 1) {
		$( "#ped-gestionar-mas" ).dialog( "open" );
	} else {
		alert("Solo puede Gestionar los pedidos en estado Pendiente!");
	}
}
</script>
<div id="ped-gestionar-mas" title="GESTIONAR PEDIDO(S)">
	<p class="validateTips">Esta seguro que desea <b>Gestionar</b> los pedidos seleccionados?.</p>
</div>