<?php
function isLoggedIn(){
    return isset($_SESSION['loggedin']);
}
function setRefreshUrl($url){
	$_SESSION['refresh.url'] = $url;
}

function getRefreshUrl(){
    return $_SESSION['refresh.url'];
}
function getBrowser(){
    return $_SESSION['bi_browser']." ".$_SESSION['bi_version']." / ".$_SESSION['bi_platform']." ".$_SESSION['bi_platver'];
}

function saveCookieRM(){
	setcookie("sgp_appuser", rawurlencode($_POST['username']), time()+31536000); //
}
function deleteCookieRM(){
    if(isset($_COOKIE['sgp_appuser'])){
       setcookie("sgp_appuser", "", time()-60*60*24*100);
    }
}

function getCookieRM(){
    return isset($_COOKIE['sgp_appuser'])?htmlspecialchars($_COOKIE['sgp_appuser']):"";
}

function isLoginForm(){
    return isset($_POST['username']) && isset($_POST['password']);
}

function rememberMe(){
    return isset($_POST['remember']);
}

/*function getMenu(){
    return isset($_GET['menu'])?$_GET['menu']:"0";
}*/

function getMenu(){
  if (isset($_GET['menu'])){
    $menuget=$_GET['menu'];
      return htmlspecialchars($menuget);
  }else {
    return "0";
  }
}
function getAppUser(){
    return unserialize($_SESSION['loggedin']);
}
function printDebug(){
	if(ENABLE_APP_DEBUG){
		echo "<div id='contenido_principal' align='center' style='margin:0 auto;width:100%'>";
		echo "<br class=\"clear\"/>";
		echo "SESSION:<br />";
		print_r($_SESSION);
		echo "<br />POST:<br />";
		print_r(htmlspecialchars($_POST));
    //print_r($_POST);
		echo "<br />GET:<br />";
		print_r(htmlspecialchars($_GET));
    //print_r($_GET);
		echo "<br />REQUEST:<br />";
		print_r(htmlspecialchars($_REQUEST));
    //print_r($_REQUEST);
		//echo "<br />SERVER:<br />";
		//print_r($_SERVER);
		//echo "<br />USER:<br />";
		//print_r($appuser);
		echo "</div>";
	}
}
function printMessage($message,$type){
	echo "<br /><br /><br />";
	echo "<div class=\"section\">";
	echo "	<div class=\"info\">";
	echo "		<table style=\"width:850px\">";
	echo "		 <tr><td>";
	//echo "			<div class=\"msg-$type\">$message<br /></div>";
  echo "        <div class='msg-".htmlspecialchars($type)."'>".htmlspecialchars($message)."</div>";
	echo "		 </td></tr>";
	if($type!="ok"){
		echo "<tr><td>";
		echo "		<center><button onclick=\"javascript:window.history.go(-1); return false;\"><span class=\"round\"><span>Regresar</button></center>";
		echo "	 </td></tr>";
	} else if($type=="info"){
		echo "<tr><td>";
		echo "		<center><button onclick=\"javascript:window.history.go(-1); return false;\"><span class=\"round\"><span>Continuar</button></center>";
		echo "	 </td></tr>";
	}
	echo "</table>";
	echo "	</div>";
	echo "</div>";
	if($type=="ok"){
		//echo htmlspecialchars("<meta http-equiv=Refresh content=1;url=\"?menu=".getMenu()."\">");
    echo "<meta http-equiv=Refresh content=1;url=\"?menu=".getMenu()."\">";
	}
}
function printAndStay($message,$type){
	echo "<br /><br /><br />";
	echo "<div class=\"section\">";
	echo "	<div class=\"info\">";
	echo "		<table style=\"width:850px\">";
	echo "		 <tr><td>";
  //echo "			<div class=\"msg-$type\">$message<br /></div>";
  echo "        <div class='msg-".htmlspecialchars($type)."'>".htmlspecialchars($message)."</div>";
	echo "		 </td></tr>";
	if($type!="ok"){
		echo "<tr><td>";
		echo "		<center><button onclick=\"javascript:window.history.go(-1); return false;\"><span class=\"round\"><span>Regresar</button></center>";
		echo "	 </td></tr>";
	} else if($type=="info"){
		echo "<tr><td>";
		echo "		<center><button onclick=\"javascript:window.history.go(-1); return false;\"><span class=\"round\"><span>Continuar</button></center>";
		echo "	 </td></tr>";
	}
	echo "</table>";
	echo "	</div>";
	echo "</div>";
	if($type=="ok"){
		if(hasVal(getRefreshUrl())){
			echo "<meta http-equiv=Refresh content=1;url=\"".getRefreshUrl()."\">";
		} else {
			//echo htmlspecialchars("<meta http-equiv=Refresh content=1;url=\"?menu=".getMenu()."\">");
      echo "<meta http-equiv=Refresh content=1;url=\"?menu=".getMenu()."\">";
		}
	}
}
function printShortMsg($message,$type){
	echo "<br /><br /><br />";
	echo "<div class='section'>";
	echo "<div class='info'>";
	echo "	<table style='width:850px;'>";
    echo "     <tr><td><br />";
    //echo htmlspecialchars("        <div class='msg-$type'>$message</div>");
    echo "        <div class='msg-".htmlspecialchars($type)."'>".htmlspecialchars($message)."</div>";
    echo "     </td></tr>";
	echo "	</table>";
	echo "</div>";
	echo "</div>";
}
function paginate($maxPage, $pageNO, $regCount){
	$maxLinks = 40;

	if($pageNO < $maxLinks)$sp = 1;
	elseif($pageNO >= ($maxPage - floor($maxLinks / 2)) )$sp = $maxPage - $maxLinks + 1;
	elseif($pageNO >= $maxLinks)$sp = $pageNO  - floor($maxLinks/2);

	if($maxPage > 1){
		if($pageNO == 1){
			echo "<span class=\"inactive\">Primera</span>&nbsp;";
			echo "<span class=\"inactive\">Anterior</span>&nbsp;";
		} else {
			echo "<a href=\"javascript:chgPage(1);\">Primera</a>&nbsp;";
			echo "<a href=\"javascript:prevPage();\">Anterior</a>&nbsp;";
		}
    $spf=($sp + $maxLinks -1);
    if($spf>=0){
		for($i=$sp; $i<= $spf; $i++){
			if($i <= $maxPage){
				if($i!=$pageNO){
					//echo htmlspecialchars("<a href=\"javascript:chgPage($i);\">$i</a>&nbsp;");
          echo "<a href=\"javascript:chgPage(".htmlspecialchars($i).");\">".htmlspecialchars($i)."</a>&nbsp;";
				} else {
					echo "<span class=\"active\"><b>[".htmlspecialchars($i)."]</b></span>&nbsp;";
				}
			}
		}
  }
		if($pageNO == $maxPage){
			echo "<span class=\"inactive\">Siguiente</span>&nbsp;";
			echo "<span class=\"inactive\">Ultima</span>&nbsp;";
		} else {
			echo "<a href=\"javascript:nextPage();\">Siguiente</a>&nbsp;";
			echo "<a href=\"javascript:chgPage(".htmlspecialchars($maxPage).");\">Ultima</a>&nbsp;";
		}
	}
	echo "<span class=\"inactive\">[Pag: ".htmlspecialchars($pageNO)."/".htmlspecialchars($maxPage)."|Reg: ".htmlspecialchars($regCount)."]</span>";
}
function getClassSort($field, $sort, $order){
	if($field != $sort) return "null";
	else {
		return getNextSortType($order);
	}
}
function getNextSortType($order){
	if($order == 'null') return 'ASC';
	if($order == 'ASC') return 'DESC';
	if($order == 'DESC') return 'ASC';
}
function printField($key,$name){
  $key=htmlspecialchars($key);
	return "<input type=\"text\" id=\"$key\" name=\"filter[$key]\" value=\"".$_POST['filter'][$key]."\" class=\"formInputSearch\"/>";
}
function printButtonBar($opt){
	echo "<table class='data-ro' id='buttonset-1'><tr>";
	printActionButtonBar($opt);
	echo "</tr></table><hr/>";
}
function printButtonSet($user,$fields){
	echo "<table class='data-ro' id='buttonset-1'><tr>";
	printActionButtons($user);
	echo "<td class='none'>Buscar:</td>";
	$code = $_POST['loc_code'];
	echo "<td class='none'><select name='loc_code' id='loc_code'><option value='-1'>---SELECCIONE---</option>";
	if(getMenu() == "208"){
		foreach($fields as $key=>$name){
			if($key == "aud" || $key == "vigencia"){}else{
				echo "<option value='".htmlspecialchars($key)."'".((htmlspecialchars($code)==htmlspecialchars($key))?' selected':'').">".htmlspecialchars($name)."</option>\n";
			}
		}
	}else{
		foreach($fields as $key=>$name){
			//echo htmlspecialchars("<option value='$key'".(($code==$key)?' selected':'').">$name</option>\n");
		echo "<option value='".htmlspecialchars($key)."'".((htmlspecialchars($code)==htmlspecialchars($key))?' selected':'').">".htmlspecialchars($name)."</option>\n";
		}
	}
	echo "</select></td>";
	echo "<td class='none'>";
	$selected = $_POST['loc_oper'];
	//echo htmlspecialchars("<select name=\"loc_oper\" id=\"loc_oper\">
  echo "<select name=\"loc_oper\" id=\"loc_oper\">
		<option value='LIKE'".(htmlspecialchars($selected)=='LIKE'?" selected":"").">CONTIENE</option>
		<option value='='".(htmlspecialchars($selected)=='='?" selected":"").">ES IGUAL</option>
		<option value='&gt;'".(htmlspecialchars($selected)=='>'?" selected":"").">ES MAYOR</option>
		<option value='&gt;='".(htmlspecialchars($selected)=='>='?" selected":"").">ES MAYOR O IGUAL</option>
		<option value='&lt;'".(htmlspecialchars($selected)=='<'?" selected":"").">ES MENOR</option>
		<option value='&lt;='".(htmlspecialchars($selected)=='<='?" selected":"").">ES MENOR O IGUAL</option>
		<option value='!='".(htmlspecialchars($selected)=='!='?" selected":"").">ES DIFERENTE</option>
		<option value='NOT LIKE'".(htmlspecialchars($selected)=='NOT LIKE'?" selected":"").">NO CONTIENE</option>
		<option value='IS NULL'".(htmlspecialchars($selected)=='IS NULL'?" selected":"").">ES VACIO</option>
		<option value='IS NOT NULL'".(htmlspecialchars($selected)=='IS NOT NULL'?" selected":"").">NO ES VACIO</option>
	</select></td>";
	//echo htmlspecialchars("<td class='none'><input type='text' size='20' name='loc_name' id='loc_name' onkeydown='onKeyPressed(event)' value='".$_POST['loc_name']."'/></td>");
  echo "<td class='none'><input type='text' size='20' name='loc_name' id='loc_name' onkeydown='onKeyPressed(event)' value='".htmlspecialchars($_POST['loc_name'])."'/></td>";
	echo "<td class='none'><button type='button' onclick='returnSearch();'>Buscar</button><button type='button' onclick='clear_form();'>Limpiar</button></td>";
	echo "</tr></table><hr/>";
}

