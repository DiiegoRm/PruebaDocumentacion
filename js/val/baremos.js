$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtDesc = $("#txtDesc");
	var txtItem = $("#txtItem");
	var txtClase = $("#txtClase");
	//var txtBaremoId = $("#txtBaremoId");
	var txtUnidad = $("#txtUnidad");
	var txtPuntos = $("#txtPuntos");
	var txtMaterial = $("#txtMaterial");
	var txtMetodo = $("#txtMetodo");
	var txtFactor1 = $("#txtFactor1");
	var txtFactor2 = $("#txtFactor2");
	var txtFactor3 = $("#txtFactor3");
	var message = $("#message");
	
	//On blur
	txtDesc.blur(validatetxtDesc);
	txtItem.blur(validatetxtItem);
	txtClase.blur(validatetxtClase);
	//txtBaremoId.blur(validatetxtBaremoId);
	txtUnidad.blur(validatetxtUnidad);
	txtPuntos.blur(validatetxtPuntos);
	txtMaterial.blur(validatetxtMaterial);
	txtMetodo.blur(validatetxtUnidad);
	txtFactor1.blur(validatetxtFactor1);
	txtFactor2.blur(validatetxtFactor2);
	txtFactor3.blur(validatetxtFactor3);
	//On key press
	txtDesc.keyup(validatetxtDesc);
	txtItem.keyup(validatetxtItem);
	txtClase.keyup(validatetxtClase);
	//txtBaremoId.keyup(validatetxtBaremoId);
	txtUnidad.keyup(validatetxtUnidad);
	txtPuntos.keyup(validatetxtPuntos);
	txtMaterial.keyup(validatetxtMaterial);
	txtMetodo.keyup(validatetxtUnidad);
	txtFactor1.keyup(validatetxtFactor1);
	txtFactor2.keyup(validatetxtFactor2);
	txtFactor3.keyup(validatetxtFactor3);
	//On Submitting
	form.submit(function(){
		if(validatetxtDesc() & validatetxtItem() & validatetxtClase() &
            validatetxtPuntos() & validatetxtMaterial() & validatetxtMetodo() &
            /*validatetxtBaremoId() &*/ validatetxtUnidad() & validatetxtFactor1() &
						validatetxtFactor2() & validatetxtFactor3() ) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
	function validatetxtDesc(){
		//if it's NOT valid
		if(txtDesc.val().length === 0){
			txtDesc.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtDesc.removeClass("error");
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
	function validatetxtClase(){
		//if it's NOT valid
		if(txtClase.val().length === 0){
			txtClase.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtClase.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtUnidad(){
		//if it's NOT valid
		if(txtUnidad.val() == ''){
			txtUnidad.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtUnidad.removeClass("error");
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
	/*function validatetxtBaremoId(){
		//if it's NOT valid
		if(txtBaremoId.val().length === 0){
			txtBaremoId.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtBaremoId.removeClass("error");
			return true;
		}
	}*/
	//validation functions
	function validatetxtPuntos(){
		//if it's NOT valid
		if(txtPuntos.val().length === 0){
			txtPuntos.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtPuntos.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtMaterial(){
		//if it's NOT valid
		if(txtMaterial.val().length === 0){
			txtMaterial.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtMaterial.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtMetodo(){
		//if it's NOT valid
		if(txtMetodo.val().length === 0){
			txtMetodo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtMetodo.removeClass("error");
			return true;
		}
	}
});
