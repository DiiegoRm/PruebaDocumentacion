$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtNombre = $("#txtNombre");
	var txtLocalidad = $("#txtLocalidad");
	var message = $("#message");
	
	//On blur
	txtNombre.blur(validatetxtName);
	txtLocalidad.blur(validatetxtLocalidad);
	//On key press
	txtNombre.keyup(validatetxtName);
	txtLocalidad.keyup(validatetxtLocalidad);
	//On Submitting
	form.submit(function(){
		if(validatetxtName() & 
            validatetxtLocalidad()) {
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
		if(txtNombre.val().length === 0){
			txtNombre.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtNombre.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtLocalidad(){
		//if it's NOT valid
		if(txtLocalidad.val().length === 0){
			txtLocalidad.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtLocalidad.removeClass("error");
			return true;
		}
	}
});
