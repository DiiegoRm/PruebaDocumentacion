<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txt2xFecha = $( "#txt2xFecha" );
	var txt2xTras = $( "#txt2xTras" );
	var pedEntregarMasCtrl = true;
	
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
	$( "#ped-entregar-mas" ).dialog({
		autoOpen: false,
		height: 300,
		width: 450,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if(pedEntregarMasCtrl&&checktxt2xFecha() && checktxt2xTras()){
					pedEntregarMasCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ped.acciones.inc.php",
						data: "mode=entregarmas"+
							"&fecha="+txt2xFecha.val()+
							"&traslado="+txt2xTras.val()+
							formSerialize(),
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
			pedEntregarMasCtrl = true;
			tips.text("Esta seguro que desea Entregar los pedidos seleccionados?");
		}
	});
	
	$("#txt2xFecha").datepicker({dateFormat: 'yy-mm-dd'});
	
	function formSerialize(){
		var attrs = "";
		with (document.frmSubmit) {
				for (var i=0; i < elements.length; i++) {
						if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
							attrs += "&ped_"+i+"="+elements[i].value;
						}
				}
		}
		return attrs;
	}
});
function checkEntregar() {
	$check = 0;
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
				$check = 1;
				var inputid = elements[i].id.split("_");
				if (inputid[1]!='<?php echo $PED_ST_GESTIONADO?>') {
					$check = 2;
					break;
				}
			}
		}
	}
	return $check;
}
function openEntregarPed() {
	var val = checkEntregar();
	if (val === 0){
		alert("Debe seleccionar minimo un registro para entregar!");
	} else if (val === 1) {
		$( "#ped-entregar-mas" ).dialog( "open" );
	} else {
		alert("Solo puede Entregar los pedidos en estado Gestionado!");
	}
}
</script>
<div id="ped-entregar-mas" title="ENTREGAR PEDIDO(S)">
	<p class="validateTips">Esta seguro que desea <b>Entregar</b> los pedidos seleccionados?.</p>
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