function printButtonSetarm($user,$fields){
	echo "<table class='data-ro' id='buttonset-1'><tr>";
	printActionButtons($user);
	echo "<td class='none'>Buscar:</td>";
	$code = $_POST['loc_code'];
	echo "<td class='none'><select name='loc_code' id='loc_code'><option value='-1'>---SELECCIONE---</option>";
	foreach($fields as $key=>$name){
		//echo htmlspecialchars("<option value='$key'".(($code==$key)?' selected':'').">$name</option>\n");
    //echo "<option value='$key'".(($code==$key)?' selected':'').">$name</option>\n";
    echo "<option value='".htmlspecialchars($key)."'".((htmlspecialchars($code)==htmlspecialchars($key))?' selected':'').">".htmlspecialchars($name)."</option>\n";
	}
	echo "</select></td>";
	echo "<td class='none'>";
	$selected = $_POST['loc_oper'];
	//echo htmlspecialchars("<select name=\"loc_oper\" id=\"loc_oper\">
  echo "<select name=\"loc_oper\" id=\"loc_oper\">
		<option value='LIKE'".(htmlspecialchars($selected)=='LIKE'?" selected":"").">CONTIENE</option>
		<option value='='".(htmlspecialchars($selected)=='='?" selected":"").">ES IGUAL</option>
		<option value='&gt;'".(htmlspecialchars($selected)=='>'?" selected":"").">ES MAYOR</option>
		<option value='&gt;='".(htmlspecialchars($selected)=='>='?" selected":"").">ES MAYOR O IGUAL</option>
		<option value='&lt;'".(htmlspecialchars($selected)=='<'?" selected":"").">ES MENOR</option>
		<option value='&lt;='".(htmlspecialchars($selected)=='<='?" selected":"").">ES MENOR O IGUAL</option>
		<option value='!='".(htmlspecialchars($selected)=='!='?" selected":"").">ES DIFERENTE</option>
		<option value='NOT LIKE'".(htmlspecialchars($selected)=='NOT LIKE'?" selected":"").">NO CONTIENE</option>
		<option value='IS NULL'".(htmlspecialchars($selected)=='IS NULL'?" selected":"").">ES VACIO</option>
		<option value='IS NOT NULL'".(htmlspecialchars($selected)=='IS NOT NULL'?" selected":"").">NO ES VACIO</option>
	</select></td>";
	//echo htmlspecialchars("<td class='none'><input type='text' size='20' name='loc_name' id='loc_name' onkeydown='onKeyPressed(event)' value='".$_POST['loc_name']."'/></td>");
  echo "<td class='none'><input type='text' size='20' name='loc_name' id='loc_name' onkeydown='onKeyPressed(event)' value='".htmlspecialchars($_POST['loc_name'])."'/></td>";
	echo "<td class='none'><button type='button' onclick='returnSearcharm();'>Buscar</button><button type='button' onclick='clear_form();'>Limpiar</button></td>";
	echo "</tr></table><hr/>";
}

