$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtEecc = $("#txtEecc");
    var txtContrato = $("#txtContrato");
	var txtValue = $("#txtValue");
    var txtStartDate = $("#txtStartDate");
    var txtEndDate = $("#txtEndDate");
	var message = $("#message");

	//On blur
	txtEecc.blur(txtEecc);
	//On key press
	txtEecc.keyup(txtEecc);

	//On blur
	txtContrato.blur(txtContrato);
	//On key press
	txtContrato.keyup(txtContrato);

	//On blur
	txtValue.blur(txtValue);
	//On key press
	txtValue.keyup(txtValue);

    //On blur
	txtStartDate.blur(txtStartDate);
	//On key press
	txtStartDate.keyup(txtStartDate);
    
    //On blur
	txtEndDate.blur(txtEndDate);
	//On key press
	txtEndDate.keyup(txtEndDate);

	//On Submitting
	form.submit(function(){
		if(valtxtEecc() && valtxtContrato() && valtxtValue() && valtxtStartDate() && valtxtEndDate()) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
    function valtxtEecc(){
		//it's NOT valid
		if(txtEecc.val() == ""){
			txtEecc.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtEecc.removeClass("error");
			return true;
		}
	}

	function valtxtContrato(){
		//it's NOT valid
		if(txtContrato.val() == ""){
			txtContrato.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtContrato.removeClass("error");
			return true;
		}
	}

    function valtxtContrato(){
		//it's NOT valid
		if(txtContrato.val() == ""){
			txtContrato.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtContrato.removeClass("error");
			return true;
		}
	}

    function valtxtValue() {
        //it's NOT valid
		if(txtValue.val() == ""){
			txtValue.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtValue.removeClass("error");
			return true;
		}
    }

    function valtxtStartDate() {
        //it's NOT valid
		if(txtStartDate.val() == ""){
			txtStartDate.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtStartDate.removeClass("error");
			return true;
		}
    }

    function valtxtEndDate() {
        //it's NOT valid
		if(txtEndDate.val() == ""){
			txtEndDate.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtEndDate.removeClass("error");
			return true;
		}
    }
});
