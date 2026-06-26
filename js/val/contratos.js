$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtNumero = $("#txtNumero");
	var txtEecc = $("#txtEECC");
	var txtZona = $("#txtZona");
	var message = $("#message");
	
	//On blur
	txtNumero.blur(validatetxtNumero);
	txtEecc.blur(validatetxtEecc);
	txtZona.blur(validatetxtZona);
	//On key press
	txtNumero.keyup(validatetxtNumero);
	txtEecc.keyup(validatetxtEecc);
	txtZona.keyup(validatetxtZona);
	//On Submitting
	form.submit(function(){
		if(validatetxtNumero() & 
            validatetxtEecc() & validatetxtZona()) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
	function validatetxtNumero(){
		//if it's NOT valid
		if(txtNumero.val().length === 0){
			txtNumero.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtNumero.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtEecc(){
		//if it's NOT valid
		if(txtEecc.val().length === 0){
			txtEecc.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtEecc.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtZona(){
		//if it's NOT valid
		if(txtZona.val().length === 0){
			txtZona.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtZona.removeClass("error");
			return true;
		}
	}
});
