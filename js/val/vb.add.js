$(document).ready(function(){
	//global vars
	var form = $("#frmSubmit");
	var txtSegmento = $("#txtSegmento");
	var txtTipoVB = $("#txtTipoVB");
	var txtEntrega = $("#txtEntrega");
	var txtLB = $("#txtLB");
	var txtBA = $("#txtBA");
	var txtTV = $("#txtTV");
	var message = $("#message");
	var txtDs = $("#txtDs");
	//nuevos campos

	var txtCable = $("#txtCable");
	var txtCentral = $("#txtCentral");
	var txtregiion = $("#txtregiion");
	var txtconversor = $("#txtconversor");
	var txtPoligono = $("#txtPoligono");
	var txthogarespas = $("#txthogarespas");
	var txtComuna = $("#txtComuna");
	var txtsubclus = $("#txtsubclus");
	var txtcluster = $("#txtcluster");
	var txtTipoZona = $("#txtTipoZona");
	var txttipo_vb = $("#txttipo_vb");

	
	//On blur
	txtSegmento.blur(validatetxtSegmento);
	txtTipoVB.blur(validatetxtTipoVB);
	txtEntrega.blur(validatetxtEntrega);
	txtLB.blur(validatetxtLB);
	txtBA.blur(validatetxtBA);
	txtTV.blur(validatetxtTV);
	txtDs.blur(validatetxtDs);
	//nuevos campos
	
	txtCable.blur(validatetxtCable);
	txtCable.blur(validatetxtCableuno);
	txtCentral.blur(validatetxtCentral);
	txtregiion.blur(validatetxtregiion);
	txtconversor.blur(validatetxtconversor);
	txtconversor.blur(validatetxtconversorTipoCampo);
	txtPoligono.blur(validatetxtPoligono);
	txthogarespas.blur(validatetxthogarespas);
	txthogarespas.blur(validatetxthogarespasuno);
	txtComuna.blur(validatetxttxtComuna);
	txtsubclus.blur(validatetxtsubclus);
	txtcluster.blur(validatetxtcluster);
	txtTipoZona.blur(validatetxtTipoZona);
	txttipo_vb.blur(validatetxttipo_vb);
	//On key press
	txtSegmento.keyup(validatetxtSegmento);
	txtTipoVB.keyup(validatetxtTipoVB);
	txtEntrega.keyup(validatetxtEntrega);
	txtLB.keyup(validatetxtLB);
	txtBA.keyup(validatetxtBA);
	txtTV.keyup(validatetxtTV);
	txtDs.keyup(validatetxtDs);
	
		//nuevos campos
		txtCable.keyup(validatetxtCable);
		txtCable.keyup(validatetxtCableuno);
		txtCentral.keyup(validatetxtCentral);
		txtregiion.keyup(validatetxtregiion);
		txtconversor.keyup(validatetxtconversor);
		txtconversor.keyup(validatetxtconversorTipoCampo);
		txtPoligono.keyup(validatetxtPoligono);
		txthogarespas.keyup(validatetxthogarespas);
		txthogarespas.keyup(validatetxthogarespasuno);
		txtComuna.keyup(validatetxttxtComuna);
		txtsubclus.keyup(validatetxtsubclus);
		txtcluster.keyup(validatetxtcluster);
		txtTipoZona.keyup(validatetxtTipoZona);
		txttipo_vb.keyup(validatetxttipo_vb);

	$('#txtViviendas').on('change', function () {
		var val = parseInt($(this).val(),10);
		$(this).val(!isNaN(val)?val:"");
	});
	
	//On Submitting
	form.submit(function(){
		//alert('HOLA');
		if(
			validatetxtSegmento() &&
			validatetxtTipoVB() &
			validatetxtEntrega() &
			validatetxtLB() &
			validatetxtBA() &
			validatetxtTV() &
			validatetxtDs() &
			validarCanposFTTH()
	
			) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});

function validarCanposFTTH(){
	if ($("#txtProyecto option:selected").text().indexOf("FTTH") == '-1' ){
return true;
	}
	if(
	validatetxtCable()&
	validatetxtCableuno()&
	validatetxtCentral()&
	validatetxtregiion()&
	validatetxtPoligono()&
	validatetxthogarespas()&
	validatetxthogarespasuno()&
	validatetxttxtComuna()&
	validatetxtsubclus()&
	validatetxtcluster()&
	validatetxtTipoZona()&
	validatetxttipo_vb()&
	validatetxtconversor()&
	validatetxtconversorTipoCampo()
	){
		return true
	}
	return false;
}

		//validation functions
		function validatetxtCableuno(){
			//if it's NOT valid
			if (isEventKey(event)) {
				return true;
			}
			if(txtCable.val().length != 0){
				var val = parseInt(txtCable.val(),10);
				if(isNaN(val)){
					txtCable.addClass("error");
					return false;
				}
				else{
					txtCable.val(val);
					txtCable.removeClass("error");
					return true;
				}
			}
			//if it's valid
			else{
				txtCable.removeClass("error");
				return true;
			}
			
			
		}	

			//validation functions
			function validatetxthogarespasuno(){
				//if it's NOT valid
				if (isEventKey(event)) {
					return true;
				}
				if(txthogarespas.val().length != 0){
					var val = parseInt(txthogarespas.val());
					if(isNaN(val)){
						txthogarespas.addClass("error");
						return false;
						
					}
					else{
						
						txthogarespas.val(val);
						txthogarespas.removeClass("error");
						return true;
					}
				}
				//if it's valid
				else{
					txthogarespas.removeClass("error");
					return true;
				}
				
				
			}	

			function validatetxtconversorTipoCampo(e) {
var regex = /^[a-zA-Z@]+$/;
if (isEventKey(event)) {
	return true;
}
if(txtconversor.val().length != 0){
if (regex.test(this.value) !== true){
this.value = this.value.replace(/[^a-zA-Z@]+/, '');
txtconversor.addClass("error");
return false;
}
else{
	txtconversor.removeClass("error");
	return true;
    }
}
else{
	txtconversor.removeClass("error");
	return true;
}
}


	//validacion nuevos campos 
//validation functions

function validatetxtCable(){
	//if it's NOT valid
	if(txtCable.length>0){
		if(txtCable.val().length === 0){
			$("#lbcable").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbcable").removeClass("error");
			return true;
		}
	}
	return true;
}


//validation functions
function validatetxtCentral(){
	//if it's NOT valid
	if(txtCentral.length>0){
		if(txtCentral.val().length === 0){
			$("#lbCentral").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbCentral").removeClass("error");
			return true;
		}
	}
	return true;
}


//validation functions
function validatetxtregiion(){
	//if it's NOT valid
	if(txtregiion.length>0){
		if(txtregiion.val().length === 0){
			$("#lbregiion").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbregiion").removeClass("error");
			return true;
		}
	}
	return true;
}


//validation functions
function validatetxtconversor(){
	//if it's NOT valid
	if(txtconversor.length>0){
		if(txtconversor.val().length === 0){
			$("#lbconversor").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbconversor").removeClass("error");
			return true;
		}
	}
	return true;
}


//validation functions
function validatetxtPoligono(){
	//if it's NOT valid
	console.log('entra a validar poligono');
	if(txtPoligono.length>0){
		if(txtPoligono.val().length === 0){
			$("#lbPoligono").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbPoligono").removeClass("error");
			return true;
		}
	}
	return true;
}


//validation functions
function validatetxthogarespas(){
	//if it's NOT valid
	if(txthogarespas.length>0){
		if(txthogarespas.val().length === 0){
			$("#lbhogarespas").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbhogarespas").removeClass("error");
			return true;
		}
	}
	return true;
}


//validation functions
function validatetxttxtComuna(){
	//if it's NOT valid
	if(txtComuna.length>0){
		if(txtComuna.val().length === 0){
			$("#lbComuna").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbComuna").removeClass("error");
			return true;
		}
	}
	return true;
}

//validation functions
function validatetxtsubclus(){
	//if it's NOT valid
	if(txtsubclus.length>0){
		if(txtsubclus.val().length === 0){
			$("#lbsubclus").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbsubclus").removeClass("error");
			return true;
		}
	}
	return true;
}

//validation functions
function validatetxtcluster(){
	//if it's NOT valid
	if(txtcluster.length>0){
		if(txtcluster.val().length === 0){
			$("#lbcluster").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbcluster").removeClass("error");
			return true;
		}
	}
	return true;
}

//validation functions
function validatetxtTipoZona(){
	//if it's NOT valid
	if(txtTipoZona.length>0){
		if(txtTipoZona.val().length === 0){
			$("#lbTipoZona").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbTipoZona").removeClass("error");
			return true;
		}
	}
	return true;
}

//validation functions
function validatetxttipo_vb(){
	//if it's NOT valid
	if(txttipo_vb.length>0){
		if(txttipo_vb.val().length === 0){
			$("#lbtipo_vb").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbtipo_vb").removeClass("error");
			return true;
		}
	}
	return true;
}

	//validation functions
	function validatetxtSegmento(){
		//if it's NOT valid
		if(txtSegmento.length>0){
			if(txtSegmento.val().length === 0){
				$("#lbSegmento").addClass("error");
				return false;
			}
			//if it's valid
			else{
				$("#lbSegmento").removeClass("error");
				return true;
			}
		}
		return true;
	}
	//validation functions
	function validatetxtTipoVB(){
		//if it's NOT valid
		if(txtTipoVB.val().length === 0){
			$("#lbTipoVB").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbTipoVB").removeClass("error");
			return true;
		}
	}
	 //validation functions
	function validatetxtDs(){
		//if it's NOT valid
		if((txtTipoVB.val()== 35 || txtTipoVB.val()== 34 || txtTipoVB.val()== 26 || txtTipoVB.val()== 50)&& txtDs.val().length === 0 ){
			$("#lbDs").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbDs").removeClass("error");
			return true;
		}
	}

	//validation functions
	function validatetxtEntrega(){
		//if it's NOT valid
		if(txtEntrega.val().length === 0){
			$("#lbEntrega").addClass("error");
			return false;
		}
		//if it's valid
		else{
			$("#lbEntrega").removeClass("error");
			return true;
		}
	}
	//validation functions
	function validatetxtLB(){
		//if it's NOT valid
		if (isEventKey(event)) {
			return true;
		}
		if(txtLB.val().length != 0){
			var val = parseInt(txtLB.val(),10);
			if(isNaN(val)){
				txtLB.addClass("error");
				return false;
			}
			else{
				txtLB.val(val);
				txtLB.removeClass("error");
				return true;
			}
		}
		//if it's valid
		else{
			txtLB.removeClass("error");
			return true;
		}
	}	
	//validation functions
	function validatetxtBA(){
		//if it's NOT valid
		if (isEventKey(event)) {
			return true;
		}
		if(txtBA.val().length != 0){
			var val = parseInt(txtBA.val(),10);
			if(isNaN(val)){
				txtBA.addClass("error");
				return false;
			}
			else{
				txtBA.val(val);
				txtBA.removeClass("error");
				return true;
			}
		}
		//if it's valid
		else{
			txtBA.removeClass("error");
			return true;
		}
	}	
	//validation functions
	function validatetxtTV(){
		//if it's NOT valid
		if (isEventKey(event)) {
			return true;
		}
		if(txtTV.val().length != 0){
			var val = parseInt(txtTV.val(),10);
			if(isNaN(val)){
				txtTV.addClass("error");
				return false;
			}
			else{
				txtTV.val(val);
				txtTV.removeClass("error");
				return true;
			}
		}
		//if it's valid
		else{
			txtTV.removeClass("error");
			return true;
		}
	}
	/*
	// Client side form validation
	$('form').submit(function(e) {
        var uploader = $('#uploader').plupload('getUploader');
        // Files in queue upload them first
        if (uploader.files.length > 0) {
            // When all files are uploaded submit form
            uploader.bind('StateChanged', function() {
                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                    $('form')[0].submit();
                }
 });
            uploader.start();
        } /*else
            alert('You must at least upload one file.');*-/
        return false;
});*/
});
