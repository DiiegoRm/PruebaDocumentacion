<?php
include_once "includes/database.php";
include_once "includes/global.php";
include_once "includes/static.inc.php";

class GestorLdap{
    private $id;
	private $ldap_servidor;
	private $ldap_puerto;
	private $ldap_base_dn;
	private $ldap_dn;
	private $ldap_clave;
	private $ldap_base_dn_usuario;
	private $ldap_filtro_dn;

    public function get(){
		$response = array();
		$sql = "SELECT * FROM servidor_ldap WHERE active = 1";
        $q   = db_query($sql);
        
		while($row = mysqli_fetch_array($q)) {

			$handler = $this->create();

			$handler->set_ldap_id($row["id"]);
			$handler->set_ldap_servidor($row["ldap_servidor"]);
			$handler->set_ldap_puerto($row["ldap_puerto"]);
			$handler->set_ldap_base_dn($row["ldap_base_dn"]);
			$handler->set_ldap_dn($row["ldap_dn"]);
			$handler->set_ldap_clave($row["ldap_clave"]);
			$handler->set_ldap_base_dn_usuario($row["ldap_base_dn_usuario"]);
			$handler->set_ldap_filtro_dn($row["ldap_filtro_dn"]);    
			$response[] = $handler;        
        }
		return $response;
    }
	public function create(){
		return new GestorLdap();
	}
	//Setters
	public function set_ldap_id($value){
		$this->ldap_id = $value;
	}
	public function set_ldap_servidor($value){
		$this->ldap_servidor = $value;
	}
	public function set_ldap_puerto($value){
		$this->ldap_puerto = $value;
	}
	public function set_ldap_base_dn($value){
		$this->ldap_base_dn = $value;
	}
	public function set_ldap_dn($value){
		$this->ldap_dn = $value;
	}
	public function set_ldap_clave($value){
		$this->ldap_clave = $value;
	}
	public function set_ldap_base_dn_usuario($value){
		$this->ldap_base_dn_usuario = $value;
	}
	public function set_ldap_filtro_dn($value){
		$this->ldap_filtro_dn = $value;
	}
	
	//Getters
	public function get_ldap_id(){
		return $this->ldap_id;
	}
	public function get_ldap_servidor(){
		return $this->ldap_servidor;
	}
	public function get_ldap_puerto(){
		return $this->ldap_puerto;
	}
	public function get_ldap_base_dn(){
		return $this->ldap_base_dn;
	}
	public function get_ldap_dn(){
		return $this->ldap_dn;
	}
	public function get_ldap_clave(){
		return $this->ldap_clave;
	}
	public function get_ldap_base_dn_usuario(){
		return $this->ldap_base_dn_usuario;
	}
	public function get_ldap_filtro_dn(){
		return $this->ldap_filtro_dn;
	}


}
?>