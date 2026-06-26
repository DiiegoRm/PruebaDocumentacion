<?php
ob_start();

switch ($_REQUEST['mode']) { // Added quotes around 'mode' for consistency
	case 'update': //Subir archivos
		$id = getVal($_POST['txtId']);
		setRefreshUrl("?menu=" . getMenu() . "&amp;mode=view&amp;id=" . encrypt($id) . "");
		$count = getVal($_POST['uploader_count'], "0");
		$appuser = getAppUser();
		for ($i = 0; $i < $count; $i++) {
			if ($_POST["uploader_{$i}_status"] == "done") { // Used double quotes for variable interpolation
				$uptime = $_POST["uploader_{$i}_tmpname"]; // Used double quotes for variable interpolation
				if (copy(realpath(UPLOAD_TMP_DIR . DIRECTORY_SEPARATOR . basename($uptime)), realpath(VB_FILE_PATH . DIRECTORY_SEPARATOR . basename("$id." . $uptime)))) {
					$sql = "INSERT INTO adjuntosvb(idviabilidad,titulo,archivo,create_user) VALUES($id,'" . $_POST["uploader_{$i}_name"] . "','" . $id . "." . $_POST["uploader_{$i}_tmpname"] . "',$appuser->uid)"; // Corrected string concatenation
					$sql_update = db_query($sql);
					unlink(realpath(UPLOAD_TMP_DIR . DIRECTORY_SEPARATOR . basename($uptime)));
				} else {
					printShortMsg("No se pudo cargar el archivo [" . $_POST["uploader_{$i}_name"] . "]<br />Valide con el administrador del servidor que las directorios tengas los permisos adecuados y que existe suficientes espacio.", "warn");
				}
			}
		}
		printAndStay("Actualizando base de datos, por favor espere..", "ok");
		break;
	case 'view':
		$id = decrypt(getVal($_GET['id'])); // Removed extra parenthesis
		$pp = getVal($_GET['type']);
		$r =  db_query("SELECT * FROM `viabilidades` WHERE idestadovb > $VB_ST_CREACION AND `id` = '$id'");
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC); // Changed to MYSQLI_ASSOC
		if ((is_countable($row) ? count($row) : 0) > 0) {
			$numero = $row['numero'];
			$idestadovb = $row['idestadovb'];
			$estadovb = getNameById("estadovb", $idestadovb);
			$fecha_solicitud = $row['fecha_solicitud'];
			$fecha_requerida = $row['fecha_requerida'];
			$nombre_usuario = getNameById("usuarios", $row['create_user']);
			$tel_usuario = getNameById("usuarios", $row['create_user'], "telefono");
			$segmento = getNameById("segmentos", $row['idsegmento']);
			$idsegmento = $row['idsegmento'];
			$idtipovb = $row['idtipovb'];
			$entrega = $row['entrega'];
			$idjefatura = $row['idjefatura'];
			$idjefe = $row['idjefe'];
			$idregion = $row['idregion'];
			$iddepto = $row['iddepto'];
			$ideecc = $row['ideecc'];
			$idlocalidad = $row['idlocalidad'];
			$idproyectovb = $row['idproyectovb'];
			$nombre = $row['nombre'];
			$direccion = $row['direccion'];
			$constructora = $row['constructora'];
			$contacto = $row['contacto'];
			$telefono = $row['telefono'];
			$tipodemanda = $row['tipodemanda'];
			$numcto = $row['numcto'];
			$lb = $row['lb'];
			$ba = $row['ba'];
			$tv = $row['tv'];
			$estrato = $row['estrato'];
			$viviendas = $row['total_viviendas'];
			$etapa = $row['etapa'];
			$viviendas_etapa = $row['viviendas_etapa'];
			//nuevos campos
			$cable = $row['cable'];
			$idcentral = $row['idcentral'];
			$conversor = $row['conversor'];
			$idpoligono = $row['idpoligono'];
			$idcomuna = $row['idcomuna'];
			$idcluster = $row['idcluster'];
			$Hogares_pasados = $row['hogares_pasados'];
			$id_region = $row['id_region'];
			$subcluster = $row['subcluster'];
			$idtipo_vb = $row['idtipo_vb'];
			$idtipozona = $row['idtipozona'];

			$notas_seg = $row['notas_seg'];
			$fecha_presupuesto = $row['fecha_presupuesto']; // Removed extra parenthesis

			if (hasVal($ideecc)) {
				$eecc_asignado = getNameById("eecc", $ideecc);
			}

			if (hasVal($row['idorden'])) {
				$ot_asignada = getNameById("ordenes", $row['idorden'], "numero");
			}

			$notas_ing = $row['notas_ing'];
			$respuesta = $row['respuesta'];
			$idpresupuesto = $row['idpresupuesto'];

			$disabled = "disabled='disabled'";
			$selwidth = "style='width:100%'";

			$created = $row['create_date'];
			$modified = $row['modify_date'] ?? 'Nunca'; // Used Null Coalescing Operator for cleaner code
?>
			<div class="section">
				<div class="info">
					<div class="formpage">
						<div class="outerbox">
							<div class="mainHeading">
								<h2>Ver Viabilidad</h2>
							</div>
							<div class="messagebar">
								<span id="message" class="error"></span>
							</div>
							<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu(); ?>&amp;mode=update">
								<input type="hidden" id="txtId" name="txtId" value="<?php echo $id ?>" />
								<input type="hidden" id="txtSave" name="txtSave" value="" />
								<?php include_once "parts/vb.sec.1.inc.php"; ?>
								<script type="text/javascript">
									$(function() {
										$("#tabs").tabs({
											cache: true,
											beforeLoad: function(event, ui) {
												ui.panel.html(getSpinner()); // Removed extra parenthesis
											}
										});
									});
								</script>
								<div id="tabs">
									<ul>
										<?php if ($idestadovb >= $VB_ST_EJECUCION) { ?>
											<li><a href="parts/vb/tab.respuesta.inc.php?id=<?php echo encrypt($id); ?>"><span>Respuesta</span></a></li>
										<?php } ?>
										<li><a href="#tabs-2">Solicitud</a></li>
										<li><a href="#tabs-3">Archivos</a></li>
										<li><a href="#tabs-4">Seguimiento</a></li>
										<?php if (hasVal($ot_asignada) && ($appuser->isAdmin() || ($idestadovb > $VB_ST_CREACION))) { // Corrected logic operator 
										?>
											<li><a href="parts/vb/tab.seguimiento.ot.inc.php?id=<?php echo htmlspecialchars(encrypt($id)); ?>"><span>Seguimiento <?php echo htmlspecialchars($ot_asignada); ?></span></a></li>
											<li><a href="parts/vb/tab.registro.ot.inc.php?id=<?php echo htmlspecialchars(encrypt($row['idorden'])); ?>"><span>Registro <?php echo htmlspecialchars($ot_asignada); ?></span></a></li>
										<?php } ?>
									</ul>
									<div id="tabs-2">
										<?php include_once "parts/vb.sec.2.inc.php"; ?>
										<hr />
										<?php include_once "parts/vb.sec.3.inc.php"; ?>
									</div>
									<div id="tabs-3">
										<?php include_once "parts/vb.sec.up.inc.php"; ?>
									</div>
									<div id="tabs-4">
										<?php include_once "parts/vb.sec.6.inc.php"; ?>
									</div>
								</div>
								<br class="clear" />
								<div class="formbuttons">
									<button type="button" onclick="window.location.href='/index.php?menu=703';">Regresar</button>
								</div>
							</form>
						</div>
						<div class="requirednotice">Los campos marcados con asterisco <span class="required">*</span> son obligatorios.</div>
					</div>
				</div>
			</div>
			<?php if ($completed) { ?>
				<script type="text/javascript" src="js/val/vb.attend.js?ver=<?php echo SGP_VERSION ?>"></script>
			<?php
			}
		}
		break;
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	default:
		if ($_POST['enviado'] == 'Boton') { // Added quotes around 'enviado' and 'Boton'
			$variable = "";
		} else {
			$variable = " AND o.id='-1'";
		}
		$sort = getVal($_GET['sort'], "0");
		$order = getVal($_GET['order'], "null");
		$pageNO = getVal($_POST['pageNO'], "1");
		$rowsxPage = 100;
		if ($_POST['delState']) {
			$del = $_POST['chkLocID'];
			$n = (is_countable($del) ? count($del) : 0);
			for ($i = 0; $i < $n; $i++) {
				switch ($_POST['delState']) {
					case 'DeleteMode':
						$sql_update = db_query("DELETE FROM `viabilidades` WHERE id={$del[$i]}");
						break;
					case 'EnableMode':
						$sql_update = db_query("UPDATE `viabilidades` SET `active`='Si' WHERE id={$del[$i]}");
						break;
					case 'DisableMode':
						$sql_update = db_query("UPDATE `viabilidades` SET `active`='No' WHERE id={$del[$i]}");
						break;
				}
			}
			printMessage("Actualizando base de datos, por favor espere..", "ok");
		} else {
			$statefilter = "v.idestadovb > $VB_ST_CREACION";
			$locationfilter = $appuser->getLocationFilterVB("v.");
			$sql = "SELECT v.id,v.numero,v.fecha_solicitud,v.entrega,v.fecha_requerida,v.nombre,v.direccion,v.fecha_respuesta,v.fecha_presupuesto,v.active,ev.nombre estado,u.nombre solicitante, s.nombre segmento, py.nombre AS 'proyecto',tv.nombre requerimiento,d.nombre depto,l.nombre localidad,
				r.nombre region,v.lb,v.total_viviendas,v.constructora, v.idorden, eot.nombre AS 'estadoot',v.etapa, IF(v.idestadovb=$VB_ST_ESTUDIO,IF(v.fecha_requerida BETWEEN DATE_SUB(current_timestamp,INTERVAL 1 DAY) AND current_timestamp,'amarillo',IF(current_timestamp > v.fecha_requerida,'rojo','verde')),'') alerta,
				IFNULL(e.nombre,'-') eecc,p.numero presupuesto,v.idpresupuesto,o.numero orden,v.idorden,case when v.pago is null then ' ' when v.pago ='0' then 'No' when v.pago ='1' then 'Si'end as Pago,v.fecha_pago, v.pedido, v.periodo,
                UNIX_TIMESTAMP(CURRENT_TIMESTAMP)-IFNULL(UNIX_TIMESTAMP(v.modify_date),UNIX_TIMESTAMP(v.create_date)) secs
				FROM viabilidades v LEFT JOIN ordenes o ON (v.idorden=o.id) LEFT JOIN presupuesto p ON (v.idpresupuesto=p.id) LEFT JOIN eecc e ON (v.ideecc=e.id) LEFT join estadoot eot on eot.id=o.idestadoot, estadovb ev,usuarios u, segmentos s,tipovb tv, localidades l, deptos d, regiones r, proyectovb py
				WHERE $statefilter $locationfilter AND v.idestadovb=ev.id $variable AND v.create_user=u.id AND v.fecha_solicitud>='2017-03-01' AND v.idtipovb=tv.id AND v.idlocalidad=l.id AND v.iddepto=d.id AND v.idsegmento=s.id AND v.idregion=r.id AND py.id=v.idproyectovb" . getAllSQLFilters() . getSQLSort("v.create_date", "DESC");
			$q = db_query($sql);
			$regCount = mysqli_num_rows($q);

			$maxPage = ceil($regCount / $rowsxPage);
			$rowFrom = (($pageNO - 1) * $rowsxPage);
			$fields = [ // Used short array syntax
				"v.numero" => "Numero",
				"v.fecha_solicitud" => "Solicitada",
				"v.fecha_requerida" => "Requerida",
				"tv.nombre" => "Requerimiento",
				"v.nombre" => "Nombre",
				"v.direccion" => "Direccion",
				"ev.nombre" => "Estado",
				"v.modify_date" => "Tiempo",
				"u.nombre" => "Solicitante",
				"s.nombre" => "Segmento",
				"py.nombre" => "proyecto",
				"r.nombre" => "Region",
				"d.nombre" => "Depto",
				"l.nombre" => "Localidad",
				"e.nombre" => "EECC",
				"p.numero" => "Presup",
				"o.numero" => "Orden",
				"case when v.pago is null then ' ' when v.pago ='0' then 'No' when v.pago ='1' then 'Si' end" => "Pago",
				'fecha_pago' => 'Fecha Pago',
				'pedido' => 'Pedido',
				"v.periodo" => "PeriodoPago",
				"v.entrega" => "FechaEntrega", // Corrected typo 'entreda' to 'entrega'
				"v.lb" => "Linea Basica",
				"v.total_viviendas" => "N° viviendas",
				"v.constructora" => "Contructora", // Typo corrected to Constructora if intended, or left as is if 'Contructora' is the actual column name/label
				"v.etapa" => "Etapa"
			];


			$hash = getRandomString();
			setReport($hash, "Viabilidades", $sql);
			?>
			<?php
			include_once "parts/vb/frm.pago.inc.php";
			?>
			<div class="section">
				<div class="info">
					<div class="outerbox">
						<div class="mainHeading">
							<h2>Viabilidades</h2>
						</div>
						<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu(); ?>&amp;sort=<?php echo $sort; ?>&amp;order=<?php echo $order; ?>">
							<input type="hidden" name="captureState" value="" />
							<input type="hidden" name="enviado" value="" />
							<input type="hidden" name="delState" value="" />
							<input type="hidden" name="pageNO" value="<?php echo $pageNO; ?>" />

							<div class="searchbox">
								<button type="button" onclick="returnFilterLoad();">Buscar</button>
								<button type="button" onclick="clearFilter();">Limpiar</button>
								<button type="button" onclick="exportXLS('<?php echo $hash; ?>');">Exportar</button>
								<?php if ($appuser->isAdmin() || $appuser->isInRole($ADMINISTRACION)) { ?>
									<button type="button" onclick="openPagoVb()" class="ui-button ui-corner-all ui-widget">
										<span class="ui-button-icon ui-icon ui-icon-calculator"></span>
										<span class="ui-button-icon-space"> </span>
										Pago
									</button>
								<?php } ?>
							</div>

							<div class="actionbar">
								<div class="actionbuttons">
								</div>
								<div class="noresultsbar"><?php echo htmlspecialchars($regCount) == 0 ? "No hay registros para mostrar!" : ""; ?></div>
								<div class="pagingbar">
									<?php paginate($maxPage, $pageNO, $regCount); ?>
								</div>
								<br class="clear" />
							</div>
							<br class="clear" />
							<div id="Layer1" style="width: 100%; height:auto; overflow-x:scroll;">
								<table cellspacing="0" cellpadding="0" class="data-table">
									<thead>
										<?php printFilterGrid($fields) ?>
										<tr>
											<td width="20">
												<input type="checkbox" name="allCheck" id="allCheck" class="checkbox" style="margin-left: 1px" onclick="doHandleAll()" />
											</td>
											<?php printColumns($fields); ?>
										</tr>
									</thead>
									<tbody>
										<?php
										$query = db_query("$sql LIMIT $rowFrom, $rowsxPage");
										//echo "$sql LIMIT $rowFrom, $rowsxPage";
										$i = 0;
										while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) { // Changed to MYSQLI_ASSOC
											$style = $row['active'] == 'Si' ? (($i++ % 2 == 0) ? "odd" : "even") : "disabled"; // Removed extra parenthesis and corrected ternary
											echo "<tr class=\"$style\">\n";
											echo "<td ><input type=\"checkbox\" class=\"checkbox\" name=\"chkLocID[]\" value=\"" . htmlspecialchars($row['id']) . "\" onclick=\"unCheckMain();\" /></td>\n";
											if ($row['idestadovb'] != 9) { // Corrected 'idesatdovb' to 'idestadovb'
												echo "<td><a href=\"?menu=" . getMenu() . "&amp;mode=view&amp;id=" . encrypt(htmlspecialchars($row['id'])) . "\">" . htmlspecialchars($row['numero']) . "</a></td>\n";
											} else {
												echo "<td>" . htmlspecialchars($row['numero']) . "</td>\n";
											}
											echo "<td>" . htmlspecialchars($row['fecha_solicitud']) . "</td>\n";
											echo "<td class='" . htmlspecialchars($row['alerta']) . "'>" . htmlspecialchars($row['fecha_requerida']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['requerimiento']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['nombre']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['direccion']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['estado']) . "</td>\n";
											echo "<td>" . formatSeconds(htmlspecialchars($row['secs'])) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['solicitante']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['segmento']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['proyecto']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['region']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['depto']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['localidad']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['eecc']) . "</td>\n";
											if (!($appuser->isInGroup($GRP_SEGMENTO))) { // Added parentheses for clarity in condition
												echo "<td><a href=\"?menu={$MENU_PPTO_SRC}&amp;mode=show&amp;id=" . encrypt(htmlspecialchars($row['idpresupuesto'])) . "\">" . htmlspecialchars($row['presupuesto']) . "</a></td>\n"; // Used curly braces for variable in string
												echo "<td><a href=\"?menu={$MENU_OT_SRC}&amp;mode=show&amp;id=" . encrypt(htmlspecialchars($row['idorden'])) . "\">" . htmlspecialchars($row['orden']) . "</a></td>\n"; // Used curly braces for variable in string
											} else {
												echo "<td>" . htmlspecialchars($row['presupuesto']) . "</td>\n";
												echo "<td>" . htmlspecialchars($row['orden']) . "</td>\n";
											}
											echo "<td>" . htmlspecialchars($row['Pago']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['fecha_pago']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['pedido']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['periodo']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['entrega']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['lb']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['total_viviendas']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['constructora']) . "</td>\n";
											echo "<td>" . htmlspecialchars($row['etapa']) . "</td>\n";
											echo "</tr>\n";
										}
										?>
									</tbody>
								</table>
							</div>
						</form>
					</div>
				</div>
			</div>
<?php
		}
} // end switch
//------------------------------------------------------------------------------------------
?>