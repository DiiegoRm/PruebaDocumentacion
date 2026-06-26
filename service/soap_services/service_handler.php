<?php
require_once('../../modules/class/user_controller.php');

$server = new SoapServer("wsdl/user.wsdl");
$server->setClass("User");
$server->handle();
?>