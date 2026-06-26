<?php  
/** 
 * soap-server-wsse.inc 
 * 
 * Copyright (c) 2007, Robert Richards <rrichards@ctindustries.net>. 
 * All rights reserved. 
 * 
 * Redistribution and use in source and binary forms, with or without 
 * modification, are permitted provided that the following conditions 
 * are met: 
 * 
 *   * Redistributions of source code must retain the above copyright 
 *     notice, this list of conditions and the following disclaimer. 
 * 
 *   * Redistributions in binary form must reproduce the above copyright 
 *     notice, this list of conditions and the following disclaimer in 
 *     the documentation and/or other materials provided with the 
 *     distribution. 
 * 
 *   * Neither the name of Robert Richards nor the names of his 
 *     contributors may be used to endorse or promote products derived 
 *     from this software without specific prior written permission. 
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS 
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT 
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS 
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE 
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, 
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, 
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; 
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER 
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT 
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN 
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
 * POSSIBILITY OF SUCH DAMAGE. 
 * 
 * @author     Robert Richards <rrichards@ctindustries.net> 
 * @copyright  2007 Robert Richards <rrichards@ctindustries.net> 
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License 
 * @version    1.0.0 
// INSAD: Start of changes by A.J.W. van Peppen
 *
 * Changes April 29 2008 by A.J.W. van Peppen, Senior System Engineer, Insad Grafisch b.v., The Netherlands
 *   - Added support for UsernameToken
 *   - Added support for Timestamp; Handling of the expiration of messages.
 *
// INSAD: End of changes by A.J.W. van Peppen

 */ 
require('xmlseclibs.php'); 
require_once(ROOT_DIR.'php/includes.php');
// INSAD: Start of changes by A.J.W. van Peppen
class WSSESoapUserToken {
	public $userName = '';
	public $passwordType;
	public $password = '';
	public $Nonce = '';
	public $Created = '';

	public function __construct()
	{
		$this->userName = '';
		$this->passwordType = '';
		$this->password = '';
		$this->Nonce = '';
		$this->Created = '';
	}
};
// INSAD: End of changes by A.J.W. van Peppen

class WSSESoapServer {
    const WSSENS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    const WSSENS_2003 = 'http://schemas.xmlsoap.org/ws/2003/06/secext';
    const WSUNS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
    const WSSEPFX = 'wsse';
    const WSUPFX = 'wsu';
	// INSAD: Start of changes by A.J.W. van Peppen
    const WSUNAME = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0';
	// INSAD: End of changes by A.J.W. van Peppen

    private $soapNS, $soapPFX;
    private $soapDoc = NULL;
    private $envelope = NULL;
    private $SOAPXPath = NULL;
    private $secNode = NULL;
    public $signAllHeaders = FALSE;
	// INSAD: Start of changes by A.J.W. van Peppen
	public $userToken = NULL;
	// INSAD: End of changes by A.J.W. van Peppen

    private function locateSecurityHeader($setActor=NULL) { 
        $wsNamespace = NULL;
        if ($this->secNode == NULL) {
            $headers = $this->SOAPXPath->query('//wssoap:Envelope/wssoap:Header');
            if ($header = $headers->item(0)) {
                $secnodes = $this->SOAPXPath->query('./*[local-name()="Security"]', $header);
                $secnode = NULL;
                foreach ($secnodes AS $node) {
                    $nsURI = $node->namespaceURI;
                    if (($nsURI == self::WSSENS) || ($nsURI == self::WSSENS_2003)) {
                        $actor = $node->getAttributeNS($this->soapNS, 'actor');
                        if (empty($actor) || ($actor == $setActor)) {
                            $secnode = $node;
                            $wsNamespace = $nsURI;
                            break;
                        }
                    }
                }
            }
            $this->secNode = $secnode;
        }
        return $wsNamespace;
    }

    public function __construct($doc) { 
        $this->soapDoc = $doc; 
        $this->envelope = $doc->documentElement; 
        $this->soapNS = $this->envelope->namespaceURI; 
        $this->soapPFX = $this->envelope->prefix; 
        $this->SOAPXPath = new DOMXPath($doc); 
        $this->SOAPXPath->registerNamespace('wssoap', $this->soapNS); 
        $this->SOAPXPath->registerNamespace('wswsu', WSSESoapServer::WSUNS); 
        $wsNamespace = $this->locateSecurityHeader(); 
        if (! empty($wsNamespace)) { 
            $this->SOAPXPath->registerNamespace('wswsse', $wsNamespace); 
        } 
    } 

