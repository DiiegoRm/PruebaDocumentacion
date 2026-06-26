<?php  
?>
<script type="text/javascript">
$(function() {

	var txtFechaDesde = $( "#txtFechaDesde" );
    var txtFechaHasta = $( "#txtFechaHasta" );

	$("#loader_aditorias").hide();
	$("#descargar").hide();

		$("#rep_auditoria").dialog({
			title: "Plan de accion",
			autoOpen: false,
			show: "blind",
			hide: "explode"
		}); 

		$("#txtFechaDesde").datepicker({ dateFormat: 'yy-mm-dd'});
		$("#txtFechaHasta").datepicker({ dateFormat: 'yy-mm-dd'});
		
	$( "#auditoria_rep" )
		.button({icons: {primary: 'ui-icon-calculator'}})
		.click(function(event) {
			$("#rep_auditoria").dialog({
				title: "Reporte de Auditoria",
				autoOpen: true,
				show: "blind",
				hide: "explode",
				buttons: { "Generar": function() { 
					if(txtFechaDesde.val() === "" || txtFechaHasta.val() === ""){
						alert("Complete los campos");
						return false;
					}
					var txtFechaDesdeVal = new Date(txtFechaDesde.val());
					var txtFechaHastaVal = new Date(txtFechaHasta.val());

					if(txtFechaDesdeVal > txtFechaHastaVal){
						alert("Verifique el rango de fechas");
						return false;
					}
					




					$.ajax({
					type: "POST",
					url: "callback/equipo.inc.php",
					dataType: 'json',
					data: "mode=auditoria"+
						"&txtFechaDesde="+txtFechaDesde.val() + "&txtFechaHasta="+txtFechaHasta.val(),
					beforeSend: function() {
						// Muestra el GIF animado antes de enviar la solicitud
						$("#loader_aditorias").show();
					},
					success: function(returnData){
						alert(returnData.msg);
							console.log(returnData.code);
						if (returnData.code == 200) {
							$("#descargar").show();
							$("#loader_aditorias").hide();

						} else if (returnData.code == 405) {
							$("#descargar").hide();
							$("#loader_aditorias").hide();

						}
						// Elimina el GIF animado después de recibir la respuesta
						$("#loader_aditorias").empty();
					},
					error: function(xhr, status, error) {
						alert("Verifique las fechas que ha ingresado ");
						// Elimina el GIF animado en caso de error
						$("#loader_aditorias").empty();
					}
				});



					

				}, 'Cancelar' : function() {
					$("#rep_auditoria").dialog('close');
				} } 
			});
			/* End */
		});
});
</script>
<button class='ui-button ui-corner-all ui-widget' type="button" id="auditoria_rep">
    Reporte de Auditoria
</button>

<div id="rep_auditoria">
	<div class="order" style="display:flex;">
		<span class="required">*</span><label style="text-align: right;color: #056A96;" for="">Fechas de generacion del reporte</label><p style="margin-left: 3px; color: #056A96;" id="lab"></p>
	</div>
	
	<label class="formLabel" for="txtFechaDesde">Desde:</label>
	<br>
	<input type="text" id="txtFechaDesde" name="txtFechaDesde" readonly="readonly"/>
	<br><br>
	<label class="formLabel" for="txtFechaHasta">Hasta:</label>
	<br>
	<input type="text" id="txtFechaHasta" name="txtFechaHasta" readonly="readonly"/>
	
	<br>
	<div class="contentButtons" style="display:flex; justify-content:center;align-items:center; margin-top:10px"></div>
	<br>
	<a id="descargar" class="ui-button ui-corner-all ui-widget" href="parts/eq/descargar_auditoria.php?file=../../data/files/tmp/Reporte.xls">Descargar archivo</a>
     <br>
	 <button class="loader_aditorias" id="loader_aditorias"></button>
</div>
