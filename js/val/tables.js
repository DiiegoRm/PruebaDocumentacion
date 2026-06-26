$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtNombre = $("#txtNombre");
	var message = $("#message");
	
	//On blur
	txtNombre.blur(validatetxtName);
	//On key press
	txtNombre.keyup(validatetxtName);
	//On Submitting
	form.submit(function(){
		if(validatetxtName()){
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	//validation functions
	function validatetxtName(){
		//if it's NOT valid
		if(jQuery.trim(txtNombre.val()).length === 0){
			txtNombre.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtNombre.removeClass("error");
			return true;
		}
	}
});