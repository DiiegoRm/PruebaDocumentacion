$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtEquipo = $("#txtEquipo");
    var txtOt = $("#txtOt");
	var message = $("#message");

	//On blur
	txtEquipo.blur(txtEquipo);
	//On key press
	txtEquipo.keyup(txtEquipo);

	//On blur
	txtOt.blur(txtOt);
	//On key press
	txtOt.keyup(txtOt);

	//On Submitting
	form.submit(function(){
		if(valtxtEquipo() && valtxtOt()) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
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

    function valtxtOt(){
        //it's NOT valid
		if(txtOt.val() == ""){
			txtOt.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtOt.removeClass("error");
			return true;
		}
    }
});