	// INSAD: Start of changes by A.J.W. van Peppen
	// Authentication function; should be pure virtual.
	// For now build implementation which denies access for all users
	function AuthenticateUsertoken() {
		if ($this->userToken) {
			$databasePwd = null;
			
			//Consulta de usuario para autenticacion
			
			$select = "SELECT * FROM USUARIOSWS WHERE uws_usuario = '" . $this -> userToken -> userName . "'";
		
			$Resultado = open_query($select);
			$registros = ora_db_num_rows($Resultado);
			
			if ($registros == 0) {	// eg. user not found in database
				return FALSE;
			} else {
				/* Inicializacion de la clase Encriptador */
				$clEncriptado = new Encriptador();
				//while ($d = $consUsuario -> fetch_array(MYSQLI_ASSOC)) {
					$linea = ora_db_fetch_row($Resultado);	
					$databasePwd = $clEncriptado -> decryptTexto($linea["UWS_CONTRASENIA"]);
				//}
			}
			
			// Check Password - Switch on Type:
			switch ($this->userToken->passwordType) {
				case WSSESoapServer::WSUNAME."#PasswordDigest":
					// Use Digest for testing
					// Sample for Digest testing:
					// $databasePwd = password for this user in plain text
					$tstPwd = base64_encode(sha1(base64_decode($this->userToken->Nonce).$this->userToken->Created.$databasePwd, true)); 
					return ($tstPwd == $this->userToken->password);
				break;
				//case WSSESoapServer::WSUNAME."#PasswordText":	// Default action
				default:	// Default is plain text
					// Use Password in plain text checking
					// Sample for Text testing:
					// $databasePwd = password for this user in plain text
					return ($databasePwd == $this->userToken->password);
				break;
			}
		}
		return FALSE;		// FALSE: Authorisation failed
	}
	// INSAD: End of changes by A.J.W. van Peppen

	// INSAD: Start of changes by A.J.W. van Peppen
	// Read and check Timestamp
	public function processTimestamp($refNode) {
		//// Get Created time -- Not required right now :)
		//$query = '//wswsu:Created';
		//$nodeset = $this->SOAPXPath->query($query);
		//if ($encmeth = $nodeset->item(0)) {
		//	$Created = $encmeth->textContent;
		//}

		// Get Expires time. When not given then never expires.
		$Expires = '';
		$query = '//wswsu:Expires';
		$nodeset = $this->SOAPXPath->query($query);
		if ($encmeth = $nodeset->item(0)) {
			$Expires = $encmeth->textContent;
		}

		if (empty($Expires)) {				// Never expires
			return TRUE;
		}

		if (time() > strtotime($Expires)) {				// Timestamp expired?
			throw new Exception("Timestamp expired.");
			//$server->fault('401',"Timestamp expired.");
		}
		return TRUE;
	}
// INSAD: End of changes by A.J.W. van Peppen

// INSAD: Start of changes by A.J.W. van Peppen
	// Read and authenticate usertoken
	public function processUsernameToken($refNode) {
		if ($this->userToken == NULL)	{
			$this->userToken = new WSSESoapUserToken();
		}

		// Get Username
		$query = '//wswsse:Username';
		$nodeset = $this->SOAPXPath->query($query);
		if ($encmeth = $nodeset->item(0)) {
			$this->userToken->userName = $encmeth->textContent;
		}

		// Get Password -- Get Type as well (WSSESoapServer::WSUNAME."#PasswordText"/WSSESoapServer::WSUNAME."#PasswordDigest")
		$query = '//wswsse:Password';
		$nodeset = $this->SOAPXPath->query($query);
		if ($encmeth = $nodeset->item(0)) {
			$this->userToken->passwordType = $encmeth->getAttribute("Type");
			$this->userToken->password = $encmeth->textContent;
		}

		// Get Nonce
		$query = '//wswsse:Nonce';
		$nodeset = $this->SOAPXPath->query($query);
		if ($encmeth = $nodeset->item(0)) {
			$this->userToken->Nonce = $encmeth->textContent;
		}

		// Get Created time
		$query = '//wswsu:Created';
		$nodeset = $this->SOAPXPath->query($query);
		if ($encmeth = $nodeset->item(0)) {
			$this->userToken->Created = $encmeth->textContent;
		}

		if (!$this->AuthenticateUsertoken()) {				// Authentication failed.
			Throw new Exception("Internal Error Server 001", 500);
			//throw new Exception("Authentication failed for user '".$this->userToken->userName."'.", 401);
			//$server->fault('401',"Incorrect username and password combination.");
		}
		return TRUE;
	}
// INSAD: End of changes by A.J.W. van Peppen

