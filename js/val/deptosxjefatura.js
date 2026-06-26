$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtDepto = $("#txtDepto");
	var txtJefatura = $("#txtJefatura");
	var message = $("#message");
	
	//On blur
	txtDepto.blur(validatetxtDepto);
	txtJefatura.blur(validatetxtJefatura);
	//On key press
	txtDepto.keyup(validatetxtDepto);
	txtJefatura.keyup(validatetxtJefatura);
	//On Submitting
	form.submit(function(){
		if(validatetxtDepto() & 
            validatetxtJefatura()) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
	function validatetxtDepto(){
		//if it's NOT valid
		if(txtDepto.val().length === 0){
			txtDepto.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtDepto.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtJefatura(){
		//if it's NOT valid
		if(txtJefatura.val().length === 0){
			txtJefatura.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtJefatura.removeClass("error");
			return true;
		}
	}
});
