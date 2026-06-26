<?php
date_default_timezone_set('America/Bogota');
error_reporting(E_ALL);
//mb_internal_encoding("UTF-8");
define("SGP_VERSION", "2.0.0-20170301");

include_once "includes/session.php";
include_once "includes/database.php";
include_once "includes/global.php";
require_once 'includes/user.class.inc.php';

if(isLoggedIn()){
	if(getMenu()=='9999'){
		include_once "logout.inc.php";
	}
    else {
		clearReports();
		$appuser = getAppUser();
		include_once "header.inc.php";
		include_once "menu.inc.php";
		include_once "content.inc.php";
		$folder = intval(getMenu()/100) * 100;
        switch(getMenu()){
			case '0':
				include_once "home.inc.php";
			break;
			case '100':case '101':
				if($appuser->isInRole($CARGAR_PRESUPUESTO)){
					include_once "modules/$folder/pp.new.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '102':
				if($appuser->isInRole($CARGAR_PRESUPUESTO)){
					include_once "modules/$folder/pp.tray.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '103':
				if($appuser->isInRole($CARGAR_PRESUPUESTO)){
					include_once "modules/$folder/pp.search.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '104':
				if($appuser->isInRole($CARGAR_PRESUPUESTO)){
					include_once "modules/$folder/modify_pp.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '200':case '201':
				if($appuser->isInRole("$GENERAR_OT_CAPEX,$GENERAR_OT_OPEX")){
					include_once "modules/$folder/ot.new.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '202':
				if($appuser->isBtwRole($GESTIONAR_PEDIDOS,$FACTURA)){
					include_once "modules/$folder/ot.tray.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '203':
				if($appuser->isInRole($VER_REPORTES_OT)){
					include_once "modules/$folder/ot.search.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '204':
			if($appuser->isInRole("$GENERAR_OT_CAPEX,$GENERAR_OT_OPEX")){
				include_once "modules/$folder/modify_ot_1.inc.php";
			} else {
				notAuthorized();
			}
			break;
			case '205':
			if($appuser->isInRole("$GENERAR_OT_CAPEX,$GENERAR_OT_OPEX")){
				include_once "modules/$folder/modify_ot.inc.php";
			} else {
				notAuthorized();
			}
			break;
			case '206':
			if($appuser->isInRole("$GENERAR_OT_CAPEX,$GENERAR_OT_OPEX")){
				include_once "modules/$folder/mod_band_ot.php";
			} else {
				notAuthorized();
			}
			break;
			case '207':
				if($appuser->isInRole("$GENERAR_VB_CAPEX,$GENERAR_VB_OPEX")){
					include_once "modules/$folder/mod_band_vb.php";
				} else {
					notAuthorized();
				}
				break;
			case '208':
				if($appuser->isAdmin() || $appuser->isInRole($ADMINISTRACION) || $appuser->isInGroup("$GRP_EECC,$GRP_OP_ZONA_PE,$GRP_OP_ZONA_PI")){
					include_once "modules/$folder/mod_equipos.php";
				} else {
					notAuthorized();
				}
			break;
			case '300':case '301':
				include_once "modules/$folder/cs.search.inc.php";
			break;
			case '302':
				include_once "modules/$folder/cs.tray.inc.php";
			break;
			case '400':case '401':
				include_once "modules/$folder/sol.search.inc.php";
			break;
			case '402':
				if($appuser->isInRole("$APROBAR_CLASE_H,$CARGAR_CLASE_H")){
					include_once "modules/$folder/sol.tray.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '500':case '501':
				include_once "modules/$folder/ped.search.inc.php";
			break;
			case '502':
				if($appuser->isInRole("$GESTIONAR_PEDIDOS")){
					include_once "modules/$folder/ped.tray.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '600':case '601':
				include_once "modules/$folder/res.search.inc.php";
			break;
			case '602':
				if($appuser->isInRole("$APROBAR_RESERVAS")){
					include_once "modules/$folder/res.tray.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '603':
				include_once "modules/$folder/res.retail.search.inc.php";
			break;
			case '604':
				if($appuser->isInRole("$APROBAR_RESERVAS")){
					include_once "modules/$folder/res.retail.tray.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '700':case '701':
				if($appuser->isInRole($GENERAR_VB)){
					if(!isset($_REQUEST["mode"]))$_REQUEST["mode"] = 'add';
					include_once "modules/$folder/vb.add.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '702':
				if($appuser->isInRole($GENERAR_VB)){
					include_once "modules/$folder/vb.add.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '703':
				if($appuser->isInRole($VER_REPORTES_VB)){
					include_once "modules/$folder/vb.search.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '704':
				if($appuser->isInRole("$GENERAR_VB,$ATENDER_VB")){
					include_once "modules/$folder/vb.tray.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '705':
				if($appuser->isInRole($GENERAR_VB)){
					include_once "modules/$folder/modify_vb.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '800':case '801':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					$page="Region";
					$table="regiones";
					include_once "modules/tables.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '802':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					$page="Jefe";
					$table="jefes";
					include_once "modules/tables.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '803':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/jefaturas.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '804':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/deptos.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '805':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/deptosxjefatura.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '806':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/localidades.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '807':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/sectores.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '808':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					$page="EECC";
					$table="eecc";
					include_once "modules/tables.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '809':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/contratos.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '810':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					$page="Segmento";
					$table="segmentos";
					include_once "modules/tables.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '811':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					$page="Red::Tipo";
					$table="tipored";
					include_once "modules/tables.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '812':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/tipos.ot.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '813':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					$page="Proyecto";
					$table="claseproyecto";
					include_once "modules/tables.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '814':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					$page="Proyecto::Tipo";
					$table="tipoproyecto";
					include_once "modules/tables.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '815':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					$page="Orden::Estado";
					$table="estadoot";
					include_once "modules/tables.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '816':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					$page="Viabilidad::Estado";
					$table="estadovb";
					include_once "modules/tables.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '817':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/tipos.vb.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '818':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					$page="Viabilidad::Proyecto";
					$table="proyectovb";
					include_once "modules/tables.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '819':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/pops.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '820':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/distribuidores.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '821':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					$page="Viabilidad::Zona";
					$table="zonas";
					include_once "modules/tables.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '822':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/peps.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '823':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/baremos.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '824':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/precios.baremo.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '825':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/clase.manoobra.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '826':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/materiales.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '827':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/deptosxzona.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '828':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					$page="Ordenes::Distancia";
					$table="distancia";
					include_once "modules/tables.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '829':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					$page="Ordenes::Velocidad";
					$table="velocidad";
					include_once "modules/tables.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '830':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/materialxactividad.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '831':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/ayuda.inc.php";
				} else {
					notAuthorized();
				}
			//---------------Nuevos Modulos
			Break;
			case '832':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/subcontratistas.inc.php";
				} else {
					notAuthorized();
				}
			Break;
			case '833':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/armario.inc.php";
				} else {
					notAuthorized();
				}
			//---------------------------
			break;
			case '834':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/tipo.equipo.inc.php";
				} else {
					notAuthorized();
				}
			//---------------------------
			break;
			case '835':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/tipoequipoxproyecto.inc.php";
				} else {
					notAuthorized();
				}
			//---------------------------
			break;
			case '836':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/equipo.inc.php";
				} else {
					notAuthorized();
				}
			//---------------------------
			break;
			case '837':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/marca.inc.php";
				} else {
					notAuthorized();
				}
			//---------------------------
			break;
			case '838':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/configuracion_baremo.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '839':
				if($appuser->isInRole($GESTIONAR_TABLAS)){
					include_once "modules/$folder/ipc.inc.php";
				} else {
					notAuthorized();
			}
			//---------------------------
			break;
			case '900':case '901':
				include_once "modules/$folder/pp.summary.inc.php";
			break;
			case '902':
				include_once "modules/$folder/pp.trays.inc.php";
			break;
			case '903':
				include_once "modules/$folder/ot.summary.inc.php";
			break;
			case '904':
				include_once "modules/$folder/ot.trays.inc.php";
			break;
			case '905':
				include_once "modules/$folder/vb.summary.inc.php";
			break;
			case '906':
				include_once "modules/$folder/vb.trays.inc.php";
			break;
			case '907':
				include_once "modules/$folder/cs.summary.inc.php";
			break;
			case '908':
				include_once "modules/$folder/cs.trays.inc.php";
			break;
			case '909':
				include_once "modules/$folder/pp.report.inc.php";
			break;
			case '910':
				include_once "modules/$folder/ot.report.inc.php";
			break;
			case '911':
				include_once "modules/$folder/vb.report.inc.php";
			break;
			case '912':
				include_once "modules/$folder/cs.report.inc.php";
			break;
			case '913':
				include_once "modules/$folder/sol.report.inc.php";
			break;
			case '914':
				include_once "modules/$folder/ped.report.inc.php";
			break;
			case '915':
				include_once "modules/$folder/res.report.inc.php";
			break;
			case '1001':
				if($appuser->isInRole("$ADMINISTRACION,$ASIGNAR_PM")  or $appuser->idgrupo==20){
					include_once "modules/1000/ot.asignar.pm.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1002':
				if($appuser->isInRole($ADMINISTRACION) or $appuser->idgrupo==20){
					include_once "modules/1000/vb.cambio.ot.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1003':
				if($appuser->isInRole($ADMINISTRACION) or $appuser->idgrupo==20){
					include_once "modules/1000/ot.cambio.estado.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1004':
				if($appuser->isInRole($ADMINISTRACION) or $appuser->idgrupo==20){
					include_once "modules/1000/vb.cambio.estado.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1005':
				if($appuser->isInRole($ADMINISTRACION) or $appuser->idgrupo==20){
					include_once "modules/1000/ot.cambio.resp.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1006':
				if($appuser->isInRole($ADMINISTRACION) or $appuser->idgrupo==20){
					include_once "modules/1000/vb.cambio.eecc.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1007':
				if($appuser->isInRole($ADMINISTRACION) or $appuser->idgrupo==20){
					include_once "modules/1000/ped.corregir.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1008':
				if($appuser->isInRole($ADMINISTRACION) or $appuser->idgrupo==20){
					include_once "modules/1000/ot.cambio.contrato.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1009':
				if($appuser->isInRole($ADMINISTRACION) or $appuser->idgrupo==20){
					include_once "modules/1000/ot.actualizar.pedidos.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1010':
				if($appuser->isInRole("$ADMINISTRACION,$CAMBIAR_PEP") or $appuser->idgrupo==20){
					include_once "modules/1000/ot.cambio.peps.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1011':
				if($appuser->isInRole("$ADMINISTRACION,$CAMBIAR_PEP") or $appuser->idgrupo==20){
					include_once "modules/1000/ped.masivo.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1012':
				if($appuser->isInRole("$ADMINISTRACION,$CAMBIAR_PEP") or $appuser->idgrupo==20){
					include_once "modules/1000/material.masivo.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1013':
				if($appuser->isInRole("$ADMINISTRACION,$CAMBIAR_PEP") or $appuser->idgrupo==20){
					include_once "modules/1000/usuario.masivo.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1014':
				if($appuser->isInRole("$ADMINISTRACION,$CAMBIAR_PEP") or $appuser->idgrupo==20){
					include_once "modules/1000/actividad.masivo.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1015':
				if($appuser->isInRole("$ADMINISTRACION,$CAMBIAR_PEP") or $appuser->idgrupo==20){
					include_once "modules/1000/ipc.masivo.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '1016':
				if($appuser->isInRole("$ADMINISTRACION,$CAMBIAR_PEP") or $appuser->idgrupo==20){
					include_once "modules/1000/clase.masivo.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '9001':
				include_once "modules/9000/password.inc.php";
			break;
			case '9002':
				if($appuser->isInRole($ADMINISTRACION)){
					include_once "modules/9000/roles.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '9003':
				if($appuser->isInRole($ADMINISTRACION)){
					include_once "modules/9000/groups.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '9004':
				if($appuser->isInRole($ADMINISTRACION)){
					include_once "modules/9000/users.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '9005':
				if($appuser->isInRole($ADMINISTRACION)){
					include_once "modules/9000/rights.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '9006':
				if($appuser->isInRole($ADMINISTRACION)){
					include_once "modules/9000/backup.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '9007':
				if($appuser->isInRole($ADMINISTRACION)){
					include_once "modules/9000/preferences.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '9008':
				if($appuser->isInRole($ADMINISTRACION)){
					include_once "modules/9000/policies.inc.php";
				} else {
					notAuthorized();
				}
			break;
			case '9010':
				if($appuser->isInRole($ADMINISTRACION)){
					include_once "modules/9000/mody_band_ot.php";
				} else {
					notAuthorized();
				}
			break;
			case '9009':
				if($appuser->isInRole($ADMINISTRACION)){
					include_once "modules/9000/modify_files.php";
				} else {
					notAuthorized();
				}
			break;
			case '9010':
				if($appuser->isInRole($ADMINISTRACION)){
					include_once "modules/9000/mody_band_ot.php";
				} else {
					notAuthorized();
				}
			break;

			case '9011':
			if($appuser->isInRole($ADMINISTRACION)){
				include_once "modules/9000/mody_eecc.php";
			}else{
				notAuthorized();
			}
			break;
			case '9013':
				if($appuser->isInRole($ADMINISTRACION)){
					include_once "modules/9000/mod_band_vb.php";
				}else{
					notAuthorized();
				}
				break;
			case '9014':
				if($appuser->isInRole($ADMINISTRACION)){
					include_once "modules/9000/causacion.inc.php";
				}else{
					notAuthorized();
				}
				break;
			case '881128':
				if($appuser->isInRole($GEOREFERENCIACION)){
					include_once "mtelefonica/panel1/index.php";
				} else {
					notAuthorized();
				}
			break;



			case '20001':
				if($appuser->isInRole($CREAR_CLUSTER)){
					if(!isset($_REQUEST["mode"]))$_REQUEST["mode"] = 'add';
					include_once "modules/$folder/clus.add.inc.php";
				} else {
					notAuthorized();
				}

				break;

			case '20004':
				if($appuser->isInRole($CREAR_CLUSTER)){
				include_once "modules/$folder/clus.tray.inc.php";
				} else {
					notAuthorized();
				}

				break;

			case '20002':
				if($appuser->isInRole($CREAR_SUBCLUSTER)){
					if(!isset($_REQUEST["mode"]))$_REQUEST["mode"] = 'add';
					include_once "modules/$folder/sub.add.inc.php";
				} else {
					notAuthorized();
				}

				break;

			case '20005':
				if($appuser->isInRole($CREAR_SUBCLUSTER)){

					include_once "modules/$folder/sub.tray.inc.php";
				} else {
					notAuthorized();
				}
				break;
			case '20003':
				if($appuser->isInRole($CONSULTA_FTTH)){
					include_once "modules/$folder/sub.search.inc.php";
				} else {
					notAuthorized();
				}
			break;


			default:
				include_once "home.inc.php";
			break;
		}
		printDebug();
		include_once "footer.inc.php";
	}
}
else {
	include_once "login.inc.php";
}
?>
