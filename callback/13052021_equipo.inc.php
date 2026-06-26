<?php
ob_start();
include_once "../includes/session.php";
sessionCheck();
include_once "../includes/database.php";
include_once "../includes/global.php";
require_once "../includes/user.class.inc.php";
include_once "../includes/static.inc.php";

switch($_REQUEST["mode"]){
    case 'search':

        $response = db_query("SELECT calibrado FROM equipos_ecc WHERE id = " . $_POST['chkLocID'][0]);
        $data = mysqli_fetch_array($response);
        echo json_encode(["Calibrado" => $data[0]]);
    break;
    case 'files':
        $id=isset ($_POST['id'])?$_POST['id']:"";
        $name=isset ($_POST['name'])?$_POST['name']:"";
        $target=isset ($_POST['target'])?$_POST['target']:"";
        $file = $id."_".$target;
        $appuser = getAppUser();
    //echo UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR. $target ."</br>";
    //echo OT_FILE_PATH. DIRECTORY_SEPARATOR .$file ."</br>";
        if (copy(UPLOAD_TMP_DIR . DIRECTORY_SEPARATOR. basename($target),EQUIPOS_FILE_PATH . basename($file))) {
            $sql = "INSERT INTO adjuntosequipos(idequipo,titulo,archivo,create_date,idusuario) VALUES($id,'$name','$file', now(),$appuser->uid)";
            $sql_update = db_query($sql,true);
            unlink(UPLOAD_TMP_DIR. DIRECTORY_SEPARATOR . basename($target));
            echo "OK";
        }
        else{
            echo "No fue posible subir el archivos ".htmlspecialchars($name);
        }
    break;
    case 'find':
        $response = db_query("SELECT te.* FROM tipoequipo te JOIN marca m ON m.id = te.marca_id WHERE m.id = $_POST[id]");
        $datos = [];
        $datos = "<option value=''>---SELECCIONE---</option>";
        while($data = mysqli_fetch_array($response)){
            $datos .= "<option value='".$data['id']."'>".$data['nombre']."</option>";
        }
        echo json_encode([$datos]);
    break;
    case 'findDepto':
        $response = db_query("SELECT dp.id, dp.nombre, dp.active FROM contratos con INNER JOIN zonaxdepto zon ON con.idzona = zon.idzona INNER JOIN deptos dp ON dp.id = zon.iddepto WHERE con.ideecc = $_POST[id]");
        $datos = [];
        $datos = "<option value=''>---SELECCIONE---</option>";
        while($data = mysqli_fetch_array($response)){
            $datos .= "<option value='".$data['id']."'>".$data['nombre']."</option>";
        }
        echo json_encode([$datos]);
    break;
    case 'findDeptoSelected':
        $response = db_query("SELECT dp.id, dp.nombre, dp.active FROM contratos con INNER JOIN zonaxdepto zon ON con.idzona = zon.idzona INNER JOIN deptos dp ON dp.id = zon.iddepto WHERE con.ideecc = $_POST[id]");
        $datos = [];
        $datos = "<option value=''>---SELECCIONE---</option>";
        while($data = mysqli_fetch_array($response)){
            if($data['id'] == $_POST['depto']){
                $datos .= "<option selected value='".$data['id']."'>".$data['nombre']."</option>";
            }else{
                $datos .= "<option value='".$data['id']."'>".$data['nombre']."</option>";
            }
        }
        echo json_encode([$datos]);
    break;
    case 'del':
        $id = getVal($_POST['id'],"null");
        // $id=mysqli_real_escape_string($dbsgp,$id);//KIUWAN
        $file = getSQLValue("SELECT archivo FROM adjuntosequipos WHERE id=$id");
        $sql = "DELETE FROM adjuntosequipos WHERE id=$id";
        if (db_query($sql,true) > 0){
            echo "OK";
            unlink(realpath(EQUIPOS_FILE_PATH . DIRECTORY_SEPARATOR .basename($file)));
        }
        else echo "No fue posible Eliminar el archivo.";
    break;
} // END SWITCH
?>
