<?php
require 'database.php';
//require 'class.vo.mliq.php';
//ini_set('display_errors',0);
//$funcionesdao= new funcionesdao();

$mliq=file_get_contents("php://input");
echo boton(htmlspecialchars($mliq));
//echo $funcionesdao->boton($mliq);
//echo "hola";
 ?>
