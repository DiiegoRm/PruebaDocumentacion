<?php
/*
	Name: Simple PHP Browser Detection script.
	Version : 13.02
	Author: Linesh Jose
	Url: http://lineshjose.com
	Email: lineshjose@gmail.com
	Donate:  http://bit.ly/donate-linesh
	github: https://github.com/lineshjose
	Copyright: Copyright (c) 2013 LineshJose.com

	Note: This script is free; you can redistribute it and/or modify  it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or (at your option) any later version.This script is distributed in the hope
		that it will be useful,    but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
		See the  GNU General Public License for more details.

-----------------------------------------------------------

	This function to get the current browser info
	@param $arg : returns current browser property. Eg: platform, name, version,
	@param $agent: it is the $_SERVER['HTTP_USER_AGENT'] value
*/

function get_browser_info($arg='',$agent='')
{
	if(empty($agent) ) {
		$browser['agent'] = $_SERVER['HTTP_USER_AGENT'];
	}else{
		$browser['agent']=$agent;
	}



	/*----------------------------------------- Platform ---------------------------------------------*/
	if((bool) strpos( $browser['agent']  , 'iPad')){ 	// for iPad
		$browser['platform']='iPad';
	}elseif((bool) strpos( $browser['agent']  , 'iPhone')){ 	// for iPhone
		$browser['platform']='iPhone';
	}elseif((bool) strpos($browser['agent']  , 'iPod')){ 	// for iPod
		$browser['platform']='iPod';
	}elseif(((bool) strpos( $browser['agent']  , 'Linux')) && ((bool)strpos( $browser['agent']  , 'Android')) ){ 	// for Android
		$browser['platform']='Android';
	}elseif( ((bool) strpos( $browser['agent'] , 'Linux')) && (!(bool)strpos( $browser['agent'] , 'Android')) ){ 	// for Linux
		$browser['platform']='Linux';
	}elseif( ((bool) strpos( $browser['agent']  , 'Windows')) ){
		$browser['platform']='Windows';
	}elseif( ((bool) strpos($browser['agent']  , 'Macintosh')) ){
		$browser['platform']='Mac';
	}else{
		$browser['platform']='Others';
	}



	/*----------------------------------------- browser name ---------------------------------------------*/
	if((bool) strpos( $browser['agent'] , 'Firefox')){ 	// for iPad
		$browser['browser']='Firefox';
	}elseif((bool) strpos( $browser['agent']  , 'Chrome')){ 	// for iPhone
		$browser['browser']='Chrome';
	}elseif((bool) strpos( $browser['agent']  , 'MSIE')){ 	// for iPod
		$browser['browser']='IExplorer';
	}elseif( ((bool) strpos( $browser['agent']  , 'Safari')) ){
		$browser['browser']='Safari';
	}elseif( ((bool) strpos( $browser['agent']  , 'Opera')) ){
		$browser['browser']='Opera';
	}else{
		$browser['browser']='Others';
	}


	/* ------------------------------------------ version number ------------------------------------ */
	if($browser['browser']=='IExplorer'){
		$br='MSIE';
	}else{
		$br=ucfirst($browser['browser']);
	}

	//$known = array('Version', $br, 'other');
	//$pattern = '#(?<browser>' . join('|', $known) .')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	/*if (!preg_match_all($pattern,$browser['agent'], $matches)) {
		// we have no matching number just continue
	}*/
	// see how many we have
	$i = count($matches['browser']);
	if ($i != 1) {
		//we will have two since we are not using 'other' argument yet
		//see if version is before or after the name
		if (strripos($browser['agent'],"Version") < strripos($browser['agent'],$br)){	$version= $matches['version'][0];}
		else {$version= $matches['version'][1];	}
	}
	else {
		$ver=explode('.',$matches['version'][0]);
		$version=$ver[0];
	}
	// check if we have a number
	if ($version==null || $version=="") {$version="?";}


	// Browser verion ------------>
	$browser['version']=$version;

	// Major version --------------->
	//$browser['majorver']=(int)$version;
	/*----------------------------------------- browser name ---------------------------------------------*/
	if($browser['platform']=='Windows'){
		if(strripos($browser['agent'],"NT 6.2")>0){
			$browser['platver']="8";
		} else if(strripos($browser['agent'],"NT 6.1")>0){
			$browser['platver']="7";
		} else if(strripos($browser['agent'],"NT 6.0")>0){
			$browser['platver']="Vista";
		} else if(strripos($browser['agent'],"NT 5.1")>0){
			$browser['platver']="XP";
		} else if(strripos($browser['agent'],"NT 5.2")>0){
			$browser['platver']="XP x64";
		} else if(strripos($browser['agent'],"NT 5.0")>0){
			$browser['platver']="2000";
		} else $browser['platver']="Otro";
	}
	if($arg){
		return $browser[$arg];
	}else{
			return $browser;
	}
}



/*
	This function to validate current browser. this function returns boolian value
	@param $name : browser name
	@param $version: browser version
	@param $platform: browser platform

*/
function is_browser($name, $version='', $platform='')
{
	$name=strtolower($name);
	$curr_brws=get_browser_info('browser');
	$curr_version=get_browser_info('version');
	$curr_platform=get_browser_info('platform');

	if($curr_brws==$name){
		$true[]=true;
	}
	if($curr_version==$version){
		$true[]=true;
	}
	if($curr_platform==$platform){
		$true[]=true;
	}
	if(!empty($true)){
		$true=array_filter($true,trim);
	}
	if(count($true)>0){
		return true;
	}else{
		return false;
	}
}
?>
