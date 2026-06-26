$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtNombre = $("#txtNombre");
	var message = $("#message");
	
	//On blur
	txtNombre.blur(validatetxtNombre);
	//On key press
	txtNombre.keyup(validatetxtNombre);
	//On Submitting
	form.submit(function(){
		if (validatetxtNombre()) {	
			return true;
		} else {
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
	function validatetxtNombre(){
		if(txtNombre.val().length === 0){
			txtNombre.addClass("error");
			return false;
		}
		else{
			txtNombre.removeClass("error");
			return true;
		}
	}
});
