<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once '../includes/user.class.inc.php';

switch($_REQUEST["mode"]){
 case 'delvb':
	$id = getVal($_POST['id'],"null");
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$file = getSQLValue("SELECT archivo FROM adjuntosvb WHERE id=$id");
	$sql = "DELETE FROM adjuntosvb WHERE id=$id";
	if (db_query($sql,true) > 0){
		echo "OK";
		unlink(realpath(VB_FILE_PATH. DIRECTORY_SEPARATOR .basename($file)));
	}
	else echo "No fue posible Eliminar el archivo.";
	break;
 case 'delpp':
	$id = getVal($_POST['id'],"null");
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$file = getSQLValue("SELECT archivo FROM adjuntospp WHERE id=$id");
	$sql = "DELETE FROM adjuntospp WHERE id=$id";
	if (db_query($sql,true) > 0){
		echo "OK";
		unlink(realpath(PP_FILE_PATH. DIRECTORY_SEPARATOR .basename($file)));
	}
	else echo "No fue posible Eliminar el archivo.";
	break;
/* case 'delot':
	$id = getVal($_POST['id'],"null");
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$file = getSQLValue("SELECT archivo FROM adjuntosot WHERE id=$id");
	$sql = "DELETE FROM adjuntosot WHERE id=$id";
	if (db_query($sql,true) > 0){
		echo "OK";
		unlink(realpath(OT_FILE_PATH. DIRECTORY_SEPARATOR .basename($file)));
	}
	else echo "No fue posible Eliminar el archivo.";
	break;
 case 'delreg':
	$id = getVal($_POST['id'],"null");
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$file = getSQLValue("SELECT archivo FROM adjuntosreg WHERE id=$id");
	$sql = "DELETE FROM adjuntosreg WHERE id=$id";
	if (db_query($sql,true) > 0){
		echo "OK";
		unlink(realpath(REG_FILE_PATH. DIRECTORY_SEPARATOR .basename($file)));
	}
	else echo "No fue posible Eliminar el archivo.";
	break;*/
 case 'delsol':
	$id = getVal($_POST['id'],"null");
  $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
	$file = getSQLValue("SELECT archivo FROM cotizaciones WHERE id=$id");
	$sql = "DELETE FROM cotizaciones WHERE id=$id";
	if (db_query($sql,true) > 0){
		echo "OK";
		unlink(realpath(SOL_FILE_PATH. DIRECTORY_SEPARATOR .basename($file)));
	}
	else echo "No fue posible Eliminar el archivo.";
	break;
} // end switch
?>
