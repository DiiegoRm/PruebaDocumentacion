<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	var tips = $( ".validateTips" );
	var ppTaskCfgCtrl = true;
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	$( "#task-cfg" ).dialog({
		autoOpen: false,
		height: 360,
		width: 550,
		modal: true,
		open: function() {
			ppTaskCfgCtrl = true;
			$("#task-cfg-pane").hide();
			$("#task-cfg-spinner").show();
			var ot = $(this).data('ot');
			$("#task-cfg-table tbody").empty();
			$.ajax({
				type: "POST",
				url: "callback/pp.task.inc.php",
				data: "mode=list"+
					"&ot="+ot,
				success: function(returnData){
					if(returnData.indexOf('OK')===0){
						var data = returnData.split("|");
						for (var i=1;i<data.length;i++){
							var row = data[i].split("^");
							$( "#task-cfg-table tbody" ).append(
							"<tr data-row='"+row[0]+"'>" +
								"<td>" + row[0] + "</td>" +
								"<td>" + row[1] + "</td>" +
								"<td><input type='checkbox' name='task_"+row[0]+"' id='task_"+row[0]+"' value='1' "+(row[2]=='Si'?"checked":"")+"/></td>" +
							"</tr>" );
						}
					}
				}
			});
			$("#task-cfg-spinner").hide();
			$("#task-cfg-pane").show();
    },
		close: function() {
			$( "#ayuda-notas" ).html("");
		},
		buttons: {
			Guardar: function() {
				if (ppTaskCfgCtrl) {
					ppTaskCfgCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/pp.task.inc.php",
						data: "mode=cfg"+ getTaskState(),
						success: function(returnData){
							if(returnData.indexOf('OK')===0){
								loadCurrentTab($("#tabs").tabs('option', 'active'));
							}
							else updateTips(returnData);
						}
					});
				}
			},
			Cerrar: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	function getTaskState(){
		var url = "";
		$('#task-cfg-table tbody>tr').each(function() {
			var id = $(this).attr("data-row");
			url += "&task_"+id+"=";
			url += $("#task_"+id).is(':checked')?"Si":"No";
		});
		return url;
	}
	$( "#cfg-task" )
		.button({icons: {primary: 'ui-icon-note'}})
		.click(function(event) {
			event.preventDefault();
			$( "#task-cfg" )
			.data("ot",<?php echo $id; ?>)
			.dialog( "open" );
		});
});
</script>
<div id="task-cfg" title="Configurar Tareas">
	<img id="task-cfg-spinner" src="./i/bigloader.gif" style="display: none" />
	<span id="task-cfg-pane">
    <span class="ui-icon ui-icon-wrench" style="float: left; margin: 0 7px 250px 0;"></span>
		<p id="task-cfg-notas" align="justify"></p>
		<table id="task-cfg-table" class="ui-widget ui-widget-content">
			<thead>
				<tr class="ui-widget-header">
					<th>#ID</th>
					<th style="width:400px;">Nombre</th>
					<th>Activo?</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
  </span>
</div>
<button id="cfg-task">Configurar</button>