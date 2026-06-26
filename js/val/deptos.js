$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtNombre = $("#txtNombre");
	var txtFactor = $("#txtFactor");
	var message = $("#message");
	
	//On blur
	txtNombre.blur(validatetxtNombre);
	txtFactor.blur(validatetxtFactor);
	//On key press
	txtNombre.keyup(validatetxtNombre);
	txtFactor.keyup(validatetxtFactor);
	
	//On Submitting
	form.submit(function(){
		if(validatetxtNombre()&validatetxtFactor()) {
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
function validatetxtFactor(){
		if (isEventKey(event)) {
			return true;
		}
		if(txtFactor.val().length != 0){
			var val = parseFloat(txtFactor.val());
			if(isNaN(val)){
				txtFactor.addClass("error");
				return false;
			}
			else{
				txtFactor.val(toFormat(txtFactor.val()));
				txtFactor.removeClass("error");
				return true;
			}
		}
		//if it's valid
		else{
				txtFactor.addClass("error");
			return true;
		}
	}
});
