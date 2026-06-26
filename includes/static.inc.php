<?php

function getIP(){
 return htmlspecialchars('10.86.55.152'); //pruebas
}

function getPassword(){
return htmlspecialchars('Temporal01+'); //pruebas
}


function getUserD(){
   return htmlspecialchars('gestot');
}

function getPuerto(){
 return htmlspecialchars('32322');//pruebas
}

function getBD(){
   return htmlspecialchars('sgp');
}

	define("DB_VERSION", "1.1.0");
	// Configuracion de mensajes de aplicacion
	define("MYSQL_DB_NAME", "sgp");
	define("MYSQL_DB_NAMEref", "ebucoco_mtelefonica");
	define("MYSQL_ERROR_REPORTING", true);
	define("MYSQL_RAW_ERROR_REPORTING", true);
	define("CHUNK_ROWS",2500);
	define("FILE_TXT_REMEDY","C:/wamp64/www/gestot/");
	//Rutas de almacenamiento de archivos
	if(strpos(PHP_OS,"Linux") !== false){
		define("ENABLE_APP_DEBUG", false);
		if(php_uname("n") == "CLPROADMAPP191" or "CLPROADMAPP192"){
			define("BASE_FILE_PATH", "/var/www/sgp/data/files");
			define("BASE_WEB_PATH", "");
			//define("SQL_LOG_FILE", "/var/www/sgp/gestot.sql.log");
			define("SQL_LOG_FILE", "../../gestot.sql.log");
			//define("SQL_LOG_FILE", "gestot.sql.log");
			define("BACKUP_PATH", "/bk");
			define("SGP_TESTING", false);
		} else {
			define("BASE_FILE_PATH", "/var/opt/webstack/apache2/2.2/htdocs/sgpdev/files");
			define("BASE_WEB_PATH", "/sgpdev");
			define("SQL_LOG_FILE", "/var/tmp/gestot.sql.log");
			define("BACKUP_PATH", "/tmp");
			define("SGP_TESTING", true);
		}
	}
	//--------------Modificaci�n ruta de almacenamiento-------------
	else if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		define("BASE_FILE_PATH", "C:\\GIT\\Gestot\\data\\files");
		//C:\Users\hpadmincolTT\Desktop
		//define("BASE_FILE_PATH", "F:\\htdocs\\sgp\\files");
		define("BASE_WEB_PATH", "C:\\GIT\\Gestot\\data\\files");
		define("SQL_LOG_FILE", "C:\\GIT\\Gestot\\gestot.sql.log");
		define("BACKUP_PATH", "C:\\GIT\\Gestot\\");
		define("ENABLE_APP_DEBUG", true);
		define("SGP_TESTING", true);
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
	//--------------------------------------------------------------
	}
	else {
		define("BASE_FILE_PATH", "/Volumes/ANONYMOUS/htdocs/sgp/files");
		define("BASE_WEB_PATH", "/sgp");
		define("SQL_LOG_FILE", "/tmp/gestot.sql.log");
		define("BACKUP_PATH", "/tmp");
		define("ENABLE_APP_DEBUG", false);
		define("SGP_TESTING", false);
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
	}
	
	// define("BASE_FILE_PATH", "C:\\wamp64\\www\\gestot\\");
	define("UPLOAD_TMP_DIR", BASE_FILE_PATH . DIRECTORY_SEPARATOR . "tmp");
	define("VB_FILE_PATH", BASE_FILE_PATH . DIRECTORY_SEPARATOR ."vb");
	define("OT_FILE_PATH", BASE_FILE_PATH . DIRECTORY_SEPARATOR . "ot");
	define("EQUIPOS_FILE_PATH", BASE_FILE_PATH. DIRECTORY_SEPARATOR . "eq");
	define("SOL_FILE_PATH", BASE_FILE_PATH . DIRECTORY_SEPARATOR . "sol");
	define("PP_FILE_PATH", BASE_FILE_PATH . DIRECTORY_SEPARATOR . "pp");
	define("REG_FILE_PATH", BASE_FILE_PATH . DIRECTORY_SEPARATOR . "reg");
	define("RPT_FILE_PATH", BASE_FILE_PATH . DIRECTORY_SEPARATOR . "rpt");
	define("LOG_FILE_PATH", BASE_FILE_PATH . DIRECTORY_SEPARATOR . "log");
	
	define("VB_FILE_WEB", BASE_WEB_PATH . "/data/files/vb/");
	define("OT_FILE_WEB", BASE_WEB_PATH . "/data/files/ot/");
	define("EQ_FILE_WEB", BASE_WEB_PATH . "/data/files/eq/");
	define("SOL_FILE_WEB", BASE_WEB_PATH . "/data/files/sol/");
	define("PP_FILE_WEB", BASE_WEB_PATH . "/data/files/pp/");
	define("REG_FILE_WEB", BASE_WEB_PATH . "/data/files/reg/");
	define("RPT_FILE_WEB", BASE_WEB_PATH . "/data/files/rpt/");

	//Roles
	static $ADMINISTRACION = 1;
    static $ADMINISTRACIONPARCIAL=20;
	static $ADMINISTRACIONMATERIALES=21;
	STATIC $CONTRATISTA = 8;
	static $GESTIONAR_TABLAS = 2;
	static $VER_REPORTES_VB = 3;
	static $VER_REPORTES_OT = 4;
	static $GENERAR_VB = 5;
	static $ATENDER_VB = 6;
	static $APROBAR_VB = 7;
	static $GESTIONAR_PEDIDOS = 8;
	static $CARGAR_PRESUPUESTO = 9;
	static $GENERAR_OT_CAPEX = 10;
	static $GENERAR_OT_OPEX = 11;
	static $GENERAR_VB_CAPEX = 10;
	static $GENERAR_VB_OPEX = 11;
	static $REASIGNAR_PRESUPUESTO = 12;
	//static $CARGAR_BAREMOS = 13; //Sin uso
	//static $CARGAR_MATERIAL = 14; //Sin uso
	static $AVANCES = 15;
	static $AJUSTAR_CRONOGRAMA = 16;
	static $LIQUIDAR_OT = 17;
	static $APROBAR_LIQUID = 18;
	static $CANCELAR_LIQUIDACION_SIN_CAUSAR = 19;
	//static $CARGAR_RESERVAS = 20;//Sin usar
	static $APROBAR_RESERVAS = 21;
	static $CARGAR_REGISTRO = 22;
	static $APROBAR_REGISTRO = 23;
	static $CARGAR_CLASE_H = 24;
	static $APROBAR_CLASE_H = 25;
	static $ASIGNAR_QUITAR_MES_CAUSADO = 26;
	static $PEDIDO_Y_MIGO = 27;
	static $FACTURA = 28;
	static $VER_CAUSACION = 29;
	static $ASIGNAR_PM = 30;
	static $GEOREFERENCIACION = 31;
	static $CANCELAR_ANULAR_PTO = 32;

	// Anadido Jose
	static $BAREMOS_CONTRATO_COINVERSION= 6849;

	//crear subcloster
		static $CREAR_CLUSTER = 34;
		static $CREAR_SUBCLUSTER = 35;
		static $CONSULTA_FTTH = 36;

	/**=ORDENES=**/
	//Estados Ordenes
	static $OT_ST_ENCREACION=0;
	static $OT_ST_APLAZADA=1;
	static $OT_ST_CANCELADA=2;
	static $OT_ST_CONORDENDETRABAJO=3;
	static $OT_ST_ENEJECUCION=4;
	static $OT_ST_OBRASCLIENTE=5;
	static $OT_ST_PENDIENTEMATERIALES=6;
	static $OT_ST_PERMISOSENTIDADNACIONAL=7;
	static $OT_ST_PERMISOSENTIDADMUNICIPAL=8;
	static $OT_ST_PERMISOSCOMERCIALES=9;
	static $OT_ST_TERMINADA=10;
	static $OT_ST_CERRADA=11;
	static $OT_ST_PUERTO_PON=20;
 	static $OT_ST_REMPLANTEADA=21;
	static $OT_ST_ENAPROBACIONECONOMICA=12;
	static $OT_ST_ENREPROGRAMACION=13;
	static $OT_ST_ENREGISTRO=14;
	static $OT_ST_SOLICITUDCANCELACION=15;
	static $OT_ST_CONSULTASPREVIAS=16;
	static $OT_ST_PENDIENTEPRESUPUESTOMATERIAL=17;
	static $OT_ST_PENDIENTEMATERIALESADICION=24;
	static $OT_PERMISOS="3,4,5,6,7,8,9,16,17,21,24";
	static $OT_PUEDE_CANCELAR="2,3,5,6,7,8,9,16,24";
	static $OT_INACTIVA="1,2,11,12,13,15";
    static $OT_ST_REGISTRADA=19;
	
	// Estados de viabilidad
	static $VB_ST_ENCREACION=0;

	//Versiones de OT
	static $OT_VER_GENERADA = 1;
	static $OT_VER_EJECUCION = 2;

	//Tipo Orden
	static $OT_TIPO_DESIGN = 1;
	static $OT_TIPO_CONSTRUCCION = 2;
	static $OT_TIPO_INVENTARIORED = 3;
	static $OT_TIPO_DIAGNOSTICO = 5;
	static $OT_TIPO_VIABILIDAD = 6;
	static $OT_TIPO_REEMBOLSABLES = 7;

	static $OT_TIPO_RED_COBRE = 1;
	static $OT_TIPO_RED_FIBRA = 2;
	static $OT_TIPO_RED_TV = 3;
	static $OT_CLASEMO_L = 2;
	static $OT_CLASEMO_L_1 = 15;
    static $OT_CLASEMO_L_2017 = 20;
	static $OT_CLASEMO_L_1_2017 = 24;
	static $OT_POSTERIA = 28;
	static $OT_FIBRA_L = 164;
	static $OT_CLASEMO_C = 4;
	static $OT_CLASEMO_C_1 = 16;
    static $OT_CLASEMO_C_2017 = 22;
	static $OT_CLASEMO_C_1_2017 = 25;
	static $OT_FIBRA_C = 235;
	static $OT_CLASEMO_G = 5;
    static $OT_CLASEMO_G_2017 = 28;

		//  Contratos Nuevos ID 27 hacia delante
    	static $CONTRATOS_NUEVOS= 28;

		/**Quitando estos parametros quemados */
    	// Baremos Contratos antiguos ID desde 0 hasta 722
		// --
    	static $BAREMOS_CONTRATO_ANTIGUO= 722;
    	// Baremos Contratos Nuevos ID 723 hacia delante
		// --
    	static $BAREMOS_CONTRATO_NUEVO= 723;
		// Baremos contrato filiales hacia Adelante
    	// --
		static $BAREMOS_CONTRATO_FILIALES= 2183;
		//
		//--
		static $BAREMOS_CONTRATO_COINVERSION= 6840;
		/** Quitando estos parametros */
	
		/* 
		 *	Dinamico variables
		 */
		/*$sql = "SELECT * FROM baremo_configuracion";
		$conexion = mysqli_connect(getIP(), getUserD(), getPassword());
		mysqli_select_db($conexion, getBD()) or die ("Ninguna DB seleccionada");
		$query = mysqli_query($conexion, $sql);
		if (mysqli_num_rows($query) > 0) {
			while ($row = mysqli_fetch_array($query)) {
				$parametro = $row['parametro'];
				${$parametro} = $row['value'];
			}
		}*/

	//Actividades Baremo calculadas
	static $OT_BAREMO_100021 = 3; //CALC OPCION
	static $OT_BAREMO_100030 = 4; //CALC OPCION
	static $OT_BAREMO_100048 = 5; //CALC OPCION
    static $OT_BAREMO_2017_100021 = 1110; //CALC OPCION
	static $OT_BAREMO_2017_100030 = 1111; //CALC OPCION
	static $OT_BAREMO_2017_100048 = 1112; //CALC OPCION

	static $OT_BAREMO_100056 = 6; //CALC OPCION
	static $OT_BAREMO_100064 = 7; //CALC OPCION
    	static $OT_BAREMO_2017_100056 = 1113; //CALC OPCION
	static $OT_BAREMO_2017_100064 = 1114; //CALC OPCION

	static $OT_BAREMO_100099 = 9; //CALC OPCION
	static $OT_BAREMO_100102 = 10; //CALC OPCION
	static $OT_BAREMO_100111 = 11; //CALC OPCION
    	static $OT_BAREMO_2017_100099 = 1115; //CALC OPCION
	static $OT_BAREMO_2017_100102 = 1116; //CALC OPCION
	static $OT_BAREMO_2017_100111 = 1117; //CALC OPCION

	static $OT_BAREMO_100153 =12; //CALC OPCION
    	static $OT_BAREMO_2017_100153 =1118; //CALC OPCION
    	static $OT_BAREMO_2018_100153 =2215; //CALC OPCION

	static $OT_BAREMO_290033 = 165; //CALC OPCION
	static $OT_BAREMO_290068 = 167; //CALC OPCION
	static $OT_BAREMO_290106 = 169; //CALC OPCION
	static $OT_BAREMO_290114 = 170; //CALC OPCION
	static $OT_BAREMO_290149 = 172; //CALC OPCION
	static $OT_BAREMO_290190 = 174; //CALC OPCION
	static $OT_BAREMO_2017_290033 = 1084; //CALC OPCION
	static $OT_BAREMO_2017_290106 = 1086; //CALC OPCION
	static $OT_BAREMO_2017_290149 = 1088; //CALC OPCION

	////////////////////////////////////////////
	static $OT_BAREMO_290033_1 = 603; //CALC OPCION
	static $OT_BAREMO_290106_1 = 605; //CALC OPCION
	static $OT_BAREMO_290114_1= 606; //CALC OPCION
	static $OT_BAREMO_290149_1= 608; //CALC OPCION
	static $OT_BAREMO_290190_1= 610; //CALC OPCION

	///////////////////////////////////////////

	static $OT_BAREMO_225011 = 203; //F3
	static $OT_BAREMO_225029 = 204; //F3
	static $OT_BAREMO_2017_225011 = 743; //F3
	static $OT_BAREMO_2017_225029 = 744; //F3
    static $OT_BAREMO_2018_225011 = 2308; //F3
    static $OT_BAREMO_2018_225029 = 2309; //F3

	static $OT_BAREMO_290688 = 267; //F4
	static $OT_BAREMO_290696 = 268; //F4
	static $OT_BAREMO_290408 = 241; //F4
	static $OT_BAREMO_290416 = 242; //F4
	static $OT_BAREMO_290424 = 243; //F4
	static $OT_BAREMO_290432 = 244; //F4
    static $OT_BAREMO_2017_290688 = 783; //F4
	static $OT_BAREMO_2017_290408 = 776; //F4
	static $OT_BAREMO_2017_290416 = 777; //F4
	static $OT_BAREMO_2017_290424 = 778; //F4
	static $OT_BAREMO_2017_290432 = 779; //F4
	static $OT_BAREMO_2018_290408 = 2504; //F4
	static $OT_BAREMO_2018_290416 = 2505; //F4
	static $OT_BAREMO_2018_290424 = 2506; //F4
	static $OT_BAREMO_2018_290432 = 2507; //F4
	static $OT_BAREMO_2018_290688 = 2513; //F4
	
	
	//2020
    static $OT_BAREMO_2020_290688 = 6859; //F4

	// Codigo de la actividad baremo = ID de la actividad
	static $OT_BAREMO_2020_290408 = 6852; //F4

	static $OT_BAREMO_2020_290416 = 6853; //F4
	static $OT_BAREMO_2020_290424 = 6854; //F4
	static $OT_BAREMO_2020_290432 = 6855; //F4
		
	static $OT_BAREMO_430048  = 295; //F5
	static $OT_BAREMO_430048A = 296; //F5
    static $OT_BAREMO_2017_430048A  = 899; //F5
	static $OT_BAREMO_2017_430048B = 900; //F5
	static $OT_BAREMO_2018_430048 = 2258 ; //F5
	
	// Añadido Listo Revisado
	static $OT_BAREMO_2020_430048A  = 6903; //F5
	static $OT_BAREMO_2020_430048B = 6904; //F5

	static $OT_BAREMO_450022  = 329; //F5A
	static $OT_BAREMO_450022A = 330; //F5A
	static $OT_BAREMO_2017_450022A  = 928; //F5A
	static $OT_BAREMO_2017_450022B = 929; //F5A
	static $OT_BAREMO_2018_450022 = 2281; //F5A
	
	// Añadido Listo Revisado
	static $OT_BAREMO_2020_450022A  = 6935; //F5A
	static $OT_BAREMO_2020_450022B = 6936; //F5A
 

	static $OT_BAREMO_430064 = 297; //F6
    	static $OT_BAREMO_2017_430064 = 901; //F6
    	static $OT_BAREMO_2018_430064 = 2259; //F6
		
	// Añadido Listo Revisado
	static $OT_BAREMO_2020_430064 = 6905; //F6
	
	static $OT_BAREMO_440043  = 306; //F7A
	static $OT_BAREMO_440043A = 307; //F7A
	static $OT_BAREMO_440043B = 308; //F7A
	static $OT_BAREMO_440043C = 309; //F7A
	static $OT_BAREMO_440051  = 310; //F7A
	static $OT_BAREMO_440051A = 311; //F7A
	static $OT_BAREMO_440051B = 312; //F7A
	static $OT_BAREMO_440051C = 313; //F7A
	
    static $OT_BAREMO_2017_440043A  = 907; //F7A
	static $OT_BAREMO_2017_440043B = 908; //F7A
	static $OT_BAREMO_2017_440043C = 909; //F7A
	static $OT_BAREMO_2017_440043D = 910; //F7A
	
	static $OT_BAREMO_2017_440051A  = 911; //F7A
	static $OT_BAREMO_2017_440051B = 912; //F7A
	static $OT_BAREMO_2017_440051C = 913; //F7A
	static $OT_BAREMO_2017_440051D = 914; //F7A
	    static $OT_BAREMO_2018_440043  = 2265; //F7A
	    static $OT_BAREMO_2018_440051  = 2266; //F7A
	
	// Añadido Listo Revisado
	static $OT_BAREMO_2020_440043A  = 6913; //F7A
    static $OT_BAREMO_2020_440043B = 6914; //F7A
    static $OT_BAREMO_2020_440043C = 6915; //F7A
    static $OT_BAREMO_2020_440043D = 6916; //F7A
	
    static $OT_BAREMO_2020_440051A  = 6917; //F7A
    static $OT_BAREMO_2020_440051B = 6918; //F7A
    static $OT_BAREMO_2020_440051C = 6919; //F7A
    static $OT_BAREMO_2020_440051D = 6920; //F7A
	
	static $OT_BAREMO_460010 = 337; //F8
	static $OT_BAREMO_460010A = 338; //F8
	static $OT_BAREMO_460044 = 341; //F8
	static $OT_BAREMO_460044A = 342; //F8
	static $OT_BAREMO_460036 = 339; //F8
	static $OT_BAREMO_460036A = 340; //F8
    static $OT_BAREMO_2017_460010A = 861; //F8
    static $OT_BAREMO_2017_460010B = 862; //F8
    static $OT_BAREMO_2017_460044A = 865; //F8
	static $OT_BAREMO_2017_460044B = 866; //F8
	static $OT_BAREMO_2017_460036A = 863; //F8
    static $OT_BAREMO_2017_460036B = 864; //F8
    static $OT_BAREMO_2018_460010 = 2287; //F8
    static $OT_BAREMO_2018_460044 = 2289; //F8
    static $OT_BAREMO_2018_460036 = 2288; //F8

	// Añadiendo
	static $OT_BAREMO_2020_460010A = 6941; //F8
    static $OT_BAREMO_2020_460010B = 6942; //F8
    static $OT_BAREMO_2020_460044A = 6945; //F8
    static $OT_BAREMO_2020_460044B = 6946; //F8
    static $OT_BAREMO_2020_460036A = 6943; //F8
    static $OT_BAREMO_2020_460036B = 6944; //F8

	/**=LIQUIDACIONES=**/
	static $LIQ_ST_ENCONCILIACION = 1;
	static $LIQ_ST_GESTIONRESERVAS = 2;
	static $LIQ_ST_APROBADA = 3;
	static $LIQ_ST_RECHAZADA = 4;
	static $LIQ_ST_CAUSADA = 5;
	static $LIQ_ST_FACTURADA = 6;
	static $LIQ_ST_CANCELADA = 7;
	static $LIQ_ST_ACTIVAS = "3,5,6";
	static $LIQ_ST_ACTIVASTOTAL = "1,2,3,5,6";

	/**=SOLICITUDES H=**/
	static $SOL_ST_SOLICITADA=1;
	static $SOL_ST_APROBADA=2;
	static $SOL_ST_RECHAZADA=3;
	static $SOL_ST_CANCELADA=4;

	/**=PEDIDOS=**/
	static $PED_ST_PENDIENTE=1;
	static $PED_ST_GESTIONADO=2;
	static $PED_ST_ENTREGADO=3;
	static $PED_ST_CANCELADO=4;

	/**=RESERVAS=**/
	static $RES_ST_CREADA=1;
	static $RES_ST_LIBERADA=2;
	static $RES_ST_CONTABILIZADA=3;

	/**=VIABILIDADES=**/

	static $VB_TAREA_ING = "INGENIERIA";

	static $GRP_SEG_CONSULTA = 2;
	static $GRP_SEG_REGISTRO = 3;
	static $GRP_SEG_CIERRE = 4;
	static $GRP_SEGMENTO = "2,3,4";
	static $GRP_INGENIERIA = 5;
	static $GRP_OP_CENTRAL = 6;
	static $GRP_REGISTRO_RED = 7;
	static $GRP_EECC = 8;
	static $GRP_OP_ZONA_PE = 10;
	static $GRP_OP_ZONA_PI = 11;
	static $GRP_ONMS = 12;
	static $GRP_CONSTRUCCION_FO = 13;
	static $GRP_GESTOR_OTS = 14;
	static $GRP_SOPORTE_TECNICO = 16;

	//Estados Viabilidades
	static $VB_ST_CREACION = 0;
	static $VB_ST_ESTUDIO = 1;
	static $VB_ST_EJECUCION = 2;
	static $VB_ST_APROBACION = 3;
	static $VB_ST_REVISION = 4;
	static $VB_ST_CANCELADA = 5;
	static $VB_ST_APROBADA = 6;
	static $VB_ST_APLAZADA = 7;
	static $VB_ST_TERMINADA = 8;
	static $VB_ST_CERRAR = 9;



