<?php if($appuser->isAdmin()) { ?>
<script type="text/javascript">
$(function() {
	var dbBackupCtrl = false;
	
	var tips = $( ".validateTips" );
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	
	var progressbar = $( "#progressbar1" ),
	progressLabel = $( ".progress-label" );
	progressbar.progressbar({
		value: false,
		change: function() {
			progressLabel.text( progressbar.progressbar( "value" ) + "%" );
		},
		complete: function() {
			progressLabel.text( "Completo!" );
			dbBackupCtrl = true;
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
			if(!dbBackupCtrl)setTimeout( progress, 500 );
		}
	}
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#db-backup" ).dialog({
		closeOnEscape: false,
		autoOpen: false,
		height: 250,
		width: 380,
		modal: true,
		buttons: {
			"Cerrar": function() {
				if (dbBackupCtrl) {
					document.location.href="?menu=<?php echo getMenu()?>";
					//$( this ).dialog( "close" );
				}
			}
		},
		open: function() {
			dbBackupCtrl = false;
			$(this).parent().children().children('.ui-dialog-titlebar-close').hide();
			tips.text("Iniciando backup...");
			progressbar.progressbar( "value", 0);
			setTimeout( progress, 100 );
			$.ajax({
				type: "POST",
				url: "callback/bk.acciones.inc.php",
				data: "mode=backup",
				success: function(returnData){
					tips.text(returnData);
					dbBackupCtrl = true;
				}
			});
		}		
	});
	$( "#backup-db" )
		.button({icons: {primary: 'ui-icon-arrowthickstop-1-e'}})
		.click(function(event) {
			event.preventDefault();
			$( "#db-backup" ).dialog( "open" );
		});
});
</script>
<style>
	.ui-progressbar {position: relative;}
	.progress-label {position: absolute;left: 47%;top: 4px;font-weight: bold;color: navy}
</style>
<div id="db-backup" title="REALIZAR BACKUP">
	<div id="progressbar1"><div class="progress-label">Backup en progreso...</div></div>
	<p class="validateTips"></p>
</div>
<button id="backup-db">Lanzar Backup</button>
<?php } ?>