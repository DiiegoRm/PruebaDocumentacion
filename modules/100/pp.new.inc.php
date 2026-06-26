<?php
ob_start();
if($_REQUEST["mode"]=='save'){
	$txtSegmento = getPostNum('txtSegmento');
	$txtTipoOT = getPostNum('txtTipoOT');
	$txtContrato = getPostNum('txtContrato');
	$txtResponsable = getPostNum('txtResponsable');
	$txtTipoRed = getPostNum('txtTipoRed');
	$txtZona = getPostNum('txtZona','0');
	$txtDepto = getPostNum('txtDepto');
	$txtLocalidad = getPostNum('txtLocalidad');
	$txtNombre = getPostStr('txtNombre');
	$txtDireccion = getPostStr('txtDireccion');
	$txtDs = getPostStr('txtDs');
	$txtEpro = getPostStr('txtEpro');
	$txtTrs = getPostStr('txtTrs');
	$txtClase = getPostNum('txtClase');
	$txtTipo = getPostNum('txtTipo');
	$txtRespMovistar = getPostNum('txtRespMovistar');
	$txtRespEECC = getPostNum('txtRespEECC');
	$txtObs = getPostStr('txtObs');
	$txtDistribuidor = getPostNum('txtDistribuidor');
	$txtPOP = getPostNum('txtPOP');
	$txtArmario = getPostStr('txtArmario');
	$txtCable = getPostStr('txtCable');
	$txtPares1 = getPostStr('txtPares1');
	$txtPares2 = getPostStr('txtPares2');
	$txtVelMax = getPostStr('txtVelMax');
	$txtDist2 = getPostStr('txtDist2');
	$txtDist1 = getPostStr('txtDist1');
	$txtVivienda = getPostStr('txtVivienda');
	$txtTorres = getPostStr('txtTorres');
	$txtBocas = getPostStr('txtBocas');
	$txtVerticales = getPostStr('txtVerticales');
	$txtLatitud= getPostStr('txtLatitud');
	$txtLongitud= getPostStr('txtLongitud');
	$CheckAtriArm = getPostStr('checkArm');
	$CheckAtriDist = getPostStr('checkDist');
	
	if(hasVal($txtTipoOT)&&hasVal($txtSegmento)){
		$uid = $appuser->uid;
		
		$sql_ins = db_query("INSERT INTO `presupuesto` (`numero`,`fecha_solicitud`,`idsegmento`,`idtipoot`,`fecha_requerida`,`idcontrato`,`eeccxresponsable`,`idzona`,`iddepto`,`idlocalidad`,`idtipored`,`nombre`,`direccion`,`ds`,`epro`,`trs`,`idclaseproyecto`,`idtipoproyecto`,resp_movistar,resp_eecc,iddistribuidor,armario,cable,idpop,parprim,parsec,idvelmaxba,distarm,iddistcaja,viviendas,torres,bocas,verticales,create_user,modify_user,notas,latitud,longitud,atrib_dist,atrib_arm) VALUES ('0',CURRENT_DATE,$txtSegmento,$txtTipoOT,CURRENT_DATE,$txtContrato,$txtResponsable,$txtZona,$txtDepto,$txtLocalidad,$txtTipoRed,$txtNombre,$txtDireccion,$txtDs,$txtEpro,$txtTrs,$txtClase,$txtTipo,$txtRespMovistar,$txtRespEECC,$txtDistribuidor,$txtArmario,$txtCable,$txtPOP,$txtPares1,$txtPares2,$txtVelMax,$txtDist1,$txtDist2,$txtVivienda,$txtTorres,$txtBocas,$txtVerticales,$uid,$uid,$txtObs,$txtLatitud,$txtLongitud,$CheckAtriDist,$CheckAtriArm)");
		if($sql_ins > 0){
			$lastpp = getLastId();
			$numero = "PRE-".padZeroLeft($lastpp,8);
			db_query("UPDATE `presupuesto` SET numero='$numero' WHERE id=$lastpp");
			db_query("INSERT INTO precronograma(idpresupuesto) VALUES($lastpp)");
			$lastcrono = getLastId();
			db_query("INSERT INTO pretareas(idcrono,idtipo,duracion) SELECT $lastcrono, id, 1 FROM tipotarea WHERE active='Si'");
			setRefreshUrl("?menu=$MENU_PPTO_TRAY&amp;mode=edit&amp;id=".encrypt($lastpp));
			printAndStay("Actualizando base de datos, por favor espere..<br />Se guardo el Presupuesto numero $numero","ok");
		} else {
		 printMessage("Error al guardar el presupuesto...","error");
		}
	} else {
	 printMessage("No ha completado los campos obligatorios...","error");
	}
} else {
$numero = "Auto-Generado";
$estado = "INCIADO";
$fecha_solicitud = date("Y-m-d");
$fecha_requerida = "Calculado";
$nombre_usuario = $appuser->nombre;
$tel_usuario = $appuser->telefono;
?>
 <div class="section">
	<div class="info">
	 <div class="formpage">
		<div class="outerbox">
			<div class="mainHeading"><h2>Adicionar Presupuesto</h2></div>
				<form name="frmSubmit" id="frmSubmit" method="post" action="?menu=<?php echo getMenu();?>&amp;mode=save">
				<script type="text/javascript">
				$(function() {
					$( "#tabs" ).tabs({
							cache:true,
							beforeLoad: function(event, ui) {
								ui.panel.html(getSpinner());
							}
					});
				});
				</script>
				<?php include_once "parts/pp/sec.header.inc.php"; ?>
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">Presupuesto</a></li>
					</ul>
					<div id="tabs-1">
						<?php include_once "parts/pp/tab.ppto.rw.inc.php"; ?>
					</div>
				</div>
				</form>
		</div>
	</div>
	</div>
 </div>
 <?php } ?>