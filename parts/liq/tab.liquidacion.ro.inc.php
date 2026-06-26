<?php
include_once "../../includes/session.php";
include_once "../../includes/global.php";
include_once "../../includes/database.php";

$id=decrypt(getVal($_GET['id'],"0"));
$r =  db_query("SELECT * FROM `liquidaciones` WHERE `id` = $id");
$row = mysqli_fetch_array($r);
if (count($row)>0) {
include_once "tab.liquidacion.inc.php";
}
?>
