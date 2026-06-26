$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtNombre = $("#txtNombre");
	var txtRegion = $("#txtRegion");
	var message = $("#message");
	
	//On blur
	txtNombre.blur(validatetxtNombre);
	txtRegion.blur(validatetxtRegion);

	//On key press
	txtNombre.keyup(validatetxtNombre);
	txtRegion.keyup(validatetxtRegion);

	//On Submitting
	form.submit(function(){
		if (validatetxtNombre() & validatetxtRegion()) {	
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
	function validatetxtRegion(){
		//if it's NOT valid
		if(txtRegion.val().length === 0){
			txtRegion.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtRegion.removeClass("error");
			return true;
		}
	}
});
