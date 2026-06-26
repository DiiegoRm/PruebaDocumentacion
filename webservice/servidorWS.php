<?php
/* Inclusion de clases */
ini_set("display_error",1);
require_once("../../static.inc.php");
require_once("clases/Encriptador.php");

require_once("clases/WebServiceUsers.php");
require_once("wsse/WSSESoapServer.php");
/* Código de error por defecto */
$codigoError = 500;
$msgError = "Internal Error Server 000";
/* Incializacion de nuevo documento XML */
$soapDOM = new DOMDocument();
$soapDOM -> load("php://input");
/* Inicializacion de servidor WS-Security */
$soapWS = new WSSESoapServer($soapDOM);
/* Inicializacion de servidor SOAP */
$srv= new SoapServer(WSDL_SIGRES);
/*try {
	if ($soapWS -> process();//) {
		$srv->setClass("WebServiceSIGRES");
		$srv->handle($soapWS -> saveXML());
		exit;
	}
} catch (Exception $ex) {
	$codigoError = $ex -> getCode();
	$msgError = $ex -> getMessage();
}*/
$soapWS -> process();
                $srv->setClass("WebServiceUsers");
                $srv->handle($soapWS -> saveXML());
//$srv -> fault($codigoError, $msgError);
?>
