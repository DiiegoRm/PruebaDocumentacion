<?php if($appuser->isInRole("$ASIGNAR_QUITAR_MES_CAUSADO")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtDate = $( "#txtFechaCausacion" );
	var tips = $( ".validateTips" );
	var csAsignarMasCtrl = true;
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	function goDate(date){
		if (csAsignarMasCtrl) {
			csAsignarMasCtrl = false;
			$.ajax({
				type: "POST",
				url: "callback/cs.acciones.inc.php",
				data: "mode=asignarmas"+formSerialize()+
					"&date="+date,
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						document.location.href="?menu=<?php echo getMenu()?>";
					}
					else updateTips(returnData);
				}
			});
		}
	}
	$( "#cs-asignar-mas" ).dialog({
		autoOpen: false,
		height: 350,
		width: 500,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if(txtDate.val().length > 0){
					goDate(txtDate.val());
				}
			},
			"Eliminar": function() {
				goDate('');
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
			}
		},
		open: function() {
			csAsignarMasCtrl = true;
			tips.text("Esta seguro que desea Asignar/Eliminar Fecha Causacion las liquidaciones seleccionadas?.");
		}
	});
	
	$("#txtFechaCausacion").datepicker({minDate: 0,dateFormat: 'yy-mm-dd'});

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
function checkAsignarLiq() {
	$check = 0;
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
				$check = 1;
				var inputid = elements[i].id.split("_");
				if (inputid[1]!='<?php echo $LIQ_ST_APROBADA?>') {
					$check = 2;
					break;
				}
			}
		}
	}
	return $check;
}
function openAsignarLiq() {
	var val = checkAsignarLiq();
	if (val === 0){
		alert("Debe seleccionar minimo un registro para asignar/eliminar fecha de causacion!");
	} else if (val === 1) {
		$( "#cs-asignar-mas" ).dialog( "open" );
	} else {
		alert("Solo puede Asignar/Eliminar Fecha para las liquidaciones en estado 'Aprobada'!");
	}
}
</script>
<div id="cs-asignar-mas" title="FECHA CAUSACION">
	<p class="validateTips">Esta seguro que desea <b>Asignar/Eliminar Fecha Causacion</b> las liquidaciones seleccionadas?.</p>
	<br class="clear"/>
	<label class="formLabel" for="txtFechaCausacion">Hasta<span class="required">*</span></label>
	<input type="text" id="txtFechaCausacion" name="txtFechaCausacion" readonly="readonly" value="<?php echo $hasFecha?$fecha_causacion:""; ?>" class="wideFormInputText"/>
</div>
<?php } ?>