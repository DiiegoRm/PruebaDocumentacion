<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var tips = $( ".validateTips" );
	var pedContabilizarMasCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#res-contabilizar-mas" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (pedContabilizarMasCtrl) {
					pedContabilizarMasCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/res.acciones.inc.php",
						data: "mode=contabilizarmas"+formSerialize(),
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
			pedContabilizarMasCtrl = true;
			tips.text("Esta seguro que desea Contabilizar las reservas seleccionadas?");
		}
	});
	function formSerialize(){
		var attrs = "";
		with (document.frmSubmit) {
				for (var i=0; i < elements.length; i++) {
						if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
									attrs += "&res_"+i+"="+elements[i].value;
						}
				}
		}
		return attrs;
	}
});
function openContabilizar() {
	if (checkSelection() == 1){
				$( "#res-contabilizar-mas" ).dialog( "open" );
	}else{
		alert("Debe seleccionar minimo un registro para contabilizar");
	}
}
</script>
<div id="res-contabilizar-mas" title="CONTABILIZAR RESERVA(S)">
	<p class="validateTips">Esta seguro que desea <b>Contailizar</b> las reservas seleccionadas?.</p>
</div>
