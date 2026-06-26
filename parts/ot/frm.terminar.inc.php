
<?php if($idestadoot<$OT_ST_TERMINADA &&(
    $appuser->isInState($idtipoot,"$OT_TIPO_VIABILIDAD,$OT_TIPO_INVENTARIORED,$OT_TIPO_DESIGN,$OT_TIPO_DIAGNOSTICO,$OT_TIPO_CONSTRUCCION")||
    $appuser->isInGroup($grp_resp_movistar) || $appuser->isInGroup($grp_resp_eec) || $appuser->isAdmin())) { ?>
<script type="text/javascript">
$(function() {

	function checkSelection() {
	$check = 0;
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
				$check = 1;
			}
		}
	}
	return $check;
}
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmOTIdx8" );
	var tips = $( ".validateTips" );
	var otTerminarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	let frm = document.querySelector("#frmSubmit");

	$( "#ot-terminar" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (otTerminarCtrl) {
					otTerminarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=terminar"+
							"&id="+txtId.val()+"&"+$("#frmSubmit").serialize(),
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								location.reload();
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
			otTerminarCtrl = true;
			tips.text("Esta seguro que desea Terminar Obra?.");
		}
	});

	$( "#terminar-ot" )
		.button({icons: {primary: 'ui-icon-eject'}})
		.click(function(event) {
			event.preventDefault();
			if (checkSelection() == 1){
			   $( "#ot-terminar" ).dialog( "open" );
			} else {
			   alert("Debe seleccionar minimo un registro para Terminar Obra");
			}			
		});
});
</script>
<div id="ot-terminar" title="TERMINAR OBRA">
	<p class="validateTips">Esta seguro que desea <b>Terminar Obra</b>?.</p>
	<input type="hidden" id="txtFrmOTIdx8" name="txtFrmOTIdx8" value="<?php echo $id; ?>"/>
</div>
<button id="terminar-ot">Terminar Obra</button>
<?php } ?>
