<?php
if($appuser->isInRole("$ADMINISTRACION")) { ?>
<script type="text/javascript">
$(function() {
	
	$("#txtEstado").find("option[value='']").remove();
	
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	$("#dialog:ui-dialog").dialog("destroy");
	
	var txtFrmLiqMonth = $("#txtFrmLiqMonth");
    var txtFrmLiqYear = $("#txtFrmLiqYear");

	var tips = $(".validateTips");
	var csExportarMasCtrl = true;
	function updateTips( t ) {
		tips
			.text(t)
			.addClass("ui-state-highlight");
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checktxtFrmLiqMonth() {
		if (txtFrmLiqMonth.val().length > 0) {
			return true;
		} else {
			txtFrmLiqMonth.addClass("ui-state-error");
			updateTips( "Ingrese el mes." );
			return false;
		}
	}

    function checktxtFrmLiqYear() {
		
		if (txtFrmLiqYear.val().length > 0) {
			return true;
		} else {
			txtFrmLiqYear.addClass("ui-state-error");
			updateTips( "Ingrese el a\u00F1o." );
			return false;
		}
	}

	$("#cs-exportar-mas").dialog({
		autoOpen: false,
		height: 280,
		width: 500,
		modal: true,
		buttons: {
			"Aceptar": function() {
				if (csExportarMasCtrl && checktxtFrmLiqMonth() && checktxtFrmLiqYear()) {
					csExportarMasCtrl = false;
					$("#formExport").attr("action", "callback/cs.acciones.inc.php?mode=exportar");
					$('#formExport').submit();
					$(this).dialog("close");
				}
			},
			"Cancelar": function() {
				$(this).dialog("close");
			}
		},
		open: function() {
			csExportarMasCtrl = true;
			tips.text("Para exportar las OT y sus actividades asociadas debe teclear mes y a\u00F1o de estas!.");
		}
	});
});

function exportarAct() {
	$("#cs-exportar-mas").dialog("open");
}
</script>

<style>
input[type="number"] {
    background: #eaf4fd;
    color: #2e6e9e;
    font-weight: bold;
    font-size: 11px;
    width: 100%;
    height: 20px;
    border: 1px solid #c5dbec;
    border-radius: 4px;
    -o-border-radius: 4px;
    -moz-border-radius: 4px;
    -icab-border-radius: 4px;
    -khtml-border-radius: 4px;
    -webkit-border-radius: 4px;
}
#cs-exportar-mas table.data-ro td.title {
    width: 0px;
}
</style>
<div id="cs-exportar-mas" title="EXPORTAR ACTIVIDADES">
    <form id="formExport" method="POST" target="_blank">
		<table class="data-ro">
			<p class="validateTips">Para exportar las OT y sus actividades asociadas debe teclear mes y a&ntilde;o de estas!.</p>
			<tr>
				<td class="title"><label class="formLabel" for="txtFrmLiqMonth">Estado<span class="required">*</span></label></td>
				<td class="input"><?php echo getComboBox("SELECT id,nombre,active FROM estadoliq ORDER BY id ASC",'txtEstado');?></td>
			</tr>
			<tr>
				<td class="title"><label class="formLabel" for="txtFrmLiqMonth">Mes<span class="required">*</span></label></td>
				<td class="input"><input type="number" name="txtFrmLiqMonth" id="txtFrmLiqMonth" value="<?php echo date('m') ?>" class="wideFormInputText"/></td>
			</tr>
			<tr>
				<td class="title"><label class="formLabel" for="txtFrmLiqYear">A&ntilde;o<span class="required">*</span></label></td>
				<td class="input"><input type="number" name="txtFrmLiqYear" id="txtFrmLiqYear" value="<?php echo date('Y') ?>" class="wideFormInputText"/></td>
			</tr>
		</table>
	</form>
</div>
<?php } ?>