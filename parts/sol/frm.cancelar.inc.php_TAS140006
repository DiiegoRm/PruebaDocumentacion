<?php if($idestadosol == $SOL_ST_APROBADA) { ?>
    <script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmPedId" );
	var tips = $( ".validateTips" );
	var solAprobarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#sol-cancelar" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (solAprobarCtrl) {
					solAprobarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/sol.acciones.inc.php",
						data: "mode=cancelar"+
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
			solAprobarCtrl = true;
			tips.text("Esta seguro que desea Cancelar la Solictud?.");
		}
	});

	$( "#cancelar-sol" )
		.button({icons: {primary: 'ui-icon-circle-check'}})
		.click(function(event) {
			event.preventDefault();
			$( "#sol-cancelar" ).dialog( "open" );
		});
});
</script>
<div id="sol-cancelar" title="APROBAR SOLICITUD">
	<p class="validateTips">Esta seguro que desea <b>Cancelar</b> la Solictud?.</p>
	<input type="hidden" id="txtFrmPedId" name="txtFrmPedId" value="<?php echo $id; ?>"/>
</div>
<button id="cancelar-sol">Cancelar</button>
<?php } ?>