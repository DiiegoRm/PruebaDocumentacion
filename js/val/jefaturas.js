$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtNombre = $("#txtNombre");
	var txtRegion = $("#txtRegion");
	var txtJefe = $("#txtJefe");
	var message = $("#message");
	
	//On blur
	txtNombre.blur(validatetxtNombre);
	txtRegion.blur(validatetxtRegion);
	txtJefe.blur(validatetxtJefe);
	//On key press
	txtNombre.keyup(validatetxtNombre);
	txtRegion.keyup(validatetxtRegion);
	txtJefe.keyup(validatetxtJefe);
	//On Submitting
	form.submit(function(){
		if(validatetxtNombre() & 
            validatetxtRegion() &
			validatetxtJefe()
			) {
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
	//validation functions
	function validatetxtJefe(){
		//if it's NOT valid
		if(txtJefe.val().length === 0){
			txtJefe.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtJefe.removeClass("error");
			return true;
		}
	}	
});
