<?php if($idestadovb==$VB_ST_ESTUDIO &&(isInTray("vb","idviabilidad","$GRP_INGENIERIA,$GRP_OP_ZONA_PE") && !hasVal($ideecc))) { ?>
<script type="text/javascript">
$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	var txtId = $( "#txtIdOrdenx6" );
	var txtNewEECC = $( "#txtNewEECC" );
	var txtEstadoAsigVB = $( "#txtEstadoAsigVB" );
	var tips = $( ".validateTips" );
	var vbAsignarCtrl = true;

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtNewEECC() {
		if ( txtNewEECC.val() != "") {
			return true;
		} else {
			txtNewEECC.addClass( "ui-state-error" );
			updateTips( "Seleccione un EECC." );
			return false;
		}
	}

	$( "#vb-asignar" ).dialog({
		autoOpen: false,
		height: 320,
		width: 480,
		modal: true,
		buttons: {
			"Guardar": function() {
				if ( vbAsignarCtrl && checktxtNewEECC() ) {
					vbAsignarCtrl = false;
					$.ajax({
						type: "POST",
						url: "callback/vb.acciones.inc.php",
						data: "mode=eecc"+
							"&txtId="+txtId.val() +
							"&txtNewEECC="+txtNewEECC.val() +
							"&txtEstadoVB="+txtEstadoAsigVB.val(),
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
			vbAsignarCtrl = true;
			txtNewEECC.val("");
			tips.text("Diligencie los siguientes datos.");
		}
	});

	$( "#asignar-eecc" )
		.button({icons: {primary: 'ui-icon-person'}})
		.click(function(event) {
			event.preventDefault();
			$( "#vb-asignar" ).dialog( "open" );
		});

	$("#txtNewEECC").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1
	});
});
</script>
<div id="vb-asignar" title="ASIGNAR EECC">
	<p class="validateTips">Diligencie los siguientes datos.</p>
	<div style="float:left;margin: 2px 0 2px 2px;">
		<input type="hidden" id="txtIdOrdenx6" name="txtIdOrdenx6" value="<?php echo $id; ?>"/>
		<input type="hidden" id="txtEstadoAsigVB" name="txtEstadoAsigVB" value="<?php echo $idestadovb; ?>"/>
		<label class="formLabel" id="lbNewEECC" for="txtNewEECC">EECC<span class="required">*</span></label>
		<select name="txtNewEECC" id="txtNewEECC" class="wideFormSelect" tabindex="1">
		<?php
		echo "<option value=''>---SELECCIONE---</option>";
		$val = @db_query("SELECT e.id,e.nombre,e.active FROM eecc e, contratos c, zonaxdepto z WHERE c.idzona=z.idzona AND c.ideecc=e.id AND z.iddepto=$iddepto");
		//$val = @db_query("SELECT e.id,e.nombre FROM eecc e");
		 if (mysqli_num_rows($val) > 0){
			 while($row = mysqli_fetch_array($val)){
				$dis = $row['active'] != 'Si'?"disabled='disabled'":"";
				echo "<option value='".htmlspecialchars($row[id])."' $dis>".htmlspecialchars($row[nombre])."</option>";
			 }
		 }
		?>
		</select>
	</div>
</div>
<button id="asignar-eecc">Asignar EECC</button>
<?php } ?>
