<?php
/**
 * Clase para conexión de base de datos

 */
class db {
	/* Variables de clase */
    private $usuario;
    private $servidor;
    private $clave;
    private $db;
	/* Constantes de la clase */
	const ID_SERVICIO_MINTIC = 14;
	const USU_ID_ADMINISTRADOR = 1;
    
	/**
	 * Constructor
	 */
    public function __construct() {
        $this->usuario = "usu_prod_trs";
    	$this->servidor = "10.201.155.31:3306";
    	$this->clave = "admin123";
    	$this->db = "sisgot_p";
    }
    
    /**
	 * Conexión a la base de datos
	 * @return Object Conexión a la base de datos
	 */ 
    public function conectar() {
        $db = new mysqli($this->servidor, $this->usuario, $this->clave, $this->db);
        if($db->connect_error){
          printf("Conexión fallida: %s\n", mysqli_connect_error());
          exit();
          return false;
        }
        return $db;
    }
	
	/**
	 * Registra los consumos de los servicios web en la tabla logwebservices
	 * @param String parametrosPeticion Contiene la descripción de los parámetros que llegaron en la petición del servicio web
	 * @param String parametrosRespuesta Contiene la descripción de los parámetros que se retornan en la petición del servicio web
	 * @return void
	 */
	public function registrarLogWS($parametrosPeticion, $parametrosRespuesta) {
		/* Realizar conexión */
		$conexion = $this -> conectar();
		/* Insercion del registro */
		//$inserta = $conexion -> query("INSERT INTO logwebservices (id_servicio,descripcion,respuesta,fecha,id_usuario) VALUES (" . self::ID_SERVICIO_MINTIC . ",'" . $parametrosPeticion . "','" . $parametrosRespuesta . "',NOW()," . self::USU_ID_ADMINISTRADOR . ")");
		//echo "INSERT INTO logwebservices (id_servicio,descripcion,respuesta,fecha,id_usuario) VALUES (" . self::ID_SERVICIO_MINTIC . ",'" . $parametrosPeticion . "','" . $parametrosRespuesta . "',NOW()," . self::USU_ID_ADMINISTRADOR . ")";
		//die();
		/*if ($inserta -> num_rows > 0) {
			/* Alguna validación para verificar la inserción 
		}*/
	}
	
	/**
	 * Destructor
	 */
	public function __destruct() {
		
	}
}
?>