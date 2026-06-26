<?php include_once "parts/frm.delfile.equipos.inc.php";
$token = generateFormToken('files'); ?>
<script type="text/javascript">
$(function() {
	var uploader=$("#uploader").plupload({
    runtimes : 'html5,silverlight,gears,flash,browserplus',
		url : 'callback/files.upload.inc.php',
		max_file_size : '5mb',
		chunk_size : '1mb',
		unique_names : true,
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
					url: "callback/equipo.inc.php",
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
					alert("No es posible cargar el archivo:\n" + er.file.name + "\ndebido a que sobrepasa el limite de tama\u00f1o de 5MB!!!");
				}
			}
		}
	});
});
</script>
<label class="formLabel">Archivos (max 5MB):</label>
<div id="uploader">
	<p>Su navegador requiere Flash, Silverlight, Gears, BrowserPlus o HTML5.</p>
</div>
<label class="formLabel">Adjuntados:</label>
<table class="ui-widget ui-widget-content" style="width:100%">
	<thead>
	<tr class="ui-widget-header">
		<th scope="col" style="width: 50px;">No.</th>
		<th scope="col" style="width: 140px;">Fecha carga</th>
		<th scope="col">Archivo</th>
		<th scope="col" style="width: 40px;">Acciones</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if($appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI)){
		$where = "";
	}else{
		$where = "ad.idusuario = $appuser->uid AND";
	}
	// echo "SELECT ad.*, ecc.fecha_vencimiento FROM adjuntosequipos ad JOIN equipos_ecc ecc ON ecc.id = ad.idequipo WHERE ad.idusuario = $appuser->uid AND ecc.id = $id";die();
	$query = db_query("SELECT ad.*, ecc.fecha_vencimiento FROM adjuntosequipos ad JOIN equipos_ecc ecc ON ecc.id = ad.idequipo WHERE $where ecc.id = $id");
	$i=0;
	$count = mysqli_num_rows($query);
	if($count != 0){
		while($row = mysqli_fetch_array($query)) {
			$fechaVen = new DateTime($row['fecha_vencimiento']);
			$fechaActual = new DateTime(date('Y/m/d'));
			$vigencia = date_diff($fechaVen, $fechaActual)->format('%a');
			if($vigencia >= 0 && $row['archivo'] != ""){
				$sql_update = db_query("UPDATE equipos_ecc SET calibrado = 'Si' WHERE id = $id");
			}
			if($vigencia > 0 && $row['archivo'] == ""){
				$sql_update = db_query("UPDATE equipos_ecc SET calibrado = 'No' WHERE id = $id");
			}
			$style = ($i++%2==0)?"odd":"even";
			echo "<tr class=\"$style\">\n";
			echo "<td>$i</td>\n";
			echo "<td>".htmlspecialchars($row[create_date])."</td>\n";
			echo "<td><a href=\"includes/descarga.inc.php?document=".htmlspecialchars(trim($row[archivo]))."&ruta=" . str_replace('/sgp', '', EQ_FILE_WEB) . "&name=" . htmlspecialchars($row[titulo]). "\">".htmlspecialchars($row[titulo])."</a></td>\n";
			if($appuser->isAdmin()||$appuser->isInRole($ADMINISTRACION)||$appuser->isInGroup($GRP_OP_ZONA_PE)||$appuser->isInGroup($GRP_OP_ZONA_PI))echo "<td><span class='ui-icon ui-icon-trash' onclick='deleteFile(".htmlspecialchars($row[id]).",\"equipos\")'></span></td>\n";
			else echo "<td>-</td>\n";
			echo "</tr>\n";
		}
	}else{
		$sql_update = db_query("UPDATE equipos_ecc SET calibrado = 'No' WHERE id = $id");
		echo "<tr>".
				"<td style='text-align: center;' colspan='4'>No se han encontrado archivos adjuntos</td>".
			 "</tr>";
	}
	?>
	</tbody>
</table>
