$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtNombre = $("#txtNombre");
	var message = $("#message");
	let txtHallazgo = $('#txtHallazgo');
	let view1 = $('#view1');

	let txtEstado = $("#txtEstado");
	let txtCertificado = $("#txtCertificado");
	let txtObservaciones = $("#txtObservaciones");
	let txtAuditado = $("#txtAuditado");
	let txtRepresentante = $("#txtRepresentante");

	// txtHallazgo.on('change',()=>{
	// 	if(txtHallazgo.val() == "Si"){
	// 		view1.show();
	// 	}else{
	// 		view1.hide();
	// 	}
	// });
	//On blur
	txtNombre.blur(valtxtNombre);
	//On key press
	txtNombre.keyup(valtxtNombre);
	//On Submitting
	form.submit(function(){
		if(valtxtNombre()) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});

	function valtxtEstado(){
		//it's NOT valid
		if(txtEstado.val() == ""){
			txtEstado.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtEstado.removeClass("error");
			return true;
		}
	}

	function valtxtCertificado(){
		//it's NOT valid
		if(txtCertificado.val() == ""){
			txtCertificado.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtCertificado.removeClass("error");
			return true;
		}
	}

	function valtxtObservaciones(){
		//it's NOT valid
		if(txtObservaciones.val().length < 3){
			txtObservaciones.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtObservaciones.removeClass("error");
			return true;
		}
	}

	function valtxtHallazgo(){
		//it's NOT valid
		if(txtHallazgo.val() == ""){
			txtHallazgo.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtHallazgo.removeClass("error");
			return true;
		}
	}

	function valtxtAuditado(){
		//it's NOT valid
		if(txtAuditado.val() == ""){
			txtAuditado.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtAuditado.removeClass("error");
			return true;
		}
	}

	function valtxtRepresentante(){
		//it's NOT valid
		if(txtRepresentante.val().length < 3){
			txtRepresentante.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtRepresentante.removeClass("error");
			return true;
		}
	}

	
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
});
