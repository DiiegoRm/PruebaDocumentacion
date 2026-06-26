<?php
if($idestadores == $RES_ST_CREADA) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmPedId" );
	var tips = $( ".validateTips" );
	var pedContabilizarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#ped-contabilizar" ).dialog({
		autoOpen: false,
		height: 200,
		width: 320,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (pedContabilizarCtrl) {
					pedContabilizarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/res.acciones.inc.php",
						data: "mode=contabilizar"+
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
			pedContabilizarCtrl = true;
			tips.text("Esta seguro que desea Contabilizar la reserva?");
		}
	});

	$( "#contabilizar-ped" )
		.button({icons: {primary: 'ui-icon-tag'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ped-contabilizar" ).dialog( "open" );
		});
});
</script>
<div id="ped-contabilizar" title="CONTABILIZAR RESERVA(S)">
	<p class="validateTips">Esta seguro que desea <b>Contabilizar</b> la reserva?.</p>
	<input type="hidden" id="txtFrmPedId" name="txtFrmPedId" value="<?php echo $id; ?>"/>
</div>
<button id="contabilizar-ped">Contabilizar</button>
<?php } ?>