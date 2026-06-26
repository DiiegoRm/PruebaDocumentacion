$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtNombre = $("#txtNombre");
	var txtProyecto = $("#txtProyecto");
	var txtMO = $("#txtMO");
	var txtCable = $("#txtCable");
	var txtOtros = $("#txtOtros");
	var txtPeriodo = $("#txtPeriodo");
	var txtTipo = $("#txtTipo");
	var txtRed = $("#txtRed");
	var txtTipoOT = $("#txtTipoOT");
	var message = $("#message");
	
	//On blur
	txtNombre.blur(validatetxtName);
	txtProyecto.blur(validatetxtProyecto);
	txtMO.blur(validatetxtMO);
	txtCable.blur(validatetxtCable);
	txtOtros.blur(validatetxtOtros);
	txtPeriodo.blur(validatetxtPeriodo);
	txtTipo.blur(validatetxtTipo);
	txtRed.blur(validatetxtRed);
	txtTipoOT.blur(validatetxtTipoOT);
	//On key press
	txtNombre.keyup(validatetxtName);
	txtProyecto.keyup(validatetxtProyecto);
	txtMO.blur(validatetxtMO);
	txtCable.keyup(validatetxtCable);
	txtOtros.keyup(validatetxtOtros);
	txtPeriodo.keyup(validatetxtPeriodo);
	txtTipo.keyup(validatetxtTipo);
	txtRed.keyup(validatetxtRed);
	txtTipoOT.keyup(validatetxtTipoOT);
	//On Submitting
	form.submit(function(){
		if(validatetxtName() & 
            validatetxtProyecto() &
            validatetxtMO() &
            validatetxtCable() &
            validatetxtOtros() &
            validatetxtPeriodo() &
            validatetxtTipo() &
            validatetxtRed() &
            validatetxtTipoOT()
						) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
	//validation functions
	function validatetxtName(){
		//if it's NOT valid
		if(txtNombre.val().length === 0){
			txtNombre.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtNombre.removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtProyecto(){
		//if it's NOT valid
		if(txtProyecto.val().length === 0){
			txtProyecto.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtProyecto.removeClass("error");
			return true;
		}
	}
	function validatetxtMO(){
		//if it's NOT valid
		if(txtMO.val().length === 0){
			txtMO.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtMO.removeClass("error");
			return true;
		}
	}
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
	function validatetxtOtros(){
		//if it's NOT valid
		if(txtOtros.val().length === 0){
			txtOtros.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtOtros.removeClass("error");
			return true;
		}
	}
	function validatetxtPeriodo(){
		//if it's NOT valid
		if(txtPeriodo.val().length === 0){
			txtPeriodo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtPeriodo.removeClass("error");
			return true;
		}
	}
	function validatetxtTipo(){
		//if it's NOT valid
		if(txtTipo.val().length === 0){
			txtTipo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtTipo.removeClass("error");
			return true;
		}
	}
	function validatetxtRed(){
		//if it's NOT valid
		if(txtRed.val().length === 0){
			txtRed.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtRed.removeClass("error");
			return true;
		}
	}
	function validatetxtTipoOT(){
		//if it's NOT valid
		if(txtTipoOT.val().length === 0){
			txtTipoOT.addClass("error");
			return false;
		}
		//if it's valid
		else{
			txtTipoOT.removeClass("error");
			return true;
		}
	}
});
