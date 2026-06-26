<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var tips = $( ".validateTips" );
	var solAprobarMasCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#sol-aprobar-mas" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (solAprobarMasCtrl) {
					solAprobarMasCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/sol.acciones.inc.php",
						data: "mode=aprobarmas"+formSerialize(),
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
			solAprobarMasCtrl = true;
			tips.text("Esta seguro que desea Aprobar las solictudes seleccionadas?.");
		}
	});

	function formSerialize(){
		var attrs = "";
		with (document.frmSubmit) {
				for (var i=0; i < elements.length; i++) {
						if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
							attrs += "&sol_"+i+"="+elements[i].value;
						}
				}
		}
		return attrs;
	}
});
function checkAprobarSol() {
	$check = 0;
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
				$check = 1;
				var inputid = elements[i].id.split("_");
				if (inputid[1]!='<?php echo $SOL_ST_SOLICITADA?>') {
					$check = 2;
					break;
				}
			}
		}
	}
	return $check;
}
function openAprobarSol() {
	var val = checkAprobarSol();
	if (val === 0){
		alert("Debe seleccionar minimo un registro para aprobar!");
	} else if (val === 1) {
		$( "#sol-aprobar-mas" ).dialog( "open" );
	} else {
		alert("Solo puede Aprobar las Solicitudes en estado 'Solicitada'!");
	}
}
</script>
<div id="sol-aprobar-mas" title="APROBAR SOLICITUDES">
	<p class="validateTips">Esta seguro que desea <b>Aprobar</b> las solictudes seleccionadas?.</p>
</div>