<?php
//echo "$idestadovb == $VB_ST_APROBACION||$idestadovb == $VB_ST_APLAZADA";
if($idestadoliq == $LIQ_ST_APROBADA&&$appuser->isInRole("$ASIGNAR_QUITAR_MES_CAUSADO")) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var txtId = $( "#txtFrmLiqId4" );
	var txtDate = $( "#txtFechaCausacion" );
	var tips = $( ".validateTips" );
	var csAsignarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	function goDate(date){
		if (csAsignarCtrl) {
			csAsignarCtrl = false;
			$.ajax({
				type: "POST",
				url: "callback/cs.acciones.inc.php",
				data: "mode=asignar"+
					"&id="+txtId.val() +
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
	$( "#cs-asignar" ).dialog({
		autoOpen: false,
		height: 350,
		width: 500,
		modal: true,
		open:function() {
			csAsignarCtrl = true;
			if(<?php echo $hasFecha; ?>==0){
				$(".ui-dialog-buttonpane button:contains('Eliminar')").button("disable");
			} else {
				$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
			}
		},
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
		close: function() {
			tips.text("Diligencie los siguientes datos.");
		}
	});
	
	$("#txtFechaCausacion").datepicker({minDate: 0,dateFormat: 'yy-mm-dd'});

	$( "#asignar-cs" )
		.button()
		.click(function(event) {
			event.preventDefault();
			$( "#cs-asignar" ).dialog( "open" );
		});
});
</script>
<div id="cs-asignar" title="FECHA CAUSACION">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<input type="hidden" id="txtFrmLiqId4" name="txtFrmLiqId4" value="<?php echo $id; ?>"/>
	<br class="clear"/>
	<label class="formLabel" for="txtFechaCausacion">Hasta<span class="required">*</span></label>
	<input type="text" id="txtFechaCausacion" name="txtFechaCausacion" readonly="readonly" value="<?php echo $hasFecha?$fecha_causacion:""; ?>" class="wideFormInputText"/>
</div>
<button id="asignar-cs">Fecha Causacion</button>
<?php } ?>