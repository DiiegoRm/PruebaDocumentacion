<?php

require_once(ROOT_DIR. 'php/adodb5/adodb.inc.php');
require_once(ROOT_DIR_CLASES. 'UsuariosWS.php');
require_once(ROOT_DIR_CLASES. 'PERFILUSUARIO.php');
require_once(ROOT_DIR_CLASES. 'USUARIOS.php');
require_once(ROOT_DIR_CLASES. 'LOGWEBSERVICES.php');

/**
 * Clase que maneja los metodos de los servicios web publicados con WS-Security
 * @author John Eliecer Mendez Garcia
 * @copyright Everis
 * @since 12 de Diciembre de 2013
 * @version 1.0
 */
class WebServiceSIGRES {
	/* Variables de clase */
	private $conDB;
	private $db;
	private $log;

	/**
	 * Metodo constructor
	 */
	public function __construct() {
		
		//Seteo las valriables para que se registre el log del servicio web
		$this -> log = new LOGWEBSERVICES();
		$this -> log -> crearBD();
		$this -> log -> setFecha("(SELECT TO_CHAR (SYSDATE, 'MM-DD-YYYY HH24:MI:SS') FROM DUAL)", 'sql');
		
	}
	
	/**
	 * Metodo para para devolver los Roles solicitados
	 * @author John Eliecer Mendez Garcia
	 * @return Array mensajesArray Contiene todos los roles  
	 */
	public function ObtenerRoles() {		
		
		$mensajesArray = array();
		$perfilusuario = new PERFILUSUARIO();
		
		$perfilusuario -> crearBD();
		
		$objPerfil = $perfilusuario -> consultar('','','','select  profile from perfilusuario GROUP BY profile ORDER BY profile');
		
		foreach ($objPerfil as $row) {
						
			$mensajesArray[] = $row->getProfile();		
					
		}
		
		return $mensajesArray;
	}
	
	/**
	 * Metodo para para crear usuarios
	 * @author John Eliecer Mendez Garcia
	 * @param  Array parametros Contiene los datos de creacion de usuario
	 * @return Array mensajesArray Contiene todos los roles  
	 */
	public function CrearUsuario($parametros) {		
		
		$usuario = new USUARIOS();		
		$usuario -> crearBD();
		
		if(isset($parametros->usuarioRed) && isset($parametros->Nombres) && isset($parametros->Apellidos) && isset($parametros->Perfil) && isset($parametros->Email) && isset($parametros->Area) && isset($parametros->Estado))
		{
			$validos = $this->validarParametros($parametros);
		
			if ($validos == TRUE) 
			{
				$objUsuario = $usuario -> consultar("WHERE UPPER(username) = UPPER('".$parametros->usuarioRed."')");
				
				if(count($objUsuario) == 0)
				{
					$usuario -> setUsername($parametros->usuarioRed);
					$usuario -> setNombres($parametros->Nombres);
					$usuario -> setApellidos($parametros->Apellidos);
					$usuario -> setProfile($parametros->Perfil);
					$usuario -> setCorreo($parametros->Email);
					$usuario -> setArea($parametros->Area);
					$usuario -> setActivo($parametros->Estado);
					
					$resultado = $usuario->insertar();
					
					if ($resultado != FALSE) {
						
						$mensajesArray['respuesta']= "C|Usuario creado con Exito";
					}
					else 
					{
						$mensajesArray['respuesta']= "R|Error en la ejecucion del servicio";	
					}
					
				}
				else 
				{
					$mensajesArray['respuesta']= "R|El usuario ya existe|".$parametros->usuarioRed;	
				}			
			}
			else 
			{
				$mensajesArray['respuesta']= "R|Error en el envio de parametros";
			}
		}
		else 
		{
			$mensajesArray['respuesta']= "R|Error en el envio de parametros";
		}

		

		
		$this -> log -> setDescripcion("Parametros: \n".print_r($parametros, TRUE));
		$this -> log -> setRespuesta("Respuesta: \n".print_r($mensajesArray,TRUE));
		//Registro el log del consumo del servicio web
		$this -> log -> insertar();
		
		return $mensajesArray;
	}

