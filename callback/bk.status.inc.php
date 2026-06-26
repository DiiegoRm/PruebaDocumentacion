<?php
ob_start();
include_once "../includes/session.php";
echo $_SESSION['BK_ACTION']."|".$_SESSION['BK_PROGRESS'];
?>