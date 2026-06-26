$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtPassword0 = $("#txtPassword0");
	var txtPassword1 = $("#txtPassword1");
	var txtPassword2 = $("#txtPassword2");
	var message = $("#message");

	//On blur
	txtPassword0.blur(validatePass0);
	txtPassword1.blur(validatePass1);
	txtPassword2.blur(validatePass2);
	//On key press
	txtPassword0.keyup(validatePass0);
	txtPassword1.keyup(validatePass1);
	txtPassword2.keyup(validatePass2);
	//On Submitting
	form.submit(function(){
		if((validatePass0() &
		   validatePass1() &
      validatePass2()) &&
			validatePolicies()) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});

	//validation functions
	function validatePass0(){
		//it's NOT valid
		if(txtPassword0.val() != '-1'){
			if(txtPassword0.val().length<1){
				txtPassword0.addClass("error");
				return false;
			}
			//it's valid
			else{
				txtPassword0.removeClass("error");
				return true;
			}
		}
		else return true;
	}
	//validation functions
function validatePass1(){
		//is valid
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
					message.text("La contrasena debe contener letras");
					ErrorValidatePassw();
				}
			}else{
				message.text("la contrasena no cumple la longitud minima de 8");
				ErrorValidatePassw();
			}
		}

	function ErrorValidatePassw(){
			txtPassword1.addClass("error");
			return false;
	}
	/*function validatePass1(){
		//it's NOT valid
		if(txtPassword1.val().length<1){
			txtPassword1.addClass("error");
			return false;
		}
		//it's valid
		else{
			txtPassword1.removeClass("error");
			validatePass2();
			return true;
		}
	}*/
	function validatePass2(){
		//are NOT valid
		if( txtPassword1.val() != txtPassword2.val() ){
		message.text("las contrasenas no coinciden");
			txtPassword2.addClass("error");
			return false;
		}
		//are valid
		else{
			txtPassword2.removeClass("error");
			return true;
		}
	}
	$('#txtId').change(function() {
		if($(this).attr('value') != $('#txtUid').val()){
			$('#txtPassword0').val("-1");
			$('#txtPassword0').hide();
			$('#tables-all tbody>tr[data-row=100]').hide();
		}
		else {
			$('#txtPassword0').val("");
			$('#txtPassword0').show();
			$('#tables-all tbody>tr[data-row=100]').show();
		}
	});
	function validatePolicies(){
		var result = true;
		$("#pswd_info ul").empty();
		$.ajax({
			type: "POST",
			async: false,
			url: "callback/pwd.inc.php",
			data: "mode=policies",
			success: function(returnData){
				if(returnData.indexOf('OK')===0){
					var data = returnData.split("|");
					for (var i=1;i<data.length;i++){
						//0=nombre,1=valor,2=regla,3=mensaje
						var row = data[i].split("^");
						var res = row[2];
						if (!res) {
							result = false;
							$("#pswd_info ul").append('<li class="invalid">'+row[3]+'</li>');
							//console.debug(data[i]);
						}
					}
				}
			}
		});
		//Validacion de Historico
		$.ajax({
			type: "POST",
			async: false,
			url: "callback/pwd.inc.php",
			data: "mode=history&id="+
			$("#txtId").val() + "&pwd="+ txtPassword1.val(),
			success: function(returnData){
				if(returnData.indexOf('OK')!==0){
					result = false;
					$("#pswd_info ul").append('<li class="invalid">'+returnData+'</li>');
				}
			}
		});
		if (result) {
			$('#pswd_info').hide();
		} else {
			$('#pswd_info').show();
		}
		return result;
	}
});
