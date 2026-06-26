$(document).ready(function(){
	jQuery.fn.exists = function(){return this.length>0;}
	



	$("#txtEstrato").multiselect({
		multiple: false,
		header: "Seleccione uno",
		selectedList: 1
	});//.multiselect('disable');
	


    var txtTipoVB = $("#txtTipoVB");
    
    $( "#txtTipoVB" ).change(function() {
        if(txtTipoVB.val()== 34 || txtTipoVB.val()== 35 || txtTipoVB.val()== 26 || txtTipoVB.val()== 50){
            $( "#txtDs" ).prop( "disabled", false );
		}
		//it's valid
		else{			
			 $( "#txtDs" ).prop( "disabled", true );
		}
    });

    $(function() {
		$("#txtTipoVB").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1
		});
	
		
		//santiago pinilla rivera -- se adiciona validaciones para los nuevos campos que se requieren para el proyecto ftth
		//inicio

		$("#txtRegion").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				var sel = $("#txtJefatura").multiselect();
				if(ui.value === ''){
					sel.multiselect().multiselect('disable');
				}
				else {
					$.ajax({
						type: "POST",
						url: "callback/jefaturasxregion.inc.php",
						data: "mode=query&filter=VB"+
							"&id="+ui.value,
						success: function(returnData){
							sel.empty();
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( sel );
							if(returnData.indexOf('OK')===0){
								var data = returnData.split("|");
								for (var i=1;i<data.length;i++){
									var row = data[i].split("^");
									var name = $("<div/>").html(row[1]).text();
									var opt = $('<option />', {value: row[0],text: name});
									opt.appendTo( sel );
								}
								sel.multiselect().multiselect('enable');
							}else{
								sel.multiselect().multiselect('disable');
							}
							sel.multiselect("uncheckAll");
							sel.multiselect('refresh');
						}
					});						
				}
				return true;
			}
		});

		
		$("#txtregiion").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				var sel = $("#txtPoligono").multiselect();
				if(ui.value === ''){
					sel.multiselect().multiselect('disable');
				}
				else {
					$.ajax({
						type: "POST",
						url: "callback/poligonoxregion.inc.php",
						data: "mode=query&filter=VB"+
							"&id="+ui.value,
						success: function(returnData){
							sel.empty();
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( sel );
							if(returnData.indexOf('OK')===0){
								var data = returnData.split("|");
								for (var i=1;i<data.length;i++){
									var row = data[i].split("^");
									var name = $("<div/>").html(row[1]).text();
									var opt = $('<option />', {value: row[0],text: name});
									opt.appendTo( sel );
								}
								sel.multiselect().multiselect('enable');
							}else{
								sel.multiselect().multiselect('disable');
							}
							sel.multiselect("uncheckAll");
							sel.multiselect('refresh');
						}
					});						
				}
				return true;
			}
		}).multiselectfilter();



		$("#txtPoligono").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				var sel = $("#txtComuna").multiselect();
				if(ui.value === ''){
					sel.multiselect().multiselect('disable');
				}
				else {
					$.ajax({
						type: "POST",
						url: "callback/comunaxpoligono.inc.php",
						data: "mode=query&filter=VB"+
							"&id="+ui.value,
						success: function(returnData){
							sel.empty();
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( sel );
							if(returnData.indexOf('OK')===0){
								var data = returnData.split("|");
								for (var i=1;i<data.length;i++){
									var row = data[i].split("^");
									var name = $("<div/>").html(row[1]).text();
									var opt = $('<option />', {value: row[0],text: name});
									opt.appendTo( sel );
								}
								sel.multiselect().multiselect('enable');
							}else{
								sel.multiselect().multiselect('disable');
							}
							sel.multiselect("uncheckAll");
							sel.multiselect('refresh');
						}
					});						
				}
				return true;
			}
		}).multiselectfilter();


		$("#txtComuna").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				var sel = $("#txtcluster").multiselect();
				if(ui.value === ''){
					sel.multiselect().multiselect('disable');
				}
				else {
					$.ajax({
						type: "POST",
						url: "callback/clusterxcomuna.inc.php",
						data: "mode=query&filter=VB"+
							"&id="+ui.value,
						success: function(returnData){
							sel.empty();
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( sel );
							if(returnData.indexOf('OK')===0){
								var data = returnData.split("|");
								for (var i=1;i<data.length;i++){
									var row = data[i].split("^");
									var name = $("<div/>").html(row[1]).text();
									var opt = $('<option />', {value: row[0],text: name});
									opt.appendTo( sel );
								}
								sel.multiselect().multiselect('enable');
							}else{
								sel.multiselect().multiselect('disable');
							}
							sel.multiselect("uncheckAll");
							sel.multiselect('refresh');
						}
					});						
				}
				return true;
			}
		}).multiselectfilter();

		$("#txtcluster").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1
		}).multiselectfilter();


		$("#txttipo_vb").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1
		}).multiselectfilter();
		

		$("#txtCentral").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1
		}).multiselectfilter();
		
		$("#txtTipoZona").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1
		}).multiselectfilter();

		function cambio() {
			if ($("#txtProyecto option:selected").text().indexOf("FTTH") != '-1' ) {
				bool = true;
				$("#txtEstrato").multiselect('disable');
				$("#txtCentral").multiselect('enable');
				$("#txtCable").prop('disabled', false);
				$("#txtregiion").multiselect('enable');
				$("#txtPoligono").multiselect('enable');
				$("#txtComuna").multiselect('enable');
				$("#txtcluster").multiselect('enable');
				$("#txtTipoZona").multiselect('enable');
				$("#txttipo_vb").multiselect('enable');
				$("#txtconversor").prop('disabled', false);
				$("#txthogarespas").prop('disabled', false);
				$("#txtsubclus").prop('disabled', false);
			}else{
				bool = false;
				console.log("bloquea");
				$("#txtEstrato").multiselect('enable');
				$("#txtLB").prop('disabled', bool);
				$("#txtBA").prop('disabled', bool);
				$("#txtTV").prop('disabled', bool);
				$("#txtViviendas").prop('disabled', bool);
				$("#txtEtapa").prop('disabled', bool);
				$("#txtViviendasEtapa").prop('disabled', bool);
				$("#txtEeccAsig").prop('disabled', bool);
				$("#txtObs").prop('disabled', bool);
				
				$("#txtCable").prop('disabled', true);
				$("#txtCentral").multiselect('disable');
				$("#txtregiion").multiselect('disable');
				$("#txtPoligono").multiselect('disable');
				$("#txtComuna").multiselect('disable');
				$("#txtcluster").multiselect('disable');
				$("#txtTipoZona").multiselect('disable');
				$("#txttipo_vb").multiselect('disable');
				$("#txtconversor").prop('disabled', true);
				$("#txthogarespas").prop('disabled', true);
				$("#txtsubclus").prop('disabled', true);
			}

			$("#txtLB").prop('disabled', bool);
			$("#txtBA").prop('disabled', bool);
			$("#txtTV").prop('disabled', bool);
			$("#txtEstrato").prop('disabled', bool);
			$("#txtViviendas").prop('disabled', bool);
			$("#txtEtapa").prop('disabled', bool);
			$("#txtViviendasEtapa").prop('disabled', bool);
			$("#txtEeccAsig").prop('disabled', bool);
			$("#txtObs").prop('disabled', bool);
				
		};

		cambio();
		
		$('select[name="txtProyecto"]').change(function() {
	cambio();
});

		//final -- validaciones nuevos campos proyecto ftth

		$("#txtJefe").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1
		});//.multiselect('disable');


		$("#txtJefatura").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				var sel1 = $("#txtDepto").multiselect();
				var sel2 = $("#txtJefe").multiselect();
				if(ui.value === ''){
					sel1.multiselect().multiselect('disable');
				}
				else {
					$.ajax({
						type: "POST",
						url: "callback/deptosxjefatura.inc.php",
						data: "mode=query&filter=VB"+
							"&id="+ui.value,
						success: function(returnData){
							sel1.empty();
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( sel1 );
							if(returnData.indexOf('OK')===0){
								var data = returnData.split("|");
								for (var i=1;i<data.length;i++){
									var row = data[i].split("^");
									var name = $("<div/>").html(row[1]).text();
									var opt = $('<option />', {value: row[0],text: name});
									opt.appendTo( sel1 );
								}
								sel1.multiselect().multiselect('enable');
							}else{
								sel1.multiselect().multiselect('disable');
							}
							sel1.multiselect("uncheckAll");
							sel1.multiselect('refresh');
						}
					});
					$.ajax({
						type: "POST",
						url: "callback/jefexjefatura.inc.php",
						data: "mode=query"+
							"&id="+ui.value,
						success: function(returnData){
							sel2.empty();
							if(returnData.indexOf('OK')===0){
								var data = returnData.split("|");
								for (var i=1;i<data.length;i++){
									var row = data[i].split("^");
									var name = $("<div/>").html(row[1]).text();
									var opt = $('<option />', {value: row[0],text: name});
									opt.appendTo( sel2 );
								}
								sel2.multiselect().multiselect('enable');
							}else{
								sel2.multiselect().multiselect('disable');
							}
							sel2.multiselect("uncheckAll");
							sel2.multiselect('refresh');
						}
					});						
				}
				return true;
			}
		}).multiselectfilter();
		
		$("#txtDepto").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				var sel = $("#txtLocalidad").multiselect();
				if(ui.value === ''){
					sel.multiselect().multiselect('disable');
				}
				else {
					$.ajax({
						type: "POST",
						url: "callback/localidadesxdepto.inc.php",
						data: "mode=query&filter=VB"+
							"&id="+ui.value,
						success: function(returnData){
							sel.empty();
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( sel );
							if(returnData.indexOf('OK')===0){
								var data = returnData.split("|");
								for (var i=1;i<data.length;i++){
									var row = data[i].split("^");
									var name = $("<div/>").html(row[1]).text();
									var opt = $('<option />', {value: row[0],text: name});
									opt.appendTo( sel );
								}
								sel.multiselect().multiselect('enable');
							}else{
								sel.multiselect().multiselect('disable');
							}
							sel.multiselect("uncheckAll");
							sel.multiselect('refresh');
						}
					});						
				}
				return true;
			}
		}).multiselectfilter();//.multiselect('disable');
		
		$("#txtLocalidad").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			click: function(event, ui){
				/*var sel1 = $("#txtDistribuidor").multiselect();
				var sel2 = $("#txtPOP").multiselect();
				if(ui.value === ''){
					sel1.multiselect().multiselect('disable');
					sel2.multiselect().multiselect('disable');
				}
				else {
					$.ajax({
						type: "POST",
						url: "callback/centralesxlocalidad.inc.php",
						data: "mode=query"+
							"&id="+ui.value,
						success: function(returnData){
							sel1.empty();
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( sel1 );
							if(returnData.indexOf('OK')===0){
								var data = returnData.split("|");
								for (var i=1;i<data.length;i++){
									var row = data[i].split("^");
									var name = $("<div/>").html(row[0]+" | "+row[1]).text();
									var opt = $('<option />', {value: row[0],text: name});
									opt.appendTo( sel1 );
								}
								sel1.multiselect().multiselect('enable');
							}else{
								sel1.multiselect().multiselect('disable');
							}
							sel1.multiselect("uncheckAll");
							sel1.multiselect('refresh');
						}
					});						
					$.ajax({
						type: "POST",
						url: "callback/popsxlocalidad.inc.php",
						data: "mode=query"+
							"&id="+ui.value,
						success: function(returnData){
							sel2.empty();
							var opt = $('<option />', {value: '',text: '---SELECCIONE---'});
							opt.attr('selected','selected');
							opt.appendTo( sel2 );
							if(returnData.indexOf('OK')===0){
								var data = returnData.split("|");
								for (var i=1;i<data.length;i++){
									var row = data[i].split("^");
									var name = $("<div/>").html(row[1]).text();
									var opt = $('<option />', {value: row[0],text: name});
									opt.appendTo( sel2 );
								}
								sel2.multiselect().multiselect('enable');
							}else{
								sel2.multiselect().multiselect('disable');
							}
							sel2.multiselect("uncheckAll");
							sel2.multiselect('refresh');
						}
					});
				}*/
				return true;
			}
		}).multiselectfilter();//.multiselect('disable');
		
		$("#txtProyecto").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1
		}).multiselectfilter();
		
		$("#txtSegmento").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1,
			minWidth: 150
		});
		
		$("#txtDistribuidor").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1
		}).multiselectfilter();//.multiselect('disable');
		$("#txtPOP").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1
		}).multiselectfilter()//;.multiselect('disable');
	});	
    
});
