<?php
class AppUser{
    private $uid='';
    private $login='';
	private $nombre='';
	private $idgrupo='';
	private $email='';
	private $telefono='';
	private $idotjefatura=array();
	private $idotzona=array();
	private $idotdepto=array();
	private $idotlocalidad=array();
	private $idotsector=array();
	private $idotsegmento=array();
	private $idoteecc=array();
	//Viabilidades
	private $idvbjefatura=array();
	private $idvbregion=array();
	private $idvbdepto=array();
	private $idvblocalidad=array();
	private $idvbsector=array();
	private $idvbsegmento=array();
	private $idvbeecc=array();
	//Privilegios
	private $rights=array();
	//--------------------------------------------------------
	function setUserMap($map){
		if(sizeof($map)){
			$this->uid = $map['id'];
			$this->login = $map['login'];
			$this->nombre = $map['nombre'];
			$this->idgrupo = $map['idgrupo'];
			$this->email = $map['email'];
			$this->telefono = $map['telefono'];
		}
	}
	//--------------------------------------------------------
	function addRightsMap($map){
		if(sizeof($map)){
			$this->rights[] = $map[0];
		}
	}
	//--------------------------------------------------------
	function addConfigMap($map){
		if(sizeof($map)){
			if($map['tipo'] === 'OT'){
				if(hasVal($map['idjefatura'])){
					$this->idotjefatura[] = $map['idjefatura'];
				}
				if(hasVal($map['idzona'])){
					$this->idotzona[] = $map['idzona'];
				}
				if(hasVal($map['iddepto'])){
					$this->idotdepto[] = $map['iddepto'];
				}
				if(hasVal($map['idlocalidad'])){
					$this->idotlocalidad[] = $map['idlocalidad'];
				}
				if(hasVal($map['idsector'])){
					$this->idotsector[] = $map['idsector'];
				}
				if(hasVal($map['idsegmento'])){
					$this->idotsegmento[] = $map['idsegmento'];
				}
				if(hasVal($map['ideecc'])){
					$this->idoteecc[] = $map['ideecc'];
				}
			} else {
				if(hasVal($map['idjefatura'])){
					$this->idvbjefatura[] = $map['idjefatura'];
				}
				if(hasVal($map['idregion'])){
					$this->idvbregion[] = $map['idregion'];
				}
				if(hasVal($map['iddepto'])){
					$this->idvbdepto[] = $map['iddepto'];
				}
				if(hasVal($map['idlocalidad'])){
					$this->idvblocalidad[] = $map['idlocalidad'];
				}
				if(hasVal($map['idsector'])){
					$this->idvbsector[] = $map['idsector'];
				}
				if(hasVal($map['idsegmento'])){
					$this->idvbsegmento[] = $map['idsegmento'];
				}
				if(hasVal($map['ideecc'])){
					$this->idvbeecc[] = $map['ideecc'];
				}
			}
		}
	}
	//--------------------------------------------------------
	public function __get($name) {
		return $this->$name;
	}
	//--------------------------------------------------------
	public function isAdmin(){
		return ($this->idgrupo== "1" || $this->idgrupo== "20" || $this->idgrupo== "21");
	}
	//--------------------------------------------------------
	public function isInRole($opts){
		$valid = false;
		$roles = explode(',',$opts);
		foreach($roles as $target) {
		  if(in_array($target,$this->rights,true)){
			$valid = true;
			break;
		  }
		}
		return $valid;
	}
	//--------------------------------------------------------
	public function isInGroup($opts){
		$valid = false;
		$grps = explode(',',$opts);
		foreach($grps as $target) {
		  if($target==$this->idgrupo){
			$valid = true;
			break;
		  }
		}
		return $valid;
	}
	//--------------------------------------------------------
	public function isInState($state,$opts){
		$valid = false;
		$sts = explode(',',$opts);
		foreach($sts as $target) {
		  if($target==$state){
			$valid = true;
			break;
		  }
		}
		return $valid;
	}
	//--------------------------------------------------------
	public function isBtwRole($a,$b){
		$valid = false;
		foreach($this->rights as $target) {
		  if($target>=$a&&$target<=$b){
			$valid = true;
			break;
		  }
		}
		return $valid;
	}
	//--------------------------------------------------------
	public function hasJefaturaOT() {
		return sizeof($this->idotjefatura);
	}
	//--------------------------------------------------------
	public function hasZonaOT() {
		return sizeof($this->idotzona);
	}
	//--------------------------------------------------------
	public function hasDeptoOT() {
		return sizeof($this->idotdepto);
	}
	//--------------------------------------------------------
	public function hasLocalidadOT() {
		return sizeof($this->idotlocalidad);
	}
	//--------------------------------------------------------
	public function hasSectorOT() {
		return sizeof($this->idotsector);
	}
	//--------------------------------------------------------
	public function hasSegmentoOT() {
		return sizeof($this->idotsegmento);
	}
	//--------------------------------------------------------
	public function hasEeccOT() {
		return sizeof($this->idoteecc);
	}
	//--------------------------------------------------------
	public function hasJefaturaVB() {
		return sizeof($this->idvbjefatura);
	}
	//--------------------------------------------------------
	public function hasRegionVB() {
		return sizeof($this->idvbregion);
	}
	//--------------------------------------------------------
	public function hasDeptoVB() {
		return sizeof($this->idvbdepto);
	}
	//--------------------------------------------------------
	public function hasLocalidadVB() {
		return sizeof($this->idvblocalidad);
	}
	//--------------------------------------------------------
	public function hasSectorVB() {
		return sizeof($this->idvbsector);
	}
	//--------------------------------------------------------
	public function hasSegmentoVB() {
		return sizeof($this->idvbsegmento);
	}
	//--------------------------------------------------------
	public function hasEeccVB() {
		return sizeof($this->idvbeecc);
	}
	//--------------------------------------------------------
	public function getTrayFilter($id,$idname,$table,$oper="AND") {
		$filter = "";
		if(!$this->isAdmin()){
			$filter = "$oper $id IN (SELECT $idname FROM $table WHERE idgrupo=$this->idgrupo)";
		}
		return $filter;
	}
	//--------------------------------------------------------
	public function getJefaturaFilterOT($field="id",$prefix="",$oper="AND") {
		$filter = "";
		if(!$this->isAdmin()){
			if($this->hasJefaturaOT()) {
				$filter = "$oper ${prefix}${field} IN (".implode(',',$this->idotjefatura).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	public function getZonaFilterOT($field="id",$prefix="",$oper="AND") {
		$filter = "";
		if(!$this->isAdmin()){
			if($this->hasZonaOT()) {
				$filter = "$oper ${prefix}${field} IN (".implode(',',$this->idotzona).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	public function getDeptoFilterOT($field="id",$prefix="",$oper="AND") {
		$filter = "";
		if(!$this->isAdmin()){
			if($this->hasDeptoOT()) {
				$filter = "$oper ${prefix}${field} IN (".implode(',',$this->idotdepto).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	public function getLocalidadFilterOT($field="id",$prefix="",$oper="AND") {
		$filter = "";
		if(!$this->isAdmin()){
			if($this->hasLocalidadOT()) {
				$filter = "$oper ${prefix}${field} IN (".implode(',',$this->idotlocalidad).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	public function getSectorFilterOT($field="id",$prefix="",$oper="AND") {
		$filter = "";
		if(!$this->isAdmin()){
			if($this->hasSectorOT()) {
				$filter = "$oper ${prefix}${field} IN (".implode(',',$this->idotsector).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	/*public function isMultisegmentoOT() {
		return in_array("-1",$this->idotsegmento);
	}*/
	//--------------------------------------------------------
	public function getSegmentoFilterOT($field="id",$prefix="",$oper="AND") {
		$filter = "$oper ${prefix}${field} > 0 ";
		if(!$this->isAdmin()){
			if($this->hasSegmentoOT()){
				$filter .= "$oper ${prefix}${field} IN (".implode(',',$this->idotsegmento).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	public function getEeccFilterOT($field="id",$prefix="",$oper="AND") {
		$filter = "";
		if(!$this->isAdmin()){
			if($this->hasEeccOT()) {
				$filter = "$oper ${prefix}${field} IN (".implode(',',$this->idoteecc).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	public function getJefaturaFilterVB($field="id",$prefix="",$oper="AND") {
		$filter = "";
		if(!$this->isAdmin()){
			if($this->hasJefaturaVB()) {
				$filter = "$oper ${prefix}${field} IN (".implode(',',$this->idvbjefatura).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	public function getRegionFilterVB($field="id",$prefix="",$oper="AND") {
		$filter = "";
		if(!$this->isAdmin()){
			if($this->hasRegionVB()) {
				$filter = "$oper ${prefix}${field} IN (".implode(',',$this->idvbregion).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	public function getDeptoFilterVB($field="id",$prefix="",$oper="AND") {
		$filter = "";
		if(!$this->isAdmin()){
			if($this->hasDeptoVB()) {
				$filter = "$oper ${prefix}${field} IN (".implode(',',$this->idvbdepto).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	public function getLocalidadFilterVB($field="id",$prefix="",$oper="AND") {
		$filter = "";
		if(!$this->isAdmin()){
			if($this->hasLocalidadVB()) {
				$filter = "$oper ${prefix}${field} IN (".implode(',',$this->idvblocalidad).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	public function getSectorFilterVB($field="id",$prefix="",$oper="AND") {
		$filter = "";
		if(!$this->isAdmin()){
			if($this->hasSectorVB()) {
				$filter = "$oper ${prefix}${field} IN (".implode(',',$this->idvbsector).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	public function isMultisegmentoVB() {
		return in_array("-1",$this->idvbsegmento);
	}
	//--------------------------------------------------------
	public function getSegmentoFilterVB($field="id",$prefix="",$oper="AND") {
		$filter = "$oper ${prefix}${field} > 0 ";
		if(!$this->isAdmin() && !$this->isMultisegmentoVB()){
			if($this->hasSegmentoVB()){
				$filter .= "$oper ${prefix}${field} IN (".implode(',',$this->idvbsegmento).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	public function getEeccFilterVB($field="id",$prefix="",$oper="AND") {
		$filter = "";
		if(!$this->isAdmin()){
			if($this->hasEeccVB()) {
				$filter = "$oper ${prefix}${field} IN (".implode(',',$this->idvbeecc).") ";
			}
		}
		return $filter;
	}
	//--------------------------------------------------------
	function getAllFilterOT($prefix=""){
		return $this->getSegmentoFilterOT("idsegmento",$prefix).
			//$this->getJefaturaFilterOT("idjefatura",$prefix).
			$this->getZonaFilterOT("idzona",$prefix).
			$this->getDeptoFilterOT("iddepto",$prefix).
			$this->getLocalidadFilterOT("idlocalidad",$prefix).
			$this->getEeccFilterOT("ideecc",$prefix);
	}
	//--------------------------------------------------------
	function getLocationFilterOT($prefix=""){
		return $this->getZonaFilterOT("idzona",$prefix).
			$this->getDeptoFilterOT("iddepto",$prefix).
			$this->getLocalidadFilterOT("idlocalidad",$prefix);
	}
	//--------------------------------------------------------
	function getLocationFilterVB($prefix=""){
		return $this->getSegmentoFilterVB("idsegmento",$prefix).
			$this->getJefaturaFilterVB("idjefatura",$prefix).
			$this->getRegionFilterVB("idregion",$prefix).
			$this->getDeptoFilterVB("iddepto",$prefix).
			$this->getLocalidadFilterVB("idlocalidad",$prefix).
			$this->getEeccFilterVB("ideecc",$prefix);
	}
	//--------------------------------------------------------
}
?>