$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtId = $("#txtId");
	var txtNombre = $("#txtNombre");
	var txtDepto = $("#txtDepto");
	var message = $("#message");
	
	//On blur
	txtId.blur(validatetxtId);
	txtNombre.blur(validatetxtName);
	txtDepto.blur(validatetxtDepto);
	//On key press
	txtId.keyup(validatetxtId);
	txtNombre.keyup(validatetxtName);
	txtDepto.keyup(validatetxtDepto);
	//On Submitting
	form.submit(function(){
		if(validatetxtId() & validatetxtName() & validatetxtDepto()) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
	function validatetxtId(){
		//if it's NOT valid
		$val = parseInt(txtId.val(),10);
		if(isNaN($val)){
			txtId.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtId.val($val);
			txtId.removeClass("error");
			return true;
		}
	}
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
});
