<?php
function encrypt_param($data) {
    return base64_encode($data);
}

function decrypt_param($data) {
    return base64_decode($data);
}
?>
