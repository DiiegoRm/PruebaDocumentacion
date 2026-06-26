<?php
$filePath = RPT_FILE_PATH . DIRECTORY_SEPARATOR ;
$files = scandir($filePath);
?>
<?php include_once "parts/frm.report.inc.php";?>
<div class="section">
	<div class="info">
	 <div class="outerbox">
		<div class="mainHeading"><h2>Lista de Reportes Reservas</h2></div>
		<div class="actionbar">
			<?php printButtonBar(array("make"=>"makeReport('reservas')")) ?>
		</div>
		<div>
			<div class="noresultsbar">Los reportes se borraran pasadas 24hrs despues de la generacion</div>
		</div>
		<br class="clear" />
		<table cellspacing="0" cellpadding="0" class="data-table">
			<thead>
				<td scope="col">Numero</td>
				<td scope="col">Archivo</td>
			</thead>
			<tbody>
<?php
		for($i=0,$j=0; $i<sizeof($files);$i++) {
			 if ($files[$i] != "." && $files[$i] != ".." && preg_match ('/^Reservas_.*\.xml$/i',$files[$i])) {
				$style = ($j++%2==0)?"odd":"even";
				echo "<tr class=\"$style\">\n";
				echo "<td>".htmlspecialchars($j)."</td>\n";
				echo "<td><a href='" . RPT_FILE_WEB . "/".htmlspecialchars($files[$i])."' target='_blank'>".htmlspecialchars($files[$i])."</a></td>\n";
				echo "</tr>\n";
			 }
		}
?>
			</tbody>
		</table>
</div>
</div>
</div>
