$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var message = $("#message");
	var txtTipoid = $("#txtTipoid");
	var txtIdentificacion = $("#txtIdentificacion");
	var txtNombre = $("#txtNombre");
	var txtDireccion = $("#txtDireccion");
	var txtCelular = $("#txtCelular");
	var txtEmail = $("#txtEmail");
	var txtRelacion = $("#txtRelacion");
	var txtEecc = $("#txtEecc");

	
	//On blur
	txtTipoid.blur(validatetxtTipoid);
	txtIdentificacion.blur(validatetxtIdentificacion);
	txtNombre.blur(validatetxtNombre);
	txtDireccion.blur(validatetxtDireccion);
	txtCelular.blur(validatetxtCelular);
	txtEmail.blur(validatetxtEmail);
	txtRelacion.blur(validatetxtRelacion);
	txtEecc.blur(validatetxtEecc);
	
	//On key press
	txtTipoid.keyup(validatetxtTipoid);
	txtIdentificacion.keyup(validatetxtIdentificacion);
	txtNombre.keyup(validatetxtNombre);
	txtDireccion.keyup(validatetxtDireccion);
	txtCelular.keyup(validatetxtCelular);
	txtEmail.keyup(validatetxtEmail);
	txtRelacion.keyup(validatetxtRelacion);
	txtEecc.keyup(validatetxtEecc);
	

	//On Submitting
	form.submit(function(){
		if(validatetxtTipoid() & validatetxtIdentificacion() & 
		validatetxtNombre() & validatetxtDireccion () & 
		validatetxtCelular() & validatetxtEmail() & validatetxtRelacion() & validatetxtEecc()){
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
		function validatetxtTipoid(){
		if(txtTipoid.length === 0){
			txtTipoid.addClass("error");
			return false;
		}else{
			txtTipoid.removeClass("error");
			return true;
		}
	}
		function validatetxtIdentificacion(){
		if(txtIdentificacion.length === 0){
			txtIdentificacion.addClass("error");
			return false;
		}else{
			txtIdentificacion.removeClass("error");
			return true;
		}
	}
		function validatetxtNombre(){
		if(txtNombre.length === 0){
			txtNombre.addClass("error");
			return false;
		}else{
			txtNombre.removeClass("error");
			return true;
		}
	}
		function validatetxtDireccion(){
		if(txtDireccion.length === 0){
			txtDireccion.addClass("error");
			return false;
		}else{
			txtDireccion.removeClass("error");
			return true;
		}
	}
	function validatetxtCelular(){
		if(txtCelular.length === 0){
			txtCelular.addClass("error");
			return false;
		}else{
			txtCelular.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtEmail(){
		if(txtEmail.length === 0){
			txtEmail.addClass("error");
			return false;
		}else{
			txtEmail.removeClass("error");
			return true;
		}
	}
	function validatetxtRelacion(){
		if(txtRelacion.length===0){
			txtRelacion.addClass("error");
			return false;
		}else{
			txtRelacion.removeClass("error");
			return true;
		}
	}
	
	function validatetxtEecc(){
		if(txtEecc.length===0){
			txtEecc.addClass("error");
			return false;
		}else{
			txtEecc.removeClass("error");
			return true;
		}
	}
});
