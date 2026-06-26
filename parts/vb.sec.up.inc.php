<?php if(!hasVal($disabled)||($create_user == $appuser->uid || $appuser->isInGroup("$GRP_SEGMENTO"))||($appuser->isInGroup("$GRP_EECC") ||
$appuser->isInGroup("$GRP_CONSTRUCCION_FO")|| $appuser->isInGroup("$ADMINISTRACIONPARCIAL")  || $idestadovb == $$VB_ST_ESTUDIO  &&  $idestadovb == $$VB_ST_REVISION)){ ?>
<?php	include_once "parts/frm.delfile.inc.php";
$token = generateFormToken('files');?>
<script type="text/javascript">
$(function() {
	$("#uploader").plupload({
    runtimes : 'html5,silverlight,gears,flash,browserplus',
		url : 'callback/files.upload.inc.php',
		max_file_size : '2mb',
		chunk_size : '1mb',
		unique_names : true,
		// Resize images on clientside if we can
		//resize : {width : 320, height : 240, quality : 90},
		// Specify what files to browse for
		filters : [
			{title : "Documentos", extensions : "pdf,txt,msg"},
			{title : "Imagenes", extensions : "jpg,gif,png"},
			{title : "MS Office", extensions : "xls,xlsx,doc,docx,ppt,pps,pptx,ppsx,vsd,vdx,vsx"},
			{title : "Comprimidos", extensions : "zip,rar,tar,gz"}
		],
		flash_swf_url : 'js/plupload.flash.swf',
    silverlight_xap_url : 'js/plupload.silverlight.xap',
		multipart_params : { "token" : "<?php echo $token; ?>"},
		init : {
			FileUploaded: function(up, file, info) {
				$.ajax({
					type: "POST",
					async:false,
					url: "callback/vb.acciones.inc.php",
					data: "mode=files"+
						"&id=<?php echo $id; ?>"+
						"&name="+encodeURI(file.name)+
						"&target="+encodeURI(file.target_name),
					success: function(returnData){
						if(returnData.indexOf('OK')!==0){
							alert(returnData);
						}
					}
				});
			},
			StateChanged: function(up) {
				// Called when the state of the queue is changed
				if(up.state == plupload.STOPPED){
					loadCurrentTab($("#tabs").tabs('option', 'active'));
				}
			},
			Error: function(up,er) {
				if (er.code == plupload.FILE_SIZE_ERROR) {
					alert("No es posible cargar el archivo:\n" + er.file.name + "\ndebido a que sobrepasa el limite de tama\u00f1o de 2MB!!!");
				}
			}
		}
	});
});
</script>
<label class="formLabel">Archivos (max 2MB):</label>
<div id="uploader">
	<p>Su navegador requiere Flash, Silverlight, Gears, BrowserPlus o HTML5.</p>
</div>
<?php } if(hasVal($id)){ ?>
<label class="formLabel">Adjuntados:</label>
<table class="ui-widget ui-widget-content" style="width:100%">
	<thead>
	<tr class="ui-widget-header">
		<th scope="col" style="width: 50px;">#</th>
		<th scope="col" style="width: 140px;">Fecha carga</th>
		<th scope="col">Cargado Por</th>
		<th scope="col">Archivo</th>
		<th scope="col" style="width: 40px;">Acciones</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$query = db_query("SELECT a.id,a.titulo,a.archivo,a.create_date,a.create_user,u.nombre usuario FROM adjuntosvb a, viabilidades v, usuarios u WHERE a.idviabilidad=v.id AND a.create_user=u.id AND v.id=$id");
	$i=0;
	while($row = mysqli_fetch_array($query)) {
		$style = ($i++%2==0)?"odd":"even";
		echo "<tr class=\"$style\">\n";
		echo "<td>$i</td>\n";
		echo "<td>".htmlspecialchars($row[create_date])."</td>\n";
		echo "<td>".htmlspecialchars($row[usuario])."</td>\n";
		echo "<td><a href=\"includes/descarga.inc.php?document=".htmlspecialchars(trim($row[archivo]))."&ruta=" . str_replace('/sgp', '', VB_FILE_WEB) . "&name=" . htmlspecialchars($row[titulo]). "\">".htmlspecialchars($row[titulo])."</a></td>\n";
		echo "<td>-</td>\n";
		echo "</tr>\n";
	}
	?>
	</tbody>
</table>
<?php } ?>
<br class="clear"/>
