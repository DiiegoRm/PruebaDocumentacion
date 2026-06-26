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

	
	//On blur
	txtSegmento.blur(validatetxtSegmento);
	txtTipoVB.blur(validatetxtTipoVB);
	txtEntrega.blur(validatetxtEntrega);
	txtLB.blur(validatetxtLB);
	txtBA.blur(validatetxtBA);
	txtTV.blur(validatetxtTV);
	txtDs.blur(validatetxtDs);
	//On key press
	txtSegmento.keyup(validatetxtSegmento);
	txtTipoVB.keyup(validatetxtTipoVB);
	txtEntrega.keyup(validatetxtEntrega);
	txtLB.keyup(validatetxtLB);
	txtBA.keyup(validatetxtBA);
	txtTV.keyup(validatetxtTV);
	txtDs.keyup(validatetxtDs);

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
			validatetxtDs()
			) {
			return true;
		}
		else{
			message.text("El formulario contiene errores!");
			return false;
		}
	});
	
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
		if((txtTipoVB.val()== 35 || txtTipoVB.val()== 34 ||txtTipoVB.val()== 26 ||txtTipoVB.val()== 50)&& txtDs.val().length === 0 ){
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
