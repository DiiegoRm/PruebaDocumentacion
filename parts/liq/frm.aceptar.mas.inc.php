<?php if($appuser->isInRole("$APROBAR_LIQUID")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var tips = $( ".validateTips" );
	var csAceptarMasCtrl = true;
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#cs-aceptar-mas" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (csAceptarMasCtrl) {
					csAceptarMasCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/cs.acciones.inc.php",
						data: "mode=aceptarmas"+formSerialize(),
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
			$(this).removeClass('loader');
			tips.text("Esta seguro que desea Aceptar las liquidaciones seleccionadas?.");
			csAceptarMasCtrl = true;
		},
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
function checkAceptarLiq() {
	$check = 0;
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
				$check = 1;
				var inputid = elements[i].id.split("_");
				if (inputid[1]!='<?php echo $LIQ_ST_ENCONCILIACION?>') {
					$check = 2;
					break;
				}
			}
		}
	}
	return $check;
}
function openAceptarLiq() {
	var val = checkAceptarLiq();
	if (val === 0){
		alert("Debe seleccionar minimo un registro para aceptar!");
	} else if (val === 1) {
		$( "#cs-aceptar-mas" ).dialog( "open" );
	} else {
		alert("Solo puede Aceptar las liquidaciones en estado 'En Conciliacion'!");
	}
}
</script>
<div id="cs-aceptar-mas" title="ACEPTAR LIQUIDACIONES" class="loader">
	<p class="validateTips">Esta seguro que desea <b>Aceptar</b> las liquidaciones seleccionadas?.</p>
</div>
<?php } ?>