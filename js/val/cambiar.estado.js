$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtEstado = $("#txtEstado");
	var message = $("#message");
	
	//On Submitting
	form.submit(function(){
		return true;
	});
