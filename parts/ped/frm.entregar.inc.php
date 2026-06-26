<?php
if($idestadoped == $PED_ST_GESTIONADO) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmPedId" );
	var txt2xFecha = $( "#txt2xFecha" );
	var txt2xTras = $( "#txt2xTras" );
	var pedEntregarCtrl = true;
	
	var tips = $( ".validateTips" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	function checktxt2xFecha() {
		if ( txt2xFecha.val().length > 0) {
			return true;
		} else {
			txt2xFecha.addClass( "ui-state-error" );
			updateTips( "Debe ingresar la Fecha de Entrega." );
			return false;
		}
	}
	function checktxt2xTras() {
		if ( txt2xTras.val().length > 0) {
			return true;
		} else {
			txt2xTras.addClass( "ui-state-error" );
			updateTips( "Debe ingresar numero de Traslado." );
			return false;
		}
	}
	$( "#ped-entregar" ).dialog({
		autoOpen: false,
		height: 300,
		width: 450,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if(pedEntregarCtrl&&checktxt2xFecha() && checktxt2xTras()){
					pedEntregarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ped.acciones.inc.php",
						data: "mode=entregar"+
							"&id="+txtId.val()+
							"&fecha="+txt2xFecha.val()+
							"&traslado="+txt2xTras.val(),
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
			pedEntregarCtrl = true;
			tips.text("Esta seguro que desea Entregar el pedido?");
		}
	});
	
	$("#txt2xFecha").datepicker({dateFormat: 'yy-mm-dd'});
	
	$( "#entregar-ped" )
		.button({icons: {primary: 'ui-icon-arrowthick-1-e'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ped-entregar" ).dialog( "open" );
		});
});
</script>
<div id="ped-entregar" title="ENTREGAR PEDIDO(S)">
	<p class="validateTips">Esta seguro que desea <b>Entregar</b> el pedido?.</p>
	<input type="hidden" id="txtFrmPedId" name="txtFrmPedId" value="<?php echo $id; ?>"/>
	<table class="data-ro" id="entregar-header">
		<tr>
			<td class="title"><span class="required">*</span>Fecha Entrega:</td>
			<td class="input"><input type="text" id="txt2xFecha" name="txt2xFecha" readonly="readonly" value=""/></td>
		</tr>
		<tr>
			<td class="title"><span class="required">*</span>Numero Traslado:</td>
			<td class="input"><input type="text" id="txt2xTras" name="txt2xTras" value=""/></td>
		</tr>
	</table>
</div>
<button id="entregar-ped">Entregar</button>
<?php } ?>