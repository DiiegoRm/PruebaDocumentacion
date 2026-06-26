$(document).ready(function(){
	jQuery.fn.exists = function(){return this.length>0;}
	//global vars
	var form = $("#frmSubmit");
	var txtUid = $("#txtId");
    var txtUsername = $("#txtUsername");
	var txtNombre = $("#txtNombre");
	var txtPassword1 = $("#txtPassword1");
	var txtPassword2 = $("#txtPassword2");
    var txtEmail = $("#txtEmail");
	var txtPhone = $("#txtPhone");
	var message = $("#message");
	
	//On blur
	txtUid.blur(validatetxtUid);
    txtUsername.blur(validatetxtUsername);
	txtNombre.blur(validatetxtName);
	txtPassword1.blur(validatePass1);
	txtPassword2.blur(validatePass2);
	txtEmail.blur(validatetxtEmail);
	txtPhone.blur(validatetxtPhone);
	//On key press
	txtUid.keyup(validatetxtUid);
    txtUsername.keyup(validatetxtUsername);
	txtNombre.keyup(validatetxtName);
	txtPassword1.keyup(validatePass1);
	txtPassword2.keyup(validatePass2);
	txtEmail.keyup(validatetxtEmail);
	txtPhone.keyup(validatetxtPhone);
	
	//On Submitting
	form.submit(function(){
		if(validatetxtUid() &
           validatetxtUsername() & 
            validatetxtName() &
            validatePass1() &
            validatePass2() &
			validatetxtEmail() &
			validatetxtPhone()) {
			return true;
		}
		else{
			//message.text("El formulario contiene errores!");
			return false;
		}
	});
	//validation functions
    function validatetxtUid(){
		//if it's NOT valid
        var id = parseInt(txtUid.val(),10);

		if(txtUid.val().length < 6||isNaN(id)){
			message.text("El formulario contiene errores!");
			txtUid.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtUid.removeClass("error");
			return true;
		}
	}
	
	//validation functions
	function validatetxtUsername(){
		//if it's NOT valid
		if(txtUsername.val().length < 5){
			txtUsername.addClass("error");
			message.text("El formulario contiene errores!");
			return false;
		}
		//if it's valid
		else{
			txtUsername.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtName(){
		//if it's NOT valid
		if(txtNombre.val().length < 3){
			txtNombre.addClass("error");
			message.text("El formulario contiene errores!");
			return false;
		}
		//if it's valid
		else{
			txtNombre.removeClass("error");
			return true;
		}
	}
	//validation functions
		/*function validatePass1(){
		//it's NOT valid
		if($("#txtPassword1").exists()){
			if(txtPassword1.val().length>=8){
				if(txtPassword1.val().match(/[A-z]/)){
					if (txtPassword1.val().match(/[A-Z]/)) {
						if (txtPassword1.val().match(/\d/)) {
							if(txtPassword1.val().match(/[!,%, &, @, #, $, ^, *,?, _, ~]/)){
								txtPassword1.removeClass("error");
								validatePass2();
								return true;
							}else {
								message.text("La contrasena contener un caracter especial");
								ErrorValidatePassw();
							}
						}
						else {
							message.text("La contrasena contener numeros");
							ErrorValidatePassw();
						}
					}
					else {
						message.text("La contrasena contener minimo una letra mayuscula");
						ErrorValidatePassw();
					}
				}else {
					message.text("La contrasena debe contner letras");
					ErrorValidatePassw();
				}
			}else{
				message.text("la contrasena no cumple la longitud minima de 8");
				ErrorValidatePassw();
			}
			/*if(txtPassword1.val().length <8){
				txtPassword1.addClass("error");
				return false;
			}
			//it's valid
			else{
				txtPassword1.removeClass("error");
				validatePass2();
				return true;
			}*/
		}
		else {
			return true;
		}
	}

	function ErrorValidatePassw(){
			txtPassword1.addClass("error");
			return false;
	}*/	function validatePass2(){
		//are NOT valid
		if($("#txtPassword2").exists()){
			if( txtPassword1.val() != txtPassword2.val() ){
				txtPassword2.addClass("error");
				message.text("las contrasenas no coinciden");
				return false;
			}
			//are valid
			else{
				txtPassword2.removeClass("error");
				return true;
			}
		}
		else {
			return true;
		}
		
	}
	//validation functions
	function validatetxtEmail(){
		//if it's NOT valid
		//console.log();
		var re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
		if(!re.test(txtEmail.val())){
			txtEmail.addClass("error");
			message.text("El formulario contiene errores!");
			return false;
		}
		//if it's valid
		else{
			txtEmail.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtPhone(){
		//if it's NOT valid
		if(txtPhone.val().length < 7){
			txtPhone.addClass("error");
			message.text("El formulario contiene errores!");
			return false;
		}
		//if it's valid
		else{
			txtPhone.removeClass("error");
			return true;
		}
	}
	enableGroup();
});
