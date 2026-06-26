<?php if($appuser->isInRole("$APROBAR_LIQUID,$APROBAR_RESERVAS")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtFrmLiqObs = $( "#txtFrmLiqObs2" );
	var tips = $( ".validateTips" );
	var csRechazarMasCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtFrmLiqObs() {
		if ( txtFrmLiqObs.val().length > 0) {
			return true;
		} else {
			txtFrmLiqObs.addClass( "ui-state-error" );
			updateTips( "Ingrese sus observaciones." );
			return false;
		}
	}

	$( "#cs-rechazar-mas" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (csRechazarMasCtrl&&checktxtFrmLiqObs() ) {
					csRechazarMasCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/cs.acciones.inc.php",
						data: "mode=rechazarmas"+
							"&obs="+encodeURI(txtFrmLiqObs.val())+
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
			csRechazarMasCtrl = true;
			txtFrmLiqObs.val("");
			tips.text("Esta seguro que desea Rechazar las liquidaciones seleccionadas?.");
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
function checkRechazarLiq() {
	$check = 0;
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
				$check = 1;
				var inputid = elements[i].id.split("_");
				if (inputid[1]!='<?php echo $LIQ_ST_ENCONCILIACION?>'&&inputid[1]!='<?php echo $LIQ_ST_GESTIONRESERVAS?>') {
					$check = 2;
					break;
				}
			}
		}
	}
	return $check;
}
function openRechazarLiq() {
	var val = checkRechazarLiq();
	if (val === 0){
		alert("Debe seleccionar minimo un registro para rechazar!");
	} else if (val === 1) {
		$( "#cs-rechazar-mas" ).dialog( "open" );
	} else {
		alert("Solo puede Rechzar las liquidaciones en estado 'En Conciliacion' o 'Gestion de Reservas'!");
	}
}
</script>
<div id="cs-rechazar-mas" title="RECHAZAR LIQUIDACIONES">
	<p class="validateTips">Esta seguro que desea <b>Aceptar</b> las liquidaciones seleccionadas?.</p>
	<label class="formLabel" for="txtFrmLiqObs2">Observaciones<span class="required">*</span></label>
	<textarea name='txtFrmLiqObs2' id="txtFrmLiqObs2" class="formTextArea" style="width:320px;max-height:140px; min-height:120px;" maxlength="1000" tabindex="1"></textarea>
</div>
<?php } ?>