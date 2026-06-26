$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtMO = $("#txtMO");
	var txtMateriales = $("#txtMateriales");
	var txtPares1 = $("#txtPares1");
	var txtPares2 = $("#txtPares2");
	var txtArmario = $("#txtArmario");
	var txtCable = $("#txtCable");
	var txtMaxVel = $("#txtMaxVel");
	var txtDistancia1 = $("#txtDistancia1");
	var txtDistancia2 = $("#txtDistancia2");
	var message = $("#message");
	
	//On blur
	txtMO.blur(validatetxtMO);
	txtMateriales.blur(validatetxtMateriales);
	txtPares1.blur(validatetxtPares1);
	txtPares2.blur(validatetxtPares2);
	txtArmario.blur(validatetxtArmario);
	txtCable.blur(validatetxtCable);
	txtMaxVel.blur(validatetxtMaxVel);
	txtDistancia1.blur(validatetxtDistancia1);
	txtDistancia2.blur(validatetxtDistancia2);
	//On key press
	txtMO.keyup(validatetxtMO);
	txtMateriales.keyup(validatetxtMateriales);
	txtPares1.keyup(validatetxtPares1);
	txtPares2.keyup(validatetxtPares2);
	txtArmario.keyup(validatetxtArmario);
	txtCable.keyup(validatetxtCable);
	txtMaxVel.keyup(validatetxtMaxVel);
	txtDistancia1.keyup(validatetxtDistancia1);
	txtDistancia2.keyup(validatetxtDistancia2);
	
	//On Submitting
	form.submit(function(){
		if(
			validatetxtMO() &
			validatetxtMateriales() &
			validatetxtPares1() &
			validatetxtPares2() &
			validatetxtArmario() &
			validatetxtCable() &
			validatetxtMaxVel() &
			validatetxtDistancia1() &
			validatetxtDistancia2()
			) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
	function validatetxtMO(){
		//if it's NOT valid
		if(txtMO.val().length != 0){
			var val1 = parseFloat(txtMO.val());
			if(isNaN(val1)){
				$("#txtTotal").val(0);
				txtMO.addClass("error");
				return false;
			}
			else{
				var val2 = parseFloat(txtMateriales.val());
				if(!isNaN(val2)){
					$("#txtTotal").val(moneyFormat(val1+val2));
				}
				txtMO.removeClass("error");
				return true;
			}
		}
		//if it's valid
		else{
			txtMO.addClass("error");
			return false;
		}
	}	
	//validation functions
	function validatetxtMateriales(){
		//if it's NOT valid
		if(txtMateriales.val().length != 0){
			var val1 = parseFloat(txtMateriales.val());
			if(isNaN(val1)){
				$("#txtTotal").val(0);
				txtMateriales.addClass("error");
				return false;
			}
			else{
				var val2 = parseFloat(txtMO.val());
				if(!isNaN(val2)){
					$("#txtTotal").val(moneyFormat(val1+val2));
				}
				txtMateriales.removeClass("error");
				return true;
			}
		}
		//if it's valid
		else{
			txtMateriales.addClass("error");
			return false;
		}
	}	
	//validation functions
	function validatetxtArmario(){
		//if it's NOT valid
		if(txtArmario.val().length === 0){
			txtArmario.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtArmario.removeClass("error");
			return true;
		}
	}	
	//validation functions
	function validatetxtCable(){
		//if it's NOT valid
		if(txtCable.val().length === 0){
			txtCable.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtCable.removeClass("error");
			return true;
		}
	}	
	//validation functions
	function validatetxtMaxVel(event){
		//if it's NOT valid
		if(event===undefined){
			event = jQuery.Event("keypress");
			event.ctrlKey = false;
			event.keyCode = 48;
			event.which = 48;
		}
		if(txtMaxVel.val().length != 0){
			if (String.fromCharCode(event.keyCode).match(/\w/)){
				var val = parseInt(txtMaxVel.val(),10);
				if(isNaN(val)){
					txtMaxVel.addClass("error");
				}
				else{
					txtMaxVel.val(val);
					txtMaxVel.removeClass("error");
					return true;
				}
			}
		}
		//if it's valid
		else{
			txtMaxVel.addClass("error");
		}
		return false;
	}	
	//validation functions
	function validatetxtDistancia1(event){
		//if it's NOT valid
		if(event===undefined){
			event = jQuery.Event("keypress");
			event.ctrlKey = false;
			event.keyCode = 48;
			event.which = 48;
		}
		if(txtDistancia1.val().length != 0){
			if (String.fromCharCode(event.keyCode).match(/\w/)){
				var val = parseInt(txtDistancia1.val(),10);
				if(isNaN(val)){
					txtDistancia1.addClass("error");
				}
				else{
					txtDistancia1.val(val);
					txtDistancia1.removeClass("error");
					return true;
				}
			}
		}
		//if it's valid
		else{
			txtDistancia1.addClass("error");
		}
		return false;
	}	
	//validation functions
	function validatetxtDistancia2(event){
		if(event===undefined){
			event = jQuery.Event("keypress");
			event.ctrlKey = false;
			event.keyCode = 48;
			event.which = 48;
		}
		//if it's NOT valid
		if(txtDistancia2.val().length != 0){
			if (String.fromCharCode(event.keyCode).match(/\w/)){
				var val = parseInt(txtDistancia2.val(),10);
				if(isNaN(val)){
					txtDistancia2.addClass("error");
				}
				else{
					txtDistancia2.val(val);
					txtDistancia2.removeClass("error");
					return true;
				}
			}
		}
		//if it's valid
		else{
			txtDistancia2.addClass("error");
		}
		return false;
	}	
	//validation functions
	function validatetxtPares1(event){
		if(event===undefined){
			event = jQuery.Event("keypress");
			event.ctrlKey = false;
			event.keyCode = 48;
			event.which = 48;
		}
		if(txtPares1.val().length != 0){
			if (String.fromCharCode(event.keyCode).match(/\w/)){
				var val = parseInt(txtPares1.val(),10);
				if(isNaN(val)){
					txtPares1.addClass("error");
				}
				else{
					txtPares1.val(val);
					txtPares1.removeClass("error");
					return true;
				}
			}
		}
		//if it's valid
		else{
			txtPares1.addClass("error");
		}
		return false;
	}	
	//validation functions
	function validatetxtPares2(event){
		if(event===undefined){
			event = jQuery.Event("keypress");
			event.ctrlKey = false;
			event.keyCode = 48;
			event.which = 48;
		}
		//if it's NOT valid
		if(txtPares2.val().length != 0){
			if (String.fromCharCode(event.keyCode).match(/\w/)){
				var val = parseInt(txtPares2.val(),10);
				if(isNaN(val)){
					txtPares2.addClass("error");
				}
				else{
					txtPares2.val(val);
					txtPares2.removeClass("error");
					return true;
				}
			}
		}
		//if it's valid
		else{
			txtPares2.addClass("error");
		}
		return false;
	}	
});
