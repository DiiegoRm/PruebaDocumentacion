<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txt3xObs = $( "#txt3xObs" );
	var tips = $( ".validateTips" );
	var solRechazarMasCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	function checktxt3xObs() {
		if ( txt3xObs.val().length > 0) {
			return true;
		} else {
			txt3xObs.addClass( "ui-state-error" );
			updateTips( "Debe ingresar las observaciones." );
			return false;
		}
	}

	$( "#sol-rechazar-mas" ).dialog({
		autoOpen: false,
		height: 300,
		width: 550,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (solRechazarMasCtrl && checktxt3xObs() ) {
					solRechazarMasCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/sol.acciones.inc.php",
						data: "mode=rechazarmas"+formSerialize()+
							"&txtObs="+encodeURIComponent(txt3xObs.val()),
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
			solRechazarMasCtrl = true;
			tips.text("Esta seguro que desea Rechazar las solicitudes seleccionadas?.");
		}
	});
	function formSerialize(){
		var attrs = "";
		with (document.frmSubmit) {
				for (var i=0; i < elements.length; i++) {
						if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
							attrs += "&sol_"+i+"="+elements[i].value;
						}
				}
		}
		return attrs;
	}
});
function checkRechazarSol() {
	$check = 0;
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
				$check = 1;
				var inputid = elements[i].id.split("_");
				if (inputid[1]!='<?php echo $SOL_ST_SOLICITADA?>') {
					$check = 2;
					break;
				}
			}
		}
	}
	return $check;
}
function openRechazarSol() {
	var val = checkRechazarSol();
	if (val === 0){
		alert("Debe seleccionar minimo un registro para rechazar!");
	} else if (val === 1) {
		$( "#sol-rechazar-mas" ).dialog( "open" );
	} else {
		alert("Solo puede Rechazar las Solicitudes en estado 'Solicitada'!");
	}
}
</script>
<div id="sol-rechazar-mas" title="RECHAZAR SOLICITUDES">
	<p class="validateTips">Esta seguro que desea <b>Rechazar</b> las solicitudes seleccionadas?.</p>
	<table class="data-ro" id="rechazar-sol-mas-header">
		<tr>
			<td class="title"><span class="required">*</span>Observaciones:</td><td class="field"><textarea name='txt3xObs' id="txt3xObs" class="formTextArea" style="max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea></td>
		</tr>
	</table>
</div>