<div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Importar/Exportar IPC</h2></div>
			 <div class="messagebar">
            </div>
					<div style="padding-left: 10px;">
                        <form action="./export/ipc.inc.php" method="post">
                            <button type="submit"  id="export_data" name="export_data" class="ui-button ui-corner-all ui-widget">
                                <span class="ui-button-icon ui-icon ui-icon-document"></span>
                                Plantilla
                            </button>
                        </form>
					</div>
                    <hr/>
                    <br class="clear"/>

            <form id="formUploadAjax" enctype="multipart/form-data" method="post">            
            <input type="hidden" name="result" id="result"/>
            <table style="padding: 10px;">
                <tr>
                    <td><h2>Importar</h2></td>
                </tr>
                <tr>
				    <td class="title"><span class="required">*</span>Documento Excel:</span><br/></td>
				</tr>
                <tr>
                <td class="input">
							<input type="file" name="files" id="files">
						</td>
                </tr>
                <tr>
                    <td><span id="message" class="error">El documento es requerido.</span></td>
                </tr>
			</table>           
            
            <div class="formbuttons">
                        <button id="downloadButton">Guardar</button>
						<button type="button" onclick="location.reload();">Limpiar</button>
						<button type="button" onclick="javascript:window.history.go(-1); return false;">Regresar</button>
					</div>
            </form>
        </div>
        <div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
    </div>
</div>

<div id="dialog" title="Importando Documento">
  <div class="progress-label">Guardar</div>
  <div id="progressbar"></div>
  <div id="json"></div>
</div>

<script type="text/javascript" src="js/val/ipc.masivo.js?ver=<?php echo SGP_VERSION?>"></script>