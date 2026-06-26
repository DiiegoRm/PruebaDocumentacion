<?php if($appuser->isInRole("$ADMINISTRACION")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var tips = $( ".validateTips" );
	var csTipoMasCtrl = true;
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#cs-tipo-mas" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (csTipoMasCtrl) {
					csTipoMasCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/cs.acciones.inc.php",
						data: "mode=tipo"+formSerialize(),
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
			csTipoMasCtrl = true;
			tips.text("Esta seguro que desea cambiar el tipo de la liquidacion seleccionadas?.");
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
function checkTipoLiq() {
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
function openTipoLiq() {
	var val = checkTipoLiq();
	if (val === 0){
		alert("Debe seleccionar minimo un registro para modificar el tipo de la liquidacion!");
	} else {
		$( "#cs-tipo-mas" ).dialog( "open" );
	}
}
</script>
<div id="cs-tipo-mas" title="MODIFICAR TIPO DE LIQUIDACION">
	<p class="validateTips">Esta seguro que desea modificar el <b>Tipo</b> de la liquidacion seleccionadas?.</p>
</div>
<?php } ?>