function printFilterGrid($fields,$start=1){
	echo "<tr>";
  //$fields=htmlspecialchars($fields);
  $fields=$fields;
	for($i=0; $i<$start;$i++)echo "<td>&nbsp;</td>\n";
	foreach($fields as $key=>$name){
		echo "<td scope=\"col\">".printField(htmlspecialchars($key),htmlspecialchars($name))."</td>\n";
	}
	echo "</tr>";
}

function printColumns($fields){
	// var_dump($fields);die();
  //$fields=htmlspecialchars($fields);
	foreach($fields as $key=>$name){
		echo "<td scope=\"col\" class='thEq'>".printColumn(htmlspecialchars($key),htmlspecialchars($name))."</td>\n";
	}
}
function getAllSQLFilters($sqloper="AND"){
	$result = " ";
	$filter = $_POST['filter'];
	$n=count($filter);
	if($n > 0){
		foreach( $filter as $key => $value){
			if(hasVal($value)){
				switch($value){
					case "(vacio)":
						//$result .= "$sqloper ({$key} IS NULL OR {$key} = '') ";
            $result .= "$sqloper ({$key} IS NULL) ";
					break;
					case "(!vacio)":
						$result .= "$sqloper ({$key} IS NOT NULL OR {$key} != '') ";
					break;
					case "(rojo)":
						$result .= "HAVING alerta = 'rojo' ";
					break;
					case "(amarillo)":
						$result .= "HAVING alerta = 'amarillo' ";
					break;
					case "(verde)":
						$result .= "HAVING alerta = 'verde' ";
					break;
					default:
						$result .= "$sqloper CAST({$key} AS CHAR) LIKE '%{$value}%' ";
					break;
				}
				/*if($value === "(vacio)"){
					$result .= "$sqloper ({$key} IS NULL OR {$key} = '') ";
				} else if($value === "(!vacio)"){
					$result .= "$sqloper ({$key} IS NOT NULL OR {$key} != '') ";
				} else {
					$result .= "$sqloper {$key} LIKE '%{$value}%' ";
				}*/
			}
		}
	}
	return $result;
}

