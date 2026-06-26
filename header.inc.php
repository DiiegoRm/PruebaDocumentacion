<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es-ES" xml:lang="es-ES">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="GENERATOR" content=".Inc"/>
<meta name="author" content=".Inc"/>
<title>::GestOT::Gestor de Ordenes de Trabajo</title>
<link rel='SHORTCUT ICON' href='i/favicon.ico'/>
<link rel="apple-touch-icon" href="i/apple-touch-icon.png"/>
<script type="text/javascript" src="js/functions.js?ver=<?php echo SGP_VERSION?>"></script>
<script type="text/javascript" src="js/style.js"></script>
<script type="text/javascript" src="js/jquery-3.4.1.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.multiselect.min.js"></script>
<script type="text/javascript" src="js/jquery.multiselect.filter.js"></script>
<script type="text/javascript" src="js/plupload.full.js"></script>
<script type="text/javascript" src="js/jquery.ui.plupload.js"></script>
<script type="text/javascript" src="js/jquery.ganttView.js"></script>
<script type="text/javascript" src="js/date.js"></script>
<script type="text/javascript" src="js/logica.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<!--<script type="text/javascript" src="js/keypress.menu.js"></script>-->
<link rel="stylesheet" type="text/css" href="./css/redmond/jquery-ui-1.12.1.custom.css" />
<link rel="stylesheet" type="text/css" href="./css/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="./css/jquery.multiselect.filter.css" />
<link rel="stylesheet" type="text/css" href="./css/jquery.ui.plupload.css" />
<link rel="stylesheet" type="text/css" href="./css/jquery.ganttView.css" />
<link rel="stylesheet" type="text/css" href="./css/menu.css" media="screen" />

<style type="text/css" media="screen">
/*<![CDATA[*/
@import url("./css/style.css");
/*]]>*/
</style>
<!--[if IE 6]>
<style type="text/css">
body {behavior: url("./css/csshover3.htc");}
#menu li .drop {background:url("./i/arrowmenu.gif") no-repeat right 8px;}
</style>
<![endif]-->
</head>
<body>
<div id="overlay">
	<img id="loading" src="./i/loader.gif">
</div>
<script type="text/javascript">
	$.ajaxSetup({ cache: false });
	$(window).on('load',function() {
		$("#overlay").remove();
		var txts = document.getElementsByTagName('TEXTAREA')
		for(var i = 0, l = txts.length; i < l; i++) {
			if(/^[0-9]+$/.test(txts[i].getAttribute("maxlength"))) {
				var func = function() {
					var len = parseInt(this.getAttribute("maxlength"), 10);
					if(this.value.length > len) {
						this.value = this.value.substr(0, len);
						return false;
					}
				}
				txts[i].onkeyup = func;
				txts[i].onblur = func;
			}
		}
	});
	/*$(function() {
		$('[id]').each(function(){
			var ids = $('[id=\''+this.id+'\']');
			if(ids.length>1 && ids[0]==this)
				console.warn('Multiple IDs #'+this.id);
		});
	});*/
</script>
<div id="contenedor_principal" align="center" style="min-height:100%">
	<div class="header"></div>
