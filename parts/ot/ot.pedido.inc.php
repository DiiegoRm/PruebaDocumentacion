<?php if(!$appuser->isInState($idestadoot,$OT_ST_TERMINADA)){ ?>
<script type="text/javascript">
$(function() {
	var seq = 0;
	var pedidoCtrl = true;
	var id=0;
	var barped = $( "#txtPedMaterial").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
		position: { my: 'left bottom',at: 'left top' },
		appendTo: "#ped-material",
	}).multiselectfilter();
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#ped-material" ).dialog({
		autoOpen: false,
		height: 450,
		width: 700,
		modal: true,
		open: function() {
			pedidoCtrl = true;
			$("#ped-mat-pane").hide();
			$("#ped-mat-spinner").show();
			$("#ped-data tbody").empty();
			id = $(this).data('id');
			var m = $(this).data('m');
			var f = $(this).data('f');
			var c = $(this).data('c');
			$(".ui-dialog-buttonpane button:contains('Eliminar')").button("disable");
			if (m > 0) {
					$("#txtPedProgramada").val(f);
					$("#txtPedCantidad").val(c);
					$(".ui-dialog-buttonpane button:contains('Eliminar')").button("enable");
			}
			$.ajax({
				type: "POST",
				async: false,
				url: "callback/ot.pedidos.inc.php",
				data: "mode=materiales",
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						barped.empty();
						barped.multiselect("uncheckAll");
						var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
						if (m == 0) {
								opt.attr('selected','selected');
						}
						opt.appendTo( barped );
						var data = returnData.split("|");
						for (var i=1; i < data.length ; i++ ){
							var row = data[i].split("^");
							var name = $("<div/>").html("["+row[1] +"|"+ row[3] +"] "+ row[2]).text();
							var opt = $('<option />', {value: row[0],text: name});
							if (row[0]==m) {
								opt.attr('selected','selected');
							}
							opt.appendTo( barped );
						}
						barped.multiselect('enable');
						barped.multiselect('refresh');
					}
				}
			});
			$("#ped-mat-spinner").hide();
			$("#ped-mat-pane").show();
		},
		close: function() {
			$("#txtPedMaterial").val("");
			$("#txtPedProgramada").val("");
			$("#txtPedCantidad").val("");
		},
		buttons: {
			Guardar: function() {
				var frm = formValidate();
				if(frm != ""){
					if (pedidoCtrl) {
						pedidoCtrl = false;
						$.ajax({
							type: "POST",
							url: "callback/ot.pedidos.inc.php",
							data: "mode=save&"+frm,
							success: function(returnData){
								if(returnData.indexOf('OK')===0){
									loadCurrentTab($("#tabs").tabs('option', 'active'));
								} else {
									alert(returnData);
								}
							}
						});
					}
				} else alert("Complete la informacion antes de aplicar cambios");
			},
			Eliminar: function() {
				if(pedidoCtrl&&confirm('Realmente desea eliminar el pedido?')){
					pedidoCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/ot.pedidos.inc.php",
						data: "mode=del&id="+id,
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								loadCurrentTab($("#tabs").tabs('option', 'active'));
							} else {
								alert(returnData);
							}
						}
					});
				}
			},
			Cerrar: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	$('#txtPedCantidad').on('change', function () {
			var value = 0;
			if(!isNaN(parseInt($(this).val(),10))){
				value = parseInt($(this).val(),10);
			}
			$(this).val(value);
	});
	
	function formValidate(){
		var attrs = "";
		var m = parseInt($("#txtPedMaterial").val(),10);
		var c = parseInt($("#txtPedCantidad").val(),10);
		var f = $("#txtPedProgramada").val();
		if(!isNaN(m)&&m > 0 && !isNaN(c)&&c > 0 && f.length > 0){
			attrs += "&id="+id;
			attrs += "&o="+$("#txtPedOrden").val();
			attrs += "&m="+m;
			attrs += "&f="+f;
			attrs += "&c="+c;
		}
		return attrs;
	}
	$("#txtPedProgramada").datepicker({minDate: getDateFromString($("#txtFP").val(),0),dateFormat: 'yy-mm-dd'});
	$( "#btn-pedido" )
		.button({icons: {primary: 'ui-icon-cart'}})
		.click(function(event) {
			event.preventDefault();
			$( "#ped-material" )
			 .data("id",0)
			 .data("m",0)
			 .data("f",0)
			 .data("c",0)
				.dialog( "open" );
		});
});
function openPedido(id,m,f,c) {
			$( "#ped-material" )
			 .data("id",id)
			 .data("m",m)
			 .data("f",f)
			 .data("c",c)
				.dialog("open");
}
</script>
<div id="ped-material" title="Pedido de Materiales">
	<img id="ped-mat-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="ped-mat-pane">
    <span class="ui-icon ui-icon-wrench" style="margin: 0 7px 7px 0;"></span>
		<p class="validateTips">Diligencie los siguientes datos.</p>
 		<input type="hidden" id="txtPedOrden" name="txtPedOrden" value="<?php echo $id; ?>"/>
		<input type="hidden" id="txtFP" name="txtFP" value="<?php echo $fp; ?>"/>
		<table class="data-ro" id="ot-pedido">
			<tr>
				<td class="title"><span class="required">*</span>Material:</td>
				<td class="input"><select name='txtPedMaterial' id='txtPedMaterial' style='width:440px'></select></td>
			</tr>
			<tr>
				<td class="title"><span class="required">*</span>Fecha Programada:</td>
				<td class="input"><input type="text" id="txtPedProgramada" name="txtPedProgramada" readonly="readonly"/></td>
			</tr>
			<tr>
				<td class="title"><span class="required">*</span>Cantidad:</td>
				<td class="input"><input type="text" id="txtPedCantidad" name="txtPedCantidad" value=""/></td>
			</tr>
		</table>	
  </span>
</div>
<button id="btn-pedido">Pedido Nuevo</button>
<?php } ?>