<?php
/* Inclusión de clases */
require_once("/var/opt/webstack/apache2/2.2/htdocs/www/static.inc.php");
require_once(ROOT_DIR.'php/adodb5/adodb.inc.php');
require_once(ROOT_DIR_CLASES.'UsuariosWS.php');
require_once("webservice/clases/Encriptador.php");
/* Captura de los parámetros */
$parametros = $_SERVER["argv"];
/* Verificacion de los parámetros */
if (count($parametros) == 5) {
	/* Inicializacion de la clase de encriptacion */
	$clEncriptador = new Encriptador();
	$usuariosws = new UsuariosWS();
	
	$passEncriptado = $clEncriptador -> encryptTexto($parametros[2]);
	$usuariosws -> crearBD();
	
	$objUsuarioWs = $usuariosws -> consultar("WHERE uws_usuario = '" . $parametros[1] . "'");
	
	if(count($objUsuarioWs) > 0)
	{
		foreach ($objUsuarioWs as $usuariows) 
		{
			echo "El nombre de usuario ya existe en la DB.\nDesea actualizar el usuario actual con los datos que ingreso ?\nPor defecto esta en N (No), si desea actualizar por favor introduzca S (Si): ";
			$prompt = fopen("php://stdin", "r");
			$linea = fgets($prompt);
			if (trim($linea) == 'S') 
			{
				$usuariows -> setUwsNombres($parametros[3]);
				$usuariows -> setUwsApellidos($parametros[4]);
				$usuariows -> setUwsContrasenia($passEncriptado);
				
				$actualizaUsuario = $usuariows -> actualizar();
														
				if ($actualizaUsuario > 0) 
				{
					echo "Se ha actualizado el usuario " . $parametros[1] . ".\nla contrase�a en texto es: " . $parametros[2] . "\nLa encriptacion de la contrase�a es: " . $passEncriptado . "\n";
				}
				else 
				{
					echo "fallo la actualizacion del usuario en Base de datos";	
				}
			} 
			else 
			{
				echo "El proceso ha terminado sin realizar cambios.\n";
				exit;
			}
		}
	}
	else 
	{
		$usuariosws -> setUwsUsuario($parametros[1]);
		$usuariosws -> setUwsNombres($parametros[3]);
		$usuariosws -> setUwsApellidos($parametros[4]);
		$usuariosws -> setUwsContrasenia($passEncriptado);
		
		$registraUsuario = $usuariosws->insertar();
				
		if ($registraUsuario != FALSE) {
			
			echo "Se ha creado el usuario " . $parametros[1] . ".\nla contrase�a en texto es: " . $parametros[2] . "\nLa encriptacion de la contrase�a es: " . $passEncriptado . "\n";
		}
		else 
		{
			echo "fallo la creacion del usuario en Base de datos";	
		}
		
	}
			
} 
else 
{
	echo "La cantidad de parametros introducidos no son aceptados para agregar el usuario a la DB.\nSe deberan colocar 4 parametros en el siguiente orden:\n1. Nombre de usuario.\n2. Password.\n3. Nombres. (Si son 2 nombres, se deberan encerrar en comillas).\n4. Apellidos. (Si son 2 apellidos, se deberan encerrar en comillas).\n";
}
?>