//Estados CLUSTER
	static $CLUS_ST_CREACION = 0;
	static $CLUS_ST_CREADA = 1;
	static $CLUS_ST_CANCELADA=2;


//Estados SUBCLUSTER
	static $SUB_ST_CREACION = 0;
	static $SUB_ST_CREADA = 1;
	static $SUB_ST_CANCELADA=2;


	//Cronogramas
	static $TAREA_ENTREGAMATERIALES=2;
	static $VB_ST_PAGO = 1;
	static $VB_ST_CANCPAGO = 0;


	/**=OTRAS OPCIONES=**/
	//Menus
	static $MENU_PPTO_NEW = 101;
	static $MENU_PPTO_TRAY = 102;
	static $MENU_PPTO_SRC = 103;
	static $MENU_PPTO_SRCTRAY = 104;

	static $MENU_OT_MAKE = 201;
	static $MENU_OT_TRAY = 202;
	static $MENU_OT_SRC = 203;

	static $MENU_LQ_SRC = 301;
	static $MENU_LQ_TRAY = 302;

	static $MENU_ADD_VB = 701;
	static $MENU_EDIT_VB = 702;
	static $MENU_SRCH_VB = 703;
	static $MENU_UPD_VB = 704;

	static $MENU_SRCH_CS = 301;
	static $MENU_UPD_CS = 302;







	static $MENU_ADD_CLUS = 20001;
	static $MENU_EDIT_CLUS = 20002;
	static $MENU_SRCH_CLUS = 20003;










	static $DEPTO_AMAZONAS = 91;
	static $DEPTO_SAN_ANDRES = 88;

	static $CLASE_MO_UTILIDAD = 14;
    	static $CLASE_MO_UTILIDAD_2017 = 38;
    	static $CLASE_MO_UTILIDAD_2018 = 72;
//-----------------modificacion para utilidades 2019 digired 17.06.2019-----
    	static $CLASE_MO_UTILIDAD_2019 = 121;
    	static $CLASE_MO_UTILIDAD_2019_1 = 131;
    	static $CLASE_MO_UTILIDAD_2019_2 = 146;
    	static $CLASE_MO_UTILIDAD_2019_3 = 149;
	static $CLASE_MO_UTILIDAD_2019_4 = 150;
	static $CLASE_MO_UTILIDAD_2019_5 = 162;
	static $CLASE_MO_UTILIDAD_2019_6 = 156;
//--------------------------------------------------------------------------

	static $PREF_FADMIN = 11;
	static $PREF_VB_UMBRAL = 24;

	static $ID_CLASE_H = 11;

	static $ID_PUNTOSP = 533;

// ******************** EXTENSIONES ***********************
	static $EXTENSIONS = array(
		"php","sh","js"
	);
?>
