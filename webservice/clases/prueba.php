<?php
	/*require_once("db.php");
	require_once("Encriptador.php");
	require_once("WebServiceTRS.php");
	//require_once("wsse/WSSESoapServer.php");
    require_once('WebServiceTRS.php');
	
	$webservice = new WebServiceTRS();
	
	$parametros = array('numContrato' => '11572','anio' => '2011' ,'marcaTiempo' => '2013-04-20T23:00:00', );
	
	
	
	$mensajesArray = $webservice -> ObtenerPQRs($parametros);
	
	var_dump($mensajesArray);*/
		ini_set("display_errors", 1);
		
	
	require_once('C:/Proyectos/23231_Vulnerabilidades_Sigres_Manual/php/adodb5/adodb.inc.php');
	require_once('C:/Proyectos/23231_Vulnerabilidades_Sigres_Manual/php/clases/UsuariosWS.php');
	require_once('C:/Proyectos/23231_Vulnerabilidades_Sigres_Manual/php/clases/PERFILUSUARIO.php');
	
	$usuariows = new UsuariosWS();
	$usuariows -> crearBD($opcion_bd);
	
	//$objusuario = $usuariows->consultar("where uws_usuario='jmendega'",1);
	
	//$objusuario = $usuariows->consultarId(2);
	
	$objusuario = $usuariows->consultar();
	
	foreach ($objusuario as $row) {
		
		echo $row->getUwsUsuario();
		echo '<br>';
		
	}
	
	$mensajesArray = array();
		$perfilusuario = new PERFILUSUARIO();
		
		$perfilusuario -> crearBD();
		
		$objPerfil = $perfilusuario -> consultar('','','','select  profile AS perfil from perfilusuario GROUP BY profile ORDER BY profile');
		
		foreach ($objPerfil as $row) {
			
			$mensajesArray[] = $row->getProfile();	
			
		}
		 
		 $VAR = FALSE;
		 
		 ECHO count($VAR);

	//$r = $objusuario -> eliminar();
	
		
	//var_dump($objusuario);
	
	
	
	
	
	
	
?>