	/**
	 * Metodo para para crear usuarios
	 * @author John Eliecer Mendez Garcia
	 * @param  Array parametros Contiene los datos de creacion de usuario
	 * @return Array mensajesArray Contiene todos los roles  
	 */
	public function ActualizarUsuario($parametros) {		
		
		$usuario = new USUARIOS();		
		$usuario -> crearBD();
		
		if(isset($parametros->usuarioRed) && isset($parametros->Nombres) && isset($parametros->Apellidos) && isset($parametros->Perfil) && isset($parametros->Email) && isset($parametros->Area) && isset($parametros->Estado))
		{		
			$validos = $this->validarParametros($parametros);
			
			if ($validos == TRUE) 
			{
				$objUsuario = $usuario -> consultar("WHERE UPPER(username) = UPPER('".$parametros->usuarioRed."')");
				
				if(count($objUsuario) > 0)
				{
					foreach ($objUsuario as $row) 
					{
						$row -> setUsername($parametros->usuarioRed);
						$row -> setNombres($parametros->Nombres);
						$row -> setApellidos($parametros->Apellidos);
						$row -> setProfile($parametros->Perfil);
						$row -> setCorreo($parametros->Email);
						$row -> setArea($parametros->Area);
						$row -> setActivo($parametros->Estado);
						
						$resultado = $row->actualizar();
						
						if ($resultado > 0 ) {
							
							$mensajesArray['respuesta']= "C|Usuario actualizado con Exito";
						}
						else 
						{
							$mensajesArray['respuesta']= "R|Error en la ejecucion del servicio";	
						}
					}
					
				}
				else 
				{
					$mensajesArray['respuesta']= "R|El usuario no existe";	
				}			
			}
			else 
			{
				$mensajesArray['respuesta']= "R|Error en el envio de parametros";
			}
		}
		else 
		{
			$mensajesArray['respuesta']= "R|Error en el envio de parametros";
		}

		$this -> log -> setDescripcion("Parametros: \n".print_r($parametros, TRUE));
		$this -> log -> setRespuesta("Respuesta: \n".print_r($mensajesArray,TRUE));
		//Registro el log del consumo del servicio web
		$r = $this -> log -> insertar();
			
		return $mensajesArray;
	}

	/**
	 * Metodo para para crear usuarios
	 * @author John Eliecer Mendez Garcia
	 * @param  Array parametros Contiene los datos de creacion de usuario
	 * @return Array mensajesArray Contiene todos los roles  
	 */
	public function InactivarUsuario($parametros) {		
		
		$usuario = new USUARIOS();		
		$usuario -> crearBD();
		
		if(isset($parametros->usuarioRed))
		{		
			$validos = $this->validarParametros($parametros);
			
			if ($validos == TRUE) 
			{
				$objUsuario = $usuario -> consultar("WHERE UPPER(username) = UPPER('".$parametros->usuarioRed."')");
				//var_dump($objUsuario);die();
				if(count($objUsuario) > 0)
				{
					foreach ($objUsuario as $row) 
					{
						if($row->getActivo() == 1)
						{
							$row -> setActivo(0);
						
							$resultado = $row->actualizar();
							
							if ($resultado > 0 ) {
								
								$mensajesArray['respuesta']= "C|Usuario inactivado con Exito";
							}
							else 
							{
								$mensajesArray['respuesta']= "R|Error en la ejecucion del servicio";	
							}
						}
						else 
						{
							$mensajesArray['respuesta']= "R|Error usuario ya se encuentra inactivo";
						}	
											
					}
					
					
				}
				else 
				{
					$mensajesArray['respuesta']= "R|El usuario no existe";	
				}			
			}
			else 
			{
				$mensajesArray['respuesta']= "R|Error en el envio de parametros";
			}
		}
		else 
		{
			$mensajesArray['respuesta']= "R|Error en el envio de parametros";
		}

		
		$this -> log -> setDescripcion("Parametros: \n".print_r($parametros, TRUE));
		$this -> log -> setRespuesta("Respuesta: \n".print_r($mensajesArray,TRUE));
		//Registro el log del consumo del servicio web
		$this -> log -> insertar();
		
		return $mensajesArray;
	}
	
	/**
	 * Método para validar que esten diligneciados todos los parametros del servicio
	 * @param Array parametros Contiene los datos para el servicio
	 * @return boolean retorno TRUE si enviaron parametros vacios o el estado es diferente a 0(activo) o 1(inactivo)
	 */
	public function validarParametros($parametros) {		
		
		$retorno = TRUE;
		
		foreach ($parametros as $key => $value) {
			
			$value = trim($value);
			
			if(empty($value))
			{
				//El parametro estado puede venir en valor 0 ya que significa inactivado.
				if ($key!='Estado') {
					$retorno = FALSE;
					return $retorno;
				}
				else 
				{
					if($value == NULL)	
					{
						$retorno = FALSE;
						return $retorno;
					}
				}
				
			}
			else 
			{	//el valor para el parametro Estado solo puede ser 1 (activo) o 0 (inactivo)
				if ($key=='Estado' && $value != 1 ) 
				{
					$retorno = FALSE;
					return $retorno;	
				}
			}								
		}

		return $retorno;
	}

	/*
	 * Función para eliminar caracteres especiales
	 */
	public function decode_entities_full($string, $quotes = ENT_COMPAT, $charset = 'ISO-8859-1') {
		return eregi_replace("[\n|\r|\n\r]",' ',trim (strip_tags (html_entity_decode(preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]+);/', 'convert_entity', $string), $quotes, $charset)))); 
	}
}
?>