    public function processSignature($refNode) {
        $objXMLSecDSig = new XMLSecurityDSig(); 
        $objXMLSecDSig->idKeys[] = 'wswsu:Id'; 
        $objXMLSecDSig->idNS['wswsu'] = WSSESoapServer::WSUNS; 
        $objXMLSecDSig->sigNode = $refNode; 

        /* Canonicalize the signed info */ 
        $objXMLSecDSig->canonicalizeSignedInfo(); 

        $retVal = $objXMLSecDSig->validateReference(); 

        if (! $retVal) { 
            throw new Exception("Validation Failed"); 
        } 

        $key = NULL; 
        $objKey = $objXMLSecDSig->locateKey(); 

        if ($objKey) { 
            if ($objKeyInfo = XMLSecEnc::staticLocateKeyInfo($objKey, $refNode)) { 
                /* Handle any additional key processing such as encrypted keys here */ 
            } 
        } 

        if (empty($objKey)) { 
            throw new Exception("Error loading key to handle Signature"); 
        } 
        do { 
            if (empty($objKey->key)) { 
                $this->SOAPXPath->registerNamespace('xmlsecdsig', XMLSecurityDSig::XMLDSIGNS); 
                $query = "./xmlsecdsig:KeyInfo/wswsse:SecurityTokenReference/wswsse:Reference"; 
                $nodeset = $this->SOAPXPath->query($query, $refNode); 
                if ($encmeth = $nodeset->item(0)) { 
                    if ($uri = $encmeth->getAttribute("URI")) { 
                        $arUrl = parse_url($uri); 
                        if (empty($arUrl['path']) && ($identifier = $arUrl['fragment'])) { 
                            $query = '//wswsse:BinarySecurityToken[@wswsu:Id="'.$identifier.'"]'; 
                            $nodeset = $this->SOAPXPath->query($query); 
                            if ($encmeth = $nodeset->item(0)) { 
                                $x509cert = $encmeth->textContent; 
                                $x509cert = str_replace(array("\r", "\n"), "", $x509cert); 
                                $x509cert = "-----BEGIN CERTIFICATE-----\n".chunk_split($x509cert, 64, "\n")."-----END CERTIFICATE-----\n"; 
                                $objKey->loadKey($x509cert); 
                                break; 
                            } 
                        } 
                    } 
                } 
                throw new Exception("Error loading key to handle Signature"); 
            } 
        } while(0); 

        if (! $objXMLSecDSig->verify($objKey)) { 
            throw new Exception("Unable to validate Signature"); 
        } 

        return TRUE; 
    } 

    public function process() {
        if (empty($this->secNode)) {
            return; 
        } 
        $node = $this->secNode->firstChild;
        while ($node) {
            $nextNode = $node->nextSibling;
            switch ($node->localName) {
                case "Signature": 
                    if ($this->processSignature($node)) { 
                        if ($node->parentNode) { 
                            $node->parentNode->removeChild($node); 
                        } 
                    } else { 
                        /* throw fault */ 
                        return FALSE; 
                    }
					break;
				// INSAD: Start of changes by A.J.W. van Peppen
				// UsernameToken processing
				case "UsernameToken":
					if ($this->processUsernameToken($node)) {
						if ($node->parentNode) {
							$node->parentNode->removeChild($node);
						}
					} else {
						/* throw fault */ 
						return FALSE; 
					}
					break;
				// Timestamp processing
				case "Timestamp":
					if ($this->processTimestamp($node)) {
						if ($node->parentNode) {
							$node->parentNode->removeChild($node);
						}
					} else {
						/* throw fault */ 
						return FALSE;
					}
					break;
				// INSAD: End of changes by A.J.W. van Peppen
            }
            $node = $nextNode;
        }
        $this->secNode->parentNode->removeChild($this->secNode);
        $this->secNode = NULL;
        return TRUE;
    }
     
    public function saveXML() { 
        return $this->soapDoc->saveXML(); 
    } 

    public function save($file) { 
        return $this->soapDoc->save($file); 
    }
}
?>