function getSQLFilters($sqloper="AND"){
	$result = " ";
	if($_POST['captureState'] && strlen($_POST['captureState'])>0){ //filtros
		switch($_POST['loc_oper']){
			case 'LIKE':
			case 'NOT LIKE':
				$result.= "$sqloper $_POST[loc_code] $_POST[loc_oper] '%$_POST[loc_name]%'";
				break;
			case 'IS NULL':
			case 'IS NOT NULL':
				$result.= "$sqloper $_POST[loc_code] $_POST[loc_oper]";
				break;
			default:
				$result.= "$sqloper $_POST[loc_code] $_POST[loc_oper] '$_POST[loc_name]'";
		}
	}
	return $result;
}
function getSQLSort($sort="", $order=""){
	$result = "";
	if($_GET['sort']){
		$result.= " ORDER BY $_GET[sort] $_GET[order]";
	}
	else if(hasVal($sort) && hasVal($order)) {

		$result.= " ORDER BY $sort $order";
	}
	return $result;
}
function printColumn($field, $name){

	$sort=isset ($_GET['sort'])?$_GET['sort']:"0";
	$order=isset ($_GET['order'])?$_GET['order']:"null";
	$pageNO=isset ($_POST['pageNO'])?$_POST['pageNO']:"1";

	$locCode = $_POST['loc_code'];
	$locName = $_POST['loc_name'];
	$locState = $_POST['captureState'];

  $sort=htmlspecialchars($sort);
	$order=htmlspecialchars($order);
	$pageNO=htmlspecialchars($pageNO);

	$locCode = htmlspecialchars($locCode);
	$locName = htmlspecialchars($locName);
	$locState = htmlspecialchars($locState);

	if($field != $sort)$order="null";
	return "<a href=\"#\" onclick=\"sortAndSearch('$field', '".
		getNextSortType($order)."','".
		getMenu()."','$locState','$locCode','$locName');\" class=\"".
		getClassSort($field,$sort,$order)."\">$name</a>";
}

