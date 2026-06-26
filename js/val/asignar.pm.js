$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtOrdenPM = $("#txtOrdenPM");
	var txtSolpedPM = $("#txtSolpedPM");
	var txtReservaPM = $("#txtReservaPM");
	var message = $("#message");
	
	//On blur
	txtOrdenPM.blur(validatetxtOrdenPM);
	txtSolpedPM.blur(validatetxtSolpedPM);
	txtReservaPM.blur(validatetxtReservaPM);
	//On key press
	txtOrdenPM.keyup(validatetxtOrdenPM);
	txtSolpedPM.keyup(validatetxtSolpedPM);
	txtReservaPM.keyup(validatetxtReservaPM);
	//On Submitting
	form.submit(function(){
		if(validatetxtOrdenPM() & validatetxtSolpedPM() & validatetxtReservaPM()) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
	function validatetxtOrdenPM(){
		if(txtOrdenPM.val().length != 0){
			var val = parseInt(txtOrdenPM.val(),10);
			if(isNaN(val)||val<=0){
				txtOrdenPM.addClass("error");
				return false;
			}
			else{
				txtOrdenPM.val(val);
				txtOrdenPM.removeClass("error");
				return true;
			}
		}
		//if it's valid
		else{
				txtOrdenPM.addClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtSolpedPM(){
		if(txtSolpedPM.val().length != 0){
			var val = parseInt(txtSolpedPM.val(),10);
			if(isNaN(val)||val<=0){
				txtSolpedPM.addClass("error");
				return false;
			}
			else{
				txtSolpedPM.val(val);
				txtSolpedPM.removeClass("error");
				return true;
			}
		}
		//if it's valid
		else{
				txtSolpedPM.addClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtReservaPM(){
		if(txtReservaPM.val().length != 0){
			var val = parseInt(txtReservaPM.val(),10);
			if(isNaN(val)||val<=0){
				txtReservaPM.addClass("error");
				return false;
			}
			else{
				txtReservaPM.val(val);
				txtReservaPM.removeClass("error");
				return true;
			}
		}
		//if it's valid
		else{
				txtReservaPM.addClass("error");
			return true;
		}
	}
});
