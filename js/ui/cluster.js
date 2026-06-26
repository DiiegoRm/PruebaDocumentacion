$(document).ready(function(){
	jQuery.fn.exists = function(){return this.length>0;}
	

		
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
			
				return true;
			}
		

	});		});	
    