function printActionButtons($user){
	if($user->isAdmin()||$user->isInRole($ADMINISTRACION)){
		echo "<td class='none'>";
		//echo htmlspecialchars("<button type='button' onclick=\"returnAdd('".getMenu()."');\"><span>Adicionar</button>\n");
    echo "<button type='button' onclick=\"returnAdd('".getMenu()."');\"><span>Adicionar</button>\n";
		echo "<button type=\"button\" onclick=\"returnDisable();\"><span>Desactivar</button>\n";
		echo "<button type=\"button\" onclick=\"returnEnable();\"><span>Activar</button>\n";
		echo "<button type=\"button\" onclick=\"returnDelete();\"><span>Eliminar</button>\n";
		if(getMenu() == "208"){
			echo "<br><button style='margin-top: 10px;' id='btnExport' class='ui-button ui-corner-all ui-widget' type=\"button\"><span>Exportar</button>\n";
			echo "<button style='margin-top: 10px;' class='ui-button ui-corner-all ui-widget' type=\"button\" onclick=\"returnAuditar();\"><span class='ui-button-icon ui-icon ui-icon-search'></span>Auditar</button>\n";
		}
		echo "</td>\n";
	}else if(getMenu() == "208"){
			echo "<td class='none'>";
			//echo htmlspecialchars("<button type='button' onclick=\"returnAdd('".getMenu()."');\"><span>Adicionar</button>\n");
			echo "<button type='button' onclick=\"returnAdd('".getMenu()."');\"><span>Adicionar</button>\n";
			echo "<button type=\"button\" onclick=\"returnDisable();\"><span>Desactivar</button>\n";
			echo "<button type=\"button\" onclick=\"returnEnable();\"><span>Activar</button>\n";
			echo "<button type=\"button\" onclick=\"returnDelete();\"><span>Eliminar</button>\n";
			echo "<br><button style='margin-top: 10px;' id='btnExport' class='ui-button ui-corner-all ui-widget' type=\"button\"><span>Exportar</button>\n";
			echo "<button style='margin-top: 10px;' class='ui-button ui-corner-all ui-widget' type=\"button\" onclick=\"returnAuditar();\"><span class='ui-button-icon ui-icon ui-icon-search'></span>Auditar</button>\n";
			echo "</td>\n";
		}
}
function printActionButtonBar($opt, $fields = NULL){
	// var_dump($opt);
	if(!empty($opt)){
		echo "<td class='none'>";
		if(!empty($opt['add'])){
			echo "<button type='button' onclick=\"".htmlspecialchars($opt['add'])."\"><span>Adicionar</button>\n";
		}
		if(!empty($opt['disable'])){
			echo "<button type=\"button\" onclick=\"".htmlspecialchars($opt['disable'])."\"><span>Desactivar</button>\n";
		}
		if(!empty($opt['enable'])){
			echo "<button type=\"button\" onclick=\"".htmlspecialchars($opt['enable'])."\"><span>Activar</button>\n";
		}
		if(!empty($opt['delete'])){
			echo "<button type=\"button\" onclick=\"".htmlspecialchars($opt['delete'])."\"><span>Eliminar</button>\n";
		}
		if(!empty($opt['make'])){
			echo "<button type=\"button\" onclick=\"".htmlspecialchars($opt['make'])."\"><span>Generar</button>\n";
		}
		if(isset($opt['export'])){
			echo "<button style='margin-top: 0px;' id='btnExport' class='ui-button ui-corner-all ui-widget' type=\"button\"><span>Exportar</button>\n";
		}
		if(!empty($opt['auditar'])){
			echo "<button type=\"button\" onclick=auditar()><span>Generar</button>\n";
		}
		echo "</td>\n";
	}
	if(getMenu() == "208" && $fields != NULL){
		echo "<td class='none'>Buscar:</td>";
		$code = $_POST['loc_code'];
		echo "<td class='none'>";
		echo "<select name='loc_code' id='loc_code'><option value='-1'>---SELECCIONE---</option>\n";
		foreach($fields as $key=>$name){
			if($key == "aud" || $key == "vigencia"){}else{
				echo "<option value='".htmlspecialchars($key)."'".((htmlspecialchars($code)==htmlspecialchars($key))?' selected':'').">".htmlspecialchars($name)."</option>\n";
			}
		}
		echo "</select>";
		echo "</td>\n";
		echo "<td class='none'>";
		$selected = $_POST['loc_oper'];
		//echo htmlspecialchars("<select name=\"loc_oper\" id=\"loc_oper\">
		echo "<select name=\"loc_oper\" id=\"loc_oper\">
				<option value='LIKE'".(htmlspecialchars($selected)=='LIKE'?" selected":"").">CONTIENE</option>
				<option value='='".(htmlspecialchars($selected)=='='?" selected":"").">ES IGUAL</option>
				<option value='&gt;'".(htmlspecialchars($selected)=='>'?" selected":"").">ES MAYOR</option>
				<option value='&gt;='".(htmlspecialchars($selected)=='>='?" selected":"").">ES MAYOR O IGUAL</option>
				<option value='&lt;'".(htmlspecialchars($selected)=='<'?" selected":"").">ES MENOR</option>
				<option value='&lt;='".(htmlspecialchars($selected)=='<='?" selected":"").">ES MENOR O IGUAL</option>
				<option value='!='".(htmlspecialchars($selected)=='!='?" selected":"").">ES DIFERENTE</option>
				<option value='NOT LIKE'".(htmlspecialchars($selected)=='NOT LIKE'?" selected":"").">NO CONTIENE</option>
				<option value='IS NULL'".(htmlspecialchars($selected)=='IS NULL'?" selected":"").">ES VACIO</option>
				<option value='IS NOT NULL'".(htmlspecialchars($selected)=='IS NOT NULL'?" selected":"").">NO ES VACIO</option>
			</select></td>";
			//echo htmlspecialchars("<td class='none'><input type='text' size='20' name='loc_name' id='loc_name' onkeydown='onKeyPressed(event)' value='".$_POST['loc_name']."'/></td>");
		echo "<td class='none'><input type='text' size='20' name='loc_name' id='loc_name' onkeydown='onKeyPressed(event)' value='".htmlspecialchars($_POST['loc_name'])."'/></td>";
		echo "<td class='none'><button type='button' onclick='returnEquipos();'>Buscar</button><button type='button' onclick='clear_form();'>Limpiar</button></td>";
	}
}
function padZeroLeft($str, $len){
	return str_pad($str, $len, "0", STR_PAD_LEFT);
}
function StartsWith($Haystack, $Needle){
    return strpos($Haystack, $Needle) === 0;
}
function getVal($var, $defval="",$scape=false){
//$var=htmlspecialchars($var);
	if (isset($var) && !empty($var)){
		return $scape?"'".$var."'":$var;
	}
	else {
		return $scape && $defval!="null"?"'".$defval."'":$defval;
	}
}
function getStrVal($var, $defval=""){
	return getVal($var,$defval,true);
}
function hasVal($var){
	return isset($var)&&!empty($var) && $var!="''"&&$var!="null";
}

