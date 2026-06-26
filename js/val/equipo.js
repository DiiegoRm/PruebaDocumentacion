$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtNombre = $("#txtNombre");
    var txtEquipo = $("#txtEquipo");
	var txtMarca = $("#txtMarca");
	var message = $("#message");

	//On blur
	txtEquipo.blur(txtEquipo);
	//On key press
	txtEquipo.keyup(txtEquipo);

	//On blur
	txtNombre.blur(txtNombre);
	//On key press
	txtNombre.keyup(txtNombre);

	//On blur
	txtMarca.blur(txtMarca);
	//On key press
	txtMarca.keyup(txtMarca);

	//On Submitting
	form.submit(function(){
		if(valtxtNombre() && valtxtEquipo() && valtxtMarca()) {
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

	function valtxtEquipo(){
		//it's NOT valid
		if(txtEquipo.val() == ""){
			txtEquipo.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtEquipo.removeClass("error");
			return true;
		}
	}

	function valtxtMarca(){
		//it's NOT valid
		if(txtMarca.val() == ""){
			txtMarca.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtMarca.removeClass("error");
			return true;
		}
	}
});
