$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtPEP = $("#txtPEP");
	var message = $("#message");
	
	//On blur
	txtPEP.blur(validatetxtPEP);
	//On key press
	txtPEP.keyup(validatetxtPEP);
	//On Submitting
	form.submit(function(){
		if(validatetxtPEP()) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
	function validatetxtPEP(){
		if(txtPEP.val().length != 0){
				txtPEP.removeClass("error");
				return true;
		}
		//if it's valid
		else{
				txtPEP.addClass("error");
			return false;
		}
	}});