function getPostStr($name,$defval="null",$scape="'"){
	$result = $defval;
	$value = trim($_POST[$name]);
	if(!empty($value) || $value=='0'){
		$result = $scape.htmlspecialchars($value).$scape;
	}
	return $result;
}

function getPostNum($name,$defval="null"){
	$result = $defval;
	$value = trim($_POST[$name]);
	if(!empty($value) || $value=='0'){
		//$result = $value;
    $result = htmlspecialchars($value);
	}
	return $result;
}
//==============================================================================
function formatSeconds($secs) {
	$secs = (int)$secs;
	if ( $secs === 0 ) {
		return '0s';
	}
	// variables for holding values
	$mins  = 0;
	$hours = 0;
	$days  = 0;
	$weeks = 0;
	// calculations
	if ( $secs >= 60 ) {
		$mins = (int)($secs / 60);
		$secs = $secs % 60;
	}
	if ( $mins >= 60 ) {
		$hours = (int)($mins / 60);
		$mins = $mins % 60;
	}
	if ( $hours >= 24 ) {
		$days = (int)($hours / 24);
		$hours = $hours % 24;
	}
	if ( $days >= 7 ) {
		$weeks = (int)($days / 7);
		$days = $days % 7;
	}
	if ( $weeks >= 4 ) {
		$months = (int)($weeks / 7);
		//$years = $days % 7;
	}
	// format result
	$result = '';
	if ( $months ) {
		$result .= "{$months}mes ";
	}
	if ( $weeks ) {
		$result .= "{$weeks}sem ";
	}
	if ( $days ) {
		$result .= "{$days}d ";
	}
	if ( $hours ) {
		$result .= "{$hours}h ";
	}
	if ( $mins ) {
		$result .= "{$mins}m ";
	}
	if ( $secs ) {
		$result .= "{$secs}s ";
	}
	$result = rtrim($result);
  //$result=htmlspecialchars($result);
	return $result;
}
function notAuthorized(){
	echo "<br /><br /><br />";
	echo "<div class='section'>";
	echo "<div class='info'>";
	echo "	<table style='width:850px;'>";
    echo "     <tr><td><br />";
    echo "        <div class='msg-bad'>Privilegios insuficientes, contacte al administrador del sistema.</div>";
    echo "     </td></tr>";
	echo "	</table>";
	echo "</div>";
	echo "</div>";
}
function getRandomString() {
    $length = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $string = '';
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}
function setReport($hash,$title,$sql){

   // $_SESSION['rpt_'.$hash.'_1']=$title;
   // $_SESSION['rpt_'.$hash.'_2']=$sql;


  preg_match("/(\d{4})-(\d{2})-(\d{2})/", $title, $results1); //FIXED
  if(sizeof($results1) >= 0){
    $_SESSION['rpt_'.$hash.'_1']=$title; //OK
  }else{
    $_SESSION['rpt_'.$hash.'_1']=$title; //OK
  }
  preg_match("/(\d{4})-(\d{2})-(\d{2})/", $sql, $results2); //FIXED
  if(sizeof($results2) >= 0){
    $_SESSION['rpt_'.$hash.'_2']=$sql; //OK
  }else{
    $_SESSION['rpt_'.$hash.'_2']=$sql; //OK
  }

}
function clearReports(){
	foreach($_SESSION as $key=>$value){
		if(strpos($key,"rpt_") === 0){
			unset($_SESSION[$key]);
		}
	}
}
function getReport($hash,$id){
    return $_SESSION['rpt_'.$hash.'_'.$id];
}

function encrypt($int){
	/*$string = strval(intval($int) << 3);
	$hex='';
    for ($i=0; $i < strlen($string); $i++)
    {
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;*/
	return base_convert($int,10,16);
}


function decrypt($hex){
    /*$string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2)
    {
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return intval($string) >> 3;*/
		return base_convert($hex,16,10);
}
function getMoneyDB($val){
	return str_replace("$","",str_replace(",","",$val));
}
function humanFileSize($size,$unit="") {
  if( (!$unit && $size >= 1<<30) || $unit == "GB")
    return number_format($size/(1<<30),2)."GB";
  if( (!$unit && $size >= 1<<20) || $unit == "MB")
    return number_format($size/(1<<20),2)."MB";
  if( (!$unit && $size >= 1<<10) || $unit == "KB")
    return number_format($size/(1<<10),2)."KB";
  return number_format($size)." bytes";
}
?>
