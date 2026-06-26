$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtCodigo = $("#txtCodigo");
	var txtItem = $("#txtItem");
	var txtValor = $("#txtValor");
	var txtUnidad = $("#txtUnidad");
	var txtTipo = $("#txtTipo");
	var txtFactor1 = $("#txtFactor1");
	var txtFactor2 = $("#txtFactor2");
	var txtFactor3 = $("#txtFactor3");
	var message = $("#message");
	
	//On blur
	txtCodigo.blur(validatetxtCodigo);
	txtItem.blur(validatetxtItem);
	txtValor.blur(validatetxtValor);
	txtUnidad.blur(validatetxtUnidad);
	txtTipo.blur(validatetxtTipo);
	txtFactor1.blur(validatetxtFactor1);
	txtFactor2.blur(validatetxtFactor2);
	txtFactor3.blur(validatetxtFactor3);
	//On key press
	txtCodigo.keyup(validatetxtCodigo);
	txtItem.keyup(validatetxtItem);
	txtValor.keyup(validatetxtValor);
	txtUnidad.keyup(validatetxtUnidad);
	txtTipo.keyup(validatetxtTipo);
	txtFactor1.keyup(validatetxtFactor1);
	txtFactor2.keyup(validatetxtFactor2);
	txtFactor3.keyup(validatetxtFactor3);
	//On Submitting
	form.submit(function(){
		if(validatetxtCodigo() & validatetxtItem() & validatetxtValor() &
            validatetxtUnidad() & validatetxtTipo() & validatetxtFactor1() &
						validatetxtFactor2() & validatetxtFactor3() ) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
	function validatetxtCodigo(){
		//if it's NOT valid
		if(txtCodigo.val().length === 0){
			txtCodigo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtCodigo.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtItem(){
		//if it's NOT valid
		if(txtItem.val().length === 0){
			txtItem.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtItem.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtValor(){
		//if it's NOT valid
		if(txtValor.val().length === 0){
			txtValor.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtValor.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtTipo(){
		//if it's NOT valid
		if(txtTipo.val() == ''){
			txtTipo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtTipo.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtFactor1(){
		//if it's NOT valid
		if(txtFactor1.val().length === 0){
			txtFactor1.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtFactor1.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtFactor2(){
		//if it's NOT valid
		if(txtFactor2.val().length === 0){
			txtFactor2.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtFactor2.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtFactor3(){
		//if it's NOT valid
		if(txtFactor3.val().length === 0){
			txtFactor3.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtFactor3.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtUnidad(){
		//if it's NOT valid
		if(txtUnidad.val().length === 0){
			txtUnidad.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtUnidad.removeClass("error");
			return true;
		}
	}
});
