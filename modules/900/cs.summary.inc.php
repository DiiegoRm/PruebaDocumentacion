<div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Resumen de Causaciones</h2></div>
			 <div class="messagebar">
                <span id="message" class="error"></span>
            </div>
			<script type="text/javascript">
			$(function() {
				$( "#tabs" ).tabs({cache:true});
			});
			</script>
			<div id="tabs">
				<ul>
					<li><a href="#tabs-r1">Por Estado</a></li>
					<li><a href="#tabs-r2">Por Orden</a></li>
				</ul>
				<div id="tabs-r1">
					<table cellspacing="0" cellpadding="0" class="data-table">
						<thead>
						<tr>
							<td scope="col">Estado</td>
							<td scope="col">Cantidad</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$query = db_query("SELECT count(*) cantidad,e.nombre estado FROM liquidaciones l, estadoliq e WHERE l.idestadoliq=e.id GROUP BY l.idestadoliq");
						$j=0;
						while($row = mysqli_fetch_array($query)) {
							$style = ($j++%2==0)?"odd":"even";
							echo "<tr class=\"$style\">\n";
							echo "<td>".htmlspecialchars($row[estado])."</td>\n";
							echo "<td>".htmlspecialchars($row[cantidad])."</td>\n";
							echo "</tr>\n";
						}
						?>
						</tbody>
					</table>
					<br class="clear"/>
				</div>
				<div id="tabs-r2">
					<table cellspacing="0" cellpadding="0" class="data-table">
						<thead>
						<tr>
							<td scope="col">Orden</td>
							<td scope="col">Cantidad</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$filter = $appuser->getLocationFilterOT("o.");
						$query = db_query("SELECT count(*) cantidad,o.numero orden FROM liquidaciones l, ordenes o WHERE l.idorden=o.id $filter GROUP BY l.idorden");
						$j=0;
						while($row = mysqli_fetch_array($query)) {
							$style = ($j++%2==0)?"odd":"even";
							echo "<tr class=\"$style\">\n";
							echo "<td>".htmlspecialchars($row[orden])."</td>\n";
							echo "<td>".htmlspecialchars($row[cantidad])."</td>\n";
							echo "</tr>\n";
						}
						?>
						</tbody>
					</table>
					<br class="clear"/>
				</div>
			</div>
		</div>
	 </div>
	</div>
</div>
