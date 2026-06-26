<?php if($appuser->isInState($idestadoot,"$OT_ST_ENREGISTRO")) { ?>
<script type="text/javascript">
$(function() {
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

	$( "#ot-Registrar" ).dialog({
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
						data: "mode=registrada"+
							"&id="+txtId.val(),
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
			otTerminarCtrl = true;
			tips.text("Esta seguro que desea Registrar Obra?.");
		}
	});

	$( "#Registrar-ot" )
		.button({icons: {primary: 'ui-icon-eject'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-Registrar" ).dialog( "open" );
		});
});
</script>
<div id="ot-Registrar" title="REGISTRAR OBRA">
	<p class="validateTips">Esta seguro que desea <b>Registrar Obra</b>?.</p>
	<input type="hidden" id="txtFrmOTIdx8" name="txtFrmOTIdx8" value="<?php echo $id; ?>"/>
</div>
<button id="Registrar-ot">Registrar Obra</button>
<?php } ?>