<?php
date_default_timezone_set('America/Bogota');
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header ("Pragma: no-cache");
header ('Pragma: public');
header ("Content-Disposition: attachment; filename=$filename");
header ('Content-Type: application/ms-excel; charset=UTF-8');
header ('Content-Transfer-Encoding: binary');

echo "\xEF\xBB\xBF";
$query = db_query($sql);
$i=0;

if (!file_exists("php://output")) {
$out = fopen("php://output", 'w');
}

$data=array();
while ($row = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
	$data[] = $row;
}

foreach ($data as $keydata) {
    if($i++==0){
			$val_token=verifyFormToken('class_excel', $token_ini);
			if($val_token != 1){
				echo "<div class=\"msg-error\">Se ha presentado un error de seguridad CSRF<br /><br />";
			}else{
				fputcsv($out, array_keys($keydata),";",'"');
			}
		}
	//fputcsv($out, array_keys($keydata),";",'"');
    //}
    fputcsv($out, array_values($keydata),";",'"');
}
fclose($out);
?>
