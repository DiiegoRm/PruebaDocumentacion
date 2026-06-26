<script src='https://www.hCaptcha.com/1/api.js' async defer></script>
<?php

function limpiarString($texto){
      $textoLimpio = preg_replace('([^A-Za-z0-9.])', '', $texto);	     					
      return $textoLimpio;
}

$captchaValidation = clean_input($_POST['h-captcha-response']);
$username          = clean_input($_POST['username']);
$password		   = clean_input($_POST['password']);
$remember          = clean_input($_POST['remember']);

#validacion de fuerza bruta
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
    $ip = $_SERVER['HTTP_X_REAL_IP'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$control  = date('Y-m-d H:i:s', strtotime('-1 hour'));
$continue = true;
$sql      = "SELECT COUNT(*) intentos FROM intentos_usuarios WHERE ip = '$ip' AND fecha_registro >= '$control'";
$result   = db_query($sql);

while($row = mysqli_fetch_array($result)) {
    if ($row['intentos'] >= 5) {
        $continue = false;
        $message  = "Usuario bloqueado por intentos fallidos";
    }
}

$isLoginForm = false;
if(!is_null($username) and !empty($username) and !is_null($password) and !empty($password)){
    $isLoginForm = true;
}
$rememberMe = false;
if(!is_null($remember) and !empty($remember)){
    $rememberMe = true;
}
if($continue){
    if($isLoginForm and $captchaValidation  != ""){
        #se modifica la consulta para solo validar que el usuario exista en la base de usuarios y que este activo
        $q = sprintf("SELECT id FROM `usuarios` WHERE `active`='Si' AND `login` = '%s'",
        mysqli_real_escape_string($dbsgp,limpiarString($username)));
        $query = @db_query($q);
        
        if (mysqli_num_rows($query) != 0) {
            $row = mysqli_fetch_array($query);
            if(count($row)>0){
                
                #$isError = false;
                #una vez validado que el usuario exista se procede a consultar el directorio activo
                include_once "ldap.php";
                $isError    = true;
                $ldap       = new GestorLdap();
                $gestorLdap = $ldap->get();
                $username   = "nh\\".$username;
                
                foreach($gestorLdap as $value){
                    $ldap_connect = ldap_connect($value->get_ldap_servidor(), $value->get_ldap_puerto());
                    
                    if($ldap_connect){
                        #$password  = "{SHA}" . base64_encode(sha1($password, true));
                        $ldap_bind = @ldap_bind($ldap_connect, $username, $password);
                        if($ldap_bind){
                            $isError = false;
                        }		
                    }
                }
                #fin validacion con el directorio activo

                if(!$isError){

                    $_SESSION['loggedin'] = serialize(getLoginUser($row['id']));
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
                    
                    if(sizeof($results1) > 0)
                        $_SESSION['bi_browser'] = $bi_browser;
                    if(sizeof($results2) > 0)
                        $_SESSION['bi_version'] = $bi_version;
                    if(sizeof($results3) > 0)
                        $_SESSION['bi_platform'] = $bi_platform;
                    if(sizeof($results4) > 0)
                        $_SESSION['bi_platver'] = $bi_platver;

                    if($rememberMe)
                        saveCookieRM();
                    else 
                        deleteCookieRM();
                    
                    header("Location: index.php?menu=0");
                    exit();
                }else{
                    
                    $message = "Usuario o contrase&ntilde;a Erronea LDAP";

                    # Registramos intento Fallido
                    $id   = $row['id'];
                    $date = date('Y-m-d H:i:s');
    
                    $sql    = "INSERT INTO intentos_usuarios VALUES (null, '$ip', '$id', '$date')";
                    $result = db_query($sql, true);

                    if(!$result)
                        $message .= "Error en la conexion"; 
                }
            }
        } else {
            $isError = true;
            $message = "Usuario inactivo en el sistema";
        }
    } else {
        $isError = false;
        $message = "Complete el formulario";
    }
}else {
    $isError = true;
    $message = "login inactivo por numero de intentos fallidos";
}


include_once "header.inc.php";
include_once "content.inc.php";
header('Cache-Control: no cache'); //no cache
//session_cache_limiter('private_no_expire');
?>

<div class="box_registro">
    <form id="demo-form" method='post'enctype='application/x-www-form-urlencoded'>
        <strong>Ingresar a GestOT  |  Versi&oacute;n <?php echo SGP_VERSION?></strong>
        <br />
        <div class="ui-widget">
            <div class="ui-state-<?php echo $isError?"error":"highlight" ?> ui-corner-all" style=" margin-top: 7px; padding: 0 .7em;">
                <p><span class="ui-icon ui-icon-<?php echo $isError?"alert":"info" ?>" style="float: left; margin-right: .3em;"></span>
                <?php echo $message;?>.</p>
            </div>
        </div>
        <br />
        <div style="float: left; margin-right: 3rem;">
            <table  class="data-ro" id="login">
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
                    <td><div style="width: 2rem; padding-left: 6rem	; height: 5rem; margin-left:-2rem;" class="h-captcha" data-sitekey="a65e689d-29cb-4fe8-b011-3d3a9978a8fd"></div></td>
                </tr>	
                <tr>
                    <td colspan="2" style="text-align: center"><button style="margin-left:5rem;" type="submit">Ingresar</button></td>
                </tr>
            </table>
        </div>
    </form>
</div>
<?php
include_once "footer.inc.php";
?>
