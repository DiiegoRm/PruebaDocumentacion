<?php

$foto = $_POST['foto'];
$tempFile = tempnam(sys_get_temp_dir(), 'blob_');
// file_put_contents($tempFile, $foto);
// header('Content-Type: image/png');
// readfile($tempFile);
// unlink($tempFile);
echo '<img src="data:image/jpeg;base64,' . $foto . '">';

?>
