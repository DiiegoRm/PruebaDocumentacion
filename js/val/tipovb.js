$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtNombre = $("#txtNombre");
	var txtArea = $("#txtArea");
	var txtRequerida1 = $("#txtRequerida1");
	var txtRequerida2 = $("#txtRequerida2");
	var txtRequerida3 = $("#txtRequerida3");
	var message = $("#message");

	//On blur
	txtNombre.blur(valtxtNombre);
	txtArea.blur(valtxtArea);
	txtRequerida1.blur(valtxtRequerida1);
	txtRequerida2.blur(valtxtRequerida2);
	txtRequerida3.blur(valtxtRequerida3);
	//On key press
	txtNombre.keyup(valtxtNombre);
	txtArea.keyup(valtxtArea);
	txtRequerida1.keyup(valtxtRequerida1);
	txtRequerida2.keyup(valtxtRequerida2);
	txtRequerida3.keyup(valtxtRequerida3);
	//On Submitting
	form.submit(function(){
		if(valtxtNombre() & valtxtArea() & valtxtRequerida1() & valtxtRequerida2() & valtxtRequerida3()) {
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
	function valtxtArea(){
		//it's NOT valid
		if(txtArea.val().length === 0){
			txtArea.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtArea.removeClass("error");
			return true;
		}
	}
	function valtxtRequerida1(){
		//are NOT valid
		var p = parseInt(txtRequerida1.val(),10);
		if( isNaN(p) || p < 1 || p > 9999){
			txtRequerida1.addClass("error");
			return false;
		}
		//are valid
		else{
			txtRequerida1.removeClass("error");
			return true;
		}
	}	
	function valtxtRequerida2(){
		//are NOT valid
		var p = parseInt(txtRequerida2.val(),10);
		if( isNaN(p) || p < 1 || p > 9999){
			txtRequerida2.addClass("error");
			return false;
		}
		//are valid
		else{
			txtRequerida2.removeClass("error");
			return true;
		}
	}	
	function valtxtRequerida3(){
		//are NOT valid
		var p = parseInt(txtRequerida3.val(),10);
		if( isNaN(p) || p < 1 || p > 9999){
			txtRequerida3.addClass("error");
			return false;
		}
		//are valid
		else{
			txtRequerida3.removeClass("error");
			return true;
		}
	}	
});
