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
		if(validatetxtNombre()) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
	function validatetxtNombre(){
		//if it's NOT valid
		if(txtNombre.val().length < 3){
			txtNombre.addClass("error");
			message.text("El formulario contiene errores!");
			return false;
		}
		//if it's valid
		else{
			txtNombre.removeClass("error");
			return true;
		}
	}
});
