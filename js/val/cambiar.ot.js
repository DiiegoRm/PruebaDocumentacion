$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtOT = $("#txtOT").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1
		}).multiselectfilter();
	
	var txtPP = $("#txtPP").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1
		}).multiselectfilter();

	var message = $("#message");
	
	//On blur
	txtOT.blur(validatetxtOT);
	txtPP.blur(validatetxtPP);
	//On key press
	txtOT.keyup(validatetxtOT);
	txtPP.keyup(validatetxtPP);
	//On Submitting
	form.submit(function(){
		if(validatetxtOT()&validatetxtPP()) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
	function validatetxtOT(){
		if(txtOT.val().length != 0){
				txtOT.removeClass("error");
				return true;
		}
		//if it's valid
		else{
				txtOT.addClass("error");
			return false;
		}
	}
	function validatetxtPP(){
		if(txtPP.val().length != 0){
				txtPP.removeClass("error");
				return true;
		}
		//if it's valid
		else{
				txtPP.addClass("error");
			return false;
		}
	}
	});
