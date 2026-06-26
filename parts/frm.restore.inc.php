<?php if($appuser->isAdmin()) { ?>
<script type="text/javascript">
$(function() {
	var dbRestoreCtrl = false;
	
	var tips = $( ".validateTips" );
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	
	var progressbar = $( "#progressbar2" ),
	progressLabel = $( ".progress-label" );
	progressbar.progressbar({
		value: false,
		change: function() {
			progressLabel.text( progressbar.progressbar( "value" ) + "%" );
		},
		complete: function() {
			progressLabel.text( "Completo!" );
			dbRestoreCtrl = true;
		}
	});
	function progress() {
		var val = 0;
		$.ajax({
			type: "POST",
			url: "callback/bk.status.inc.php",
			success: function(returnData){
				var data = returnData.split("|");
				tips.text(data[0]);
				val = parseInt(data[1],10);
				if (isNaN(val)) {
					progressbar.progressbar( "option", "value", false );
				} else {
					progressbar.progressbar( "value", (val || 0) );
				}
			}
		});
		if ( val < 100) {
			if(!dbRestoreCtrl)setTimeout( progress, 500 );
		}
	}
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#db-restore" ).dialog({
		closeOnEscape: false,
		autoOpen: false,
		height: 250,
		width: 380,
		modal: true,
		buttons: {
			"Cerrar": function() {
				if (dbRestoreCtrl) {
					document.location.href="?menu=<?php echo getMenu()?>";
					//$( this ).dialog( "close" );
				}
			}
		},
		open: function() {
			dbRestoreCtrl = false;
			$(this).parent().children().children('.ui-dialog-titlebar-close').hide();
			tips.text("Iniciando Restauracion...");
			progressbar.progressbar( "value", 0);
			setTimeout( progress, 100 );
			$.ajax({
				type: "POST",
				url: "callback/bk.acciones.inc.php",
				data: "mode=restore&file=<?php echo $archivo?>",
				success: function(returnData){
					tips.text(returnData);
					dbRestoreCtrl = true;
				}
			});
		}		
	});
	$( "#restore-db" )
		.button({icons: {primary: 'ui-icon-arrowthickstop-1-w'}})
		.click(function(event) {
			event.preventDefault();
			$( "#db-restore" ).dialog( "open" );
		});
});
</script>
<style>
	.ui-progressbar {position: relative;}
	.progress-label {position: absolute;left: 47%;top: 4px;font-weight: bold;color: navy}
</style>
<div id="db-restore" title="RESTAURAR BACKUP">
	<div id="progressbar2"><div class="progress-label">Restauracion en progreso...</div></div>
	<p class="validateTips"></p>
</div>
<button id="restore-db">Restaurar Backup</button>
<?php } ?>