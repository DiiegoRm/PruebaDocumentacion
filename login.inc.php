<?php
function limpiarString($texto)
{
      $textoLimpio = preg_replace('([^A-Za-z0-9.])', '', $texto);	     					
      return $textoLimpio;
}

if(isLoginForm()){
	//$username = mysqli_real_escape_string($dbsgp,$_POST['username']);
	//$password = mysqli_real_escape_string($dbsgp,$_POST['password']);

	//$query = @db_query("SELECT id FROM `usuarios` WHERE `active`='Si' AND `login` = '$username' AND `password` = md5('$password')");
	$q= sprintf("SELECT id FROM `usuarios` WHERE `active`='Si' AND `login` = '%s'",
	mysqli_real_escape_string($dbsgp,limpiarString($_POST['username'])),
	mysqli_real_escape_string($dbsgp,md5 ($_POST['password'])));
		//$username = $_POST['username'],
		//$password = $_POST['password']);
	$query = @db_query($q);
	if (mysqli_num_rows($query) != 0) {
			// if login ok, then session is true
			$row = mysqli_fetch_array($query);
			if(count($row)>0){
			$_SESSION['loggedin'] = serialize(getLoginUser($row['id']));
			echo htmlspecialchars("hA ".$_SESSION['loggedin']);
			include_once "includes/browser.inc.php";
			$bi = get_browser_info();
			$bi_browser = $bi['browser'];
			preg_match("/(\d{4})-(\d{2})-(\d{2})/", $bi_browser, $results1);
			$bi_version = $bi['version'];
			preg_match("/(\d{4})-(\d{2})-(\d{2})/", $bi_version, $results2);
			$bi_platform = $bi['platform'];
			preg_match("/(\d{4})-(\d{2})-(\d{2})/", $bi_platform, $results3);
			$bi_platver = $bi['platver'];
			preg_match("/(\d{4})-(\d{2})-(\d{2})/", $bi_platver, $results4);
			if(sizeof($results1) > 0){
    $_SESSION['bi_browser'] = $bi_browser;
  }
			if(sizeof($results2) > 0){
			$_SESSION['bi_version'] = $bi_version;
			}
			if(sizeof($results3) > 0){
			$_SESSION['bi_platform'] = $bi_platform;
			}
			if(sizeof($results4) > 0){
			$_SESSION['bi_platver'] = $bi_platver;
			}

			if(rememberMe()){
				saveCookieRM();
			}
			else {
				deleteCookieRM();
			}
		}
		header("Location: index.php?menu=0");
		exit();
	} else {
		$isError = true;
		$message = "Usuario/contrase&ntilde;a incorrecto";
	}
} else {
	$isError = false;
	$message = "Ingrese sus datos";
}
include_once "header.inc.php";
include_once "content.inc.php";
header('Cache-Control: no cache'); //no cache

?>
 <div class="box_registro">
<form method='post'enctype='application/x-www-form-urlencoded'>
<strong>Ingresar a GestOT  |  Versi&oacute;n <?php echo SGP_VERSION?></strong>
<br />
<div class="ui-widget">
<div class="ui-state-<?php echo $isError?"error":"highlight" ?> ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
<p><span class="ui-icon ui-icon-<?php echo $isError?"alert":"info" ?>" style="float: left; margin-right: .3em;"></span>
<?php echo $message;?>.</p>
</div>
</div>
<br />
<table class="data-ro" id="login">
	<tr>
		<td class="title">Usuario:</td>
		<td class="field" style="width:220px">
			<input name="username" type="text" maxlength="30" autocomplete="off" pattern="[A-Za-z0-9.-/-\-]{1,30}" value="<?php echo getCookieRM()?>" class="formLogin"/>
		</td>
	</tr>
	<tr>
		<td class="title">Contrase&ntilde;a:</td>
		<td class="field"><input name="password" type="password" value="" class="formPassword"/></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: center"><button type="submit">Ingresar</button></td>
	</tr>
</table>
</form>
 </div>
<?php
include_once "footer.inc.php";
?>
