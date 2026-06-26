$(document).ready(function(){
	//global vars
	let form = $("#frmSubmit");
	let txtCalibrado = $('#txtCalibrado');
	let txtSerial = $('#txtSerial');
	let txtEquipo = $('#txtEquipo');
	let txtAuditado = $('#txtAuditado');
	let txtEECC = $('#txtEECC');
	let txtDepto = $('#txtDepto');
	let txtMarca = $('#txtMarca');
	let txtFuncionalidad = $('#txtFuncionalidad');
	let txtFechaCal = $('#txtFechaCal');
	let txtFechaVen = $('#txtFechaVen');
	var message = $("#message");

    // txtCalibrado.on('change',() => {
    //     if(txtCalibrado.val() == 'Si'){
    //         for(let i = 0; i < vista1.length; i++){
    //             vista1[i].style.opacity = "1";
    //         }
    //     }else{
    //         for(let i = 0; i < vista1.length; i++){
    //             vista1[i].style.opacity = "0";
    //         }
    //     }
    // })

	txtSerial.blur(valtxtSerial);
	//On key press
	txtSerial.keyup(valtxtSerial);
	//On Submitting
	form.submit(function(){

				if(valtxtEECC() && valtxtDepto() && valtxtMarca() && valtxtFuncionalidad() && valtxtSerial() && valtxtFechaCal() && valtxtFechaVen()) {
					if(valCalibracion()) {
						if(valVencimiento()){
							return true;
						}else { message.text("La fecha de vencimiento debe ser mayor a la fecha actual"); return false; }
					}else { message.text("La fecha de calibracion debe ser menor a la fecha actual"); return false; }
				}
				else{
					message.text("El formulario contiene errores!");
					return false;
				}
	});

	function valVencimiento() {
		let date = new Date();
		let compare = new Date(txtFechaVen.val().replace(/-/g, '-'));
		if(compare >= date) {
			txtFechaVen.removeClass("error");
			return true;
		} else {
			txtFechaVen.addClass("error");
			return false;
		}
	}

	function valCalibracion() {
		let date = new Date();
		let compare = new Date(txtFechaCal.val().replace(/-/g, '-'));
		if(compare <= date) {
			txtFechaCal.removeClass("error");
			return true;
		} else {
			txtFechaCal.addClass("error");
			return false;
		}
	}
	
	function valtxtFechaCal(){
		//it's NOT valid
		if(txtFechaCal.val() == ""){
			txtFechaCal.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtFechaCal.removeClass("error");
			return true;
		}
	}

	function valtxtFechaVen(){
		//it's NOT valid
		if(txtFechaVen.val() == ""){
			txtFechaVen.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtFechaVen.removeClass("error");
			return true;
		}
	}

	function valtxtFuncionalidad(){
		//it's NOT valid
		if(txtFuncionalidad.val() == ""){
			txtFuncionalidad.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtFuncionalidad.removeClass("error");
			return true;
		}
	}

	function valtxtMarca(){
		//it's NOT valid
		if(txtMarca.val() == ""){
			txtMarca.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtMarca.removeClass("error");
			return true;
		}
	}

	function valtxtDepto(){
		return true;
	}
	
	//validation functions
	function valtxtSerial(){
		//it's NOT valid
		if(txtSerial.val().length < 3){
			txtSerial.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtSerial.removeClass("error");
			return true;
		}
	}
	//
	function valtxtEECC(){
		//it's NOT valid
		if(txtEECC.val() == ""){
			txtEECC.addClass("error");
			return false;
		}
		//it's valid
		else{			
			txtEECC.removeClass("error");
			return true;
		}
	}
});
