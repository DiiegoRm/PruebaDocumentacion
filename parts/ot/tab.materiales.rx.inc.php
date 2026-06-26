<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";
$id=decrypt(getVal($_GET['id'],"0"));
$ver=decrypt(getVal($_GET['ver'],"0"));
$mat_rx = true;
include_once "tab.materiales.xw.inc.php";
?>