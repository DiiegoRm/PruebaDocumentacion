$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtNombre = $("#txtNombre");
	var txtIva = $("#txtIva");
	var message = $("#message");

	//On blur
	txtNombre.blur(valtxtNombre);
	txtIva.blur(valtxtIva);
	//On key press
	txtNombre.keyup(valtxtNombre);
	txtIva.keyup(valtxtIva);
	//On Submitting
	form.submit(function(){
		if(valtxtNombre() & valtxtIva()) {
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
	//validation functions
	function valtxtIva(){
		//are NOT valid
		var p = parseFloat(txtIva.val());
		if( isNaN(p) || p < 0 || p > 100){
			txtIva.addClass("error");
			return false;
		}
		//are valid
		else{
			txtIva.removeClass("error");
			return true;
		}
	}
});
