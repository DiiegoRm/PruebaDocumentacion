$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtNombre = $("#txtNombre");
	var message = $("#message");

	//On blur
	txtNombre.blur(valtxtNombre);
	//On key press
	txtNombre.keyup(valtxtNombre);
	//On Submitting
	form.submit(function(){
		if(valtxtNombre()) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
	function valtxtNombre(){
		//it's NOT valid
		if(txtNombre.val().length < 3){
			txtNombre.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtNombre.removeClass("error");
			return true;
		}
	}
});
