<?php if($appuser->isInState($idestadoot,$OT_ST_ENAPROBACIONECONOMICA)) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmOTIdx1" );
	var tips = $( ".validateTips" );
	var otAprobarCtrl = true;
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#ot-aprobar" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (otAprobarCtrl) {
					otAprobarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.acciones.inc.php",
						data: "mode=aprobar"+
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
			otAprobarCtrl = true;
			tips.text("Esta seguro que desea Aprobar el nuevo presupuesto de la Orden?.");
		}
	});

	$( "#aprobar-ot" )
		.button({icons: {primary: 'ui-icon-circle-check'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ot-aprobar" ).dialog( "open" );
		});
});
</script>
<div id="ot-aprobar" title="APROBAR PRESUPUESTO ORDEN">
	<p class="validateTips">Esta seguro que desea <b>Aprobar</b> el nuevo presupuesto de la Orden?.</p>
	<input type="hidden" id="txtFrmOTIdx1" name="txtFrmOTIdx1" value="<?php echo $id; ?>"/>
</div>
<button id="aprobar-ot">Aprobar Ppto</button>
<?php } ?>