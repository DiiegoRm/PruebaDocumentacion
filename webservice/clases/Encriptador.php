<?php
/**
 * Clase para encriptación reversible de contraseñas con el sistema MCRYPT

 */
class Encriptador {
	/* Variables de clase */
	private $salt;
	/**
	 * Método constructor
	 */
	public function __construct() {
		$this -> salt = "MINTICWS";
	}
	
	/**
	 * Método para encriptar un texto proporcionado
	 * @param String texto Contiene el texto a encriptar
	 * @return String Contiene la encriptación del texto dado
	 */
	public function encryptTexto($texto) {
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this -> salt, $texto, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND))));
	}
	
	/**
	 * Método para desencriptar un texto proporcionado
	 * @param String textoEncriptado Contiene la encriptación del texto
	 * @return String Contiene el texto en modo plano
	 */
	public function decryptTexto($textoEncriptado) {
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this -> salt, base64_decode($textoEncriptado), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND)));
		
	}
}
?>