<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var tips = $( ".validateTips" );
	var changed = false;
	var otTaskCtrl = true;
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#ot-task" ).dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Actualizar": function() {
				if ($('#taskStop').val() != $(this).data('finish').toString("yyyy-MM-dd") || changed) {
					if (otTaskCtrl) {
						otTaskCtrl = false;
						$.ajax({
							type: "POST",
							url: "callback/ot.task.inc.php",
							data: "mode=save"+
								"&id="+$(this).data('id') +
								"&ot="+$(this).data('ot') +
								"&start="+$('#taskStart').val() +
								"&finish="+$('#taskStop').val() +
								"&antecesor="+$('#txtAntecesor').val(),
							success: function(returnData){
								if(returnData.indexOf('OK')===0){
									loadCurrentTab($("#tabs").tabs('option', 'active'));
								}
								else updateTips(returnData);
							}
						});
					}
				} else {
					$( this ).dialog( "close" );
				}
			},
			"Cancelar": function() {
				$( this ).dialog( "close" );
			}
		},
		open: function() {
			otTaskCtrl = true;
			changed = false;
			$('#taskStart').val($(this).data('start').toString("yyyy-MM-dd"));
			$('#taskStop').val($(this).data('finish').toString("yyyy-MM-dd"));
			//$('#taskStart').datepicker('enable');
			$('#taskStop').datepicker('enable');
			$('#taskStop').datepicker('option', 'minDate', $(this).data('start'));
			var sel = $("#txtAntecesor").multiselect();
			$.ajax({
				type: "POST",
				url: "callback/ot.task.inc.php",
				data: "mode=query"+
					"&id="+$(this).data('id')+
					"&ot="+$(this).data('ot'),
				success: function(returnData){
					sel.empty();
					var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
					opt.appendTo( sel );
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							var name = $("<div/>").html(row[1]).text();
							var opt = $('<option />', {value: row[0],text: name});
							if(row[2]=="selected"){
								opt.attr('selected','selected');
							}
							opt.appendTo( sel );
						}
						sel.multiselect().multiselect('enable');
					}else{
						sel.multiselect().multiselect('disable');
						updateTips( returnData );
					}
					sel.multiselect('refresh');
				}
			});			
		},
		close: function() {
			//$('#taskStart').datepicker('disable');
			$('#taskStop').datepicker('disable');
			tips.text("Diligencie los siguientes datos.");
		}
	});
	//$("#taskStart").datepicker({dateFormat: 'yy-mm-dd'});
	$("#taskStop").datepicker({dateFormat: 'yy-mm-dd'});
	//$('#taskStart').datepicker('disable');
	$('#taskStop').datepicker('disable');
	$('#txtAntecesor').multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1,
			click: function(event, ui){
				changed = true;
		}
	});
});
</script>
<div id="ot-task" title="Modificar Tarea">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<table class="data-ro" id="ot-task-header">
		<tr>
			<td class="title">Desde:</td><td class="field"><input type="text" id="taskStart" name="taskStart" readonly="readonly" value="" class="wideFormInputText"/></td>
		</tr>
		<tr>
			<td class="title">Hasta:</td><td class="field"><input type="text" id="taskStop" name="taskStop" readonly="readonly" value="" class="wideFormInputText"/></td>
		</tr>
		<tr>
			<td class="title">Antecesor:</td><td class="field"><select name="txtAntecesor" id="txtAntecesor" class="wideFormSelect"></select></td>
		</tr>
	</table>
</div>