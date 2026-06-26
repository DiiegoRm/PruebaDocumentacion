<div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Resumen de Viabilidades</h2></div>
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
					<li><a href="#tabs-r2">Por EECC</a></li>
					<li><a href="#tabs-r3">Por Region</a></li>
					<li><a href="#tabs-r4">Por Departamento</a></li>
					<li><a href="#tabs-r5">Por Segmento</a></li>
					<?php if($appuser->isAdmin()){ ?>
					<li><a href="#tabs-r6">Por Creador</a></li>
					<?php } ?>
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
						$locationfilter = $appuser->getLocationFilterVB("v.");
						$query = db_query("SELECT count(*) cantidad,ev.nombre estado FROM viabilidades v, estadovb ev WHERE v.idestadovb=ev.id $locationfilter GROUP BY v.idestadovb");
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
							<td scope="col">EECC</td>
							<td scope="col">Cantidad</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$locationfilter = $appuser->getLocationFilterVB("v.");
						$query = db_query("SELECT count(*) cantidad,e.nombre eecc FROM viabilidades v, eecc e WHERE v.idestadovb > $VB_ST_CREACION $locationfilter AND v.ideecc=e.id GROUP BY v.ideecc");
						$j=0;
						while($row = mysqli_fetch_array($query)) {
							$style = ($j++%2==0)?"odd":"even";
							echo "<tr class=\"$style\">\n";
							echo "<td>".htmlspecialchars($row[eecc])."</td>\n";
							echo "<td>".htmlspecialchars($row[cantidad])."</td>\n";
							echo "</tr>\n";
						}
						?>
						</tbody>
					</table>
					<br class="clear"/>
				</div>
				<div id="tabs-r3">
					<table cellspacing="0" cellpadding="0" class="data-table">
						<thead>
						<tr>
							<td scope="col">Region</td>
							<td scope="col">Cantidad</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$locationfilter = $appuser->getLocationFilterVB("v.");
						$query = db_query("SELECT count(*) cantidad,r.nombre region FROM viabilidades v, regiones r WHERE v.idestadovb > $VB_ST_CREACION $locationfilter AND v.idregion=r.id GROUP BY v.idregion");
						$j=0;
						while($row = mysqli_fetch_array($query)) {
							$style = ($j++%2==0)?"odd":"even";
							echo "<tr class=\"$style\">\n";
							echo "<td>".htmlspecialchars($row[region])."</td>\n";
							echo "<td>".htmlspecialchars($row[cantidad])."</td>\n";
							echo "</tr>\n";
						}
						?>
						</tbody>
					</table>
					<br class="clear"/>
				</div>
				<div id="tabs-r4">
					<table cellspacing="0" cellpadding="0" class="data-table">
						<thead>
						<tr>
							<td scope="col">Departamento</td>
							<td scope="col">Cantidad</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$locationfilter = $appuser->getLocationFilterVB("v.");
						$query = db_query("SELECT count(*) cantidad,d.nombre depto FROM viabilidades v, deptos d WHERE v.idestadovb > $VB_ST_CREACION $locationfilter AND v.iddepto=d.id GROUP BY v.iddepto");
						$j=0;
						while($row = mysqli_fetch_array($query)) {
							$style = ($j++%2==0)?"odd":"even";
							echo "<tr class=\"$style\">\n";
							echo "<td>".htmlspecialchars($row[depto])."</td>\n";
							echo "<td>".htmlspecialchars($row[cantidad])."</td>\n";
							echo "</tr>\n";
						}
						?>
						</tbody>
					</table>
					<br class="clear"/>
				</div>
				<div id="tabs-r5">
					<table cellspacing="0" cellpadding="0" class="data-table">
						<thead>
						<tr>
							<td scope="col">Segmento</td>
							<td scope="col">Cantidad</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$locationfilter = $appuser->getLocationFilterVB("v.");
						$query = db_query("SELECT count(*) cantidad,s.nombre segmento FROM viabilidades v, segmentos s WHERE v.idestadovb > $VB_ST_CREACION $locationfilter AND v.idsegmento=s.id GROUP BY v.idsegmento");
						$j=0;
						while($row = mysqli_fetch_array($query)) {
							$style = ($j++%2==0)?"odd":"even";
							echo "<tr class=\"$style\">\n";
							echo "<td>".htmlspecialchars($row[segmento])."</td>\n";
							echo "<td>".htmlspecialchars($row[cantidad])."</td>\n";
							echo "</tr>\n";
						}
						?>
						</tbody>
					</table>
					<br class="clear"/>
				</div>
				<?php if($appuser->isAdmin()){ ?>
				<div id="tabs-r6">
					<table cellspacing="0" cellpadding="0" class="data-table">
						<thead>
						<tr>
							<td scope="col">Usuario</td>
							<td scope="col">Cantidad</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$query = db_query("SELECT count(*) cantidad,u.nombre usuario FROM viabilidades v, usuarios u WHERE v.idestadovb > $VB_ST_CREACION AND v.create_user=u.id GROUP BY v.create_user");
						$j=0;
						while($row = mysqli_fetch_array($query)) {
							$style = ($j++%2==0)?"odd":"even";
							echo "<tr class=\"$style\">\n";
							echo("<td>".htmlspecialchars($row[usuario])."</td>\n";
							echo("<td>".htmlspecialchars($row[cantidad])."</td>\n";
							echo "</tr>\n";
						}
						?>
						</tbody>
					</table>
					<br class="clear"/>
				</div>
				<?php } ?>
			</div>
		</div>
	 </div>
	</div>
</div>
