function getSpinner() {
	return '<img src="./i/bigloader.gif" style="vertical-align:middle;"> Cargando....';
}
function returnAdd(menu) {
	location.href = '?menu='+menu+'&mode=add';
}
function attend(menu,id) {
	location.href = '?menu='+menu+'&mode=update&id='+id;
}
function addOT(menu,id1,id2) {
	var res = confirm("Esta seguro que desea crear una Orden de Trabajo ?");

	if(!res) return false;
	location.href = '?menu='+menu+'&mode=make&id1='+id1+'&id2='+id2;
	return true;
}
function loadCurrentTab(tab) {
	var url = location.href;
	var newurl = url;
	if (url.indexOf("tab=") > 0) {
		var regEx = /([?&]tab)=([^#&]*)/g;
    		newurl = url.replace(regEx, '&tab='+tab);
	} else {
		newurl = url + '&tab=' + tab;
	}
	location.href=ValidateReturn(newurl);
	//location.reload();
}

function loadCurrentTabAndBaremo(tab,bar) {
	var url = location.href;
	var newurl = url;
	if (url.indexOf("tab=") > 0) {
		var regEx = /([?&]tab)=([^#&]*)/g;
    newurl = url.replace(regEx, '&tab='+tab);
	} else {
		newurl = url + '&tab=' + tab;
	}
	if (newurl.indexOf("bar=") > 0) {
		var regEx = /([?&]bar)=([^#&]*)/g;
    		newurl = newurl.replace(regEx, '&bar='+bar);
	} else {
		newurl = newurl + '&bar=' + bar;
	}
	location.href=ValidateReturn(newurl);
			//location.reload();
}



function ValidateReturn(newurl){
	let termino ="=http";// palabra a buscar comun en cambio de url (http://example.com/example.php?url=http://malicious.example.com) o (http://example.com/example.php?q=http://malicious.example.com)
	if(!newurl){
		return false;
	}else{
		let posicion = newurl.indexOf(termino);
if (posicion !== -1){
		return false;
		}
else{
		return newurl;
		}
	}
}


function state(menu,id) {
	location.href = '?menu='+menu+'&mode=chg&id='+id;
}
function cloneVB(menu,id) {
	location.href = '?menu='+menu+'&mode=add&clone='+id;
}
function clonePP(menu,id,idCont) {
    if  (idCont<28){
        alert("No se puede clonar un presupuesto de contrato antiguo.");
       } else {
            var res = confirm("Esta seguro que desea clonar el Presupuesto?");

            if(!res) return;
            location.href = '?menu='+menu+'&mode=clone&id='+id;
       }
}
function editPP(menu,id,tipo) {
	var res = confirm("Esta seguro que desea editar el Presupuesto?");

	if(!res) return;
	location.href = '?menu='+menu+'&mode=edit&id='+id+'&tipo='+tipo;
}
function cloneOT(menu,id,idCont) {
   if  (idCont<28){
    alert("No se puede clonar una OT de contrato antiguo.");
   } else {
        var res = confirm("Esta seguro que desea clonar la Orden de Trabajo?");
        if(!res) return;
        location.href = '?menu='+menu+'&mode=clone&id='+id;
   }
}
function exportXLS(hash) {
	window.open('export/index.php?id='+hash,'_blank');
}
function exportXLSUsuario(hash) {
	window.open('export/usuarios.inc.php?id='+hash,'_blank');
}
function returnCancel(menu) {
	location.href = '?menu='+menu;
}
function doHandleAll() {
	with (document.frmSubmit) {
		if(elements['allCheck'].checked == false){
			doUnCheckAll();
		}
		else if(elements['allCheck'].checked == true){
			doCheckAll();
		}
	}
}

function doCheckAll() {
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if (elements[i].type == 'checkbox') {
				elements[i].checked = true;
			}
		}
	}
}

function doUnCheckAll() {
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if (elements[i].type == 'checkbox') {
				elements[i].checked = false;
			}
		}
	}
}
function unCheckMain(){
	with (document.frmSubmit) {
		elements['allCheck'].checked = false;
	}
}
function sortAndSearch(sort,order,menu,locState,locCode,locName) {
	var uri = "?menu="+menu+"&sort=" + sort + "&order=" + order;
	document.frmSubmit.action = uri;
	document.frmSubmit.pageNO.value=1;

	document.frmSubmit.captureState.value = locState;
	document.frmSubmit.loc_code = locCode;
	document.frmSubmit.loc_name = locName;

	document.frmSubmit.submit();
}

function checkSelection() {
	$check = 0;
	with (document.frmSubmit) {
		for (var i=0; i < elements.length; i++) {
			if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
				$check = 1;
			}
		}
	}
	return $check;
}

function checkNot() {
	$(".chk").prop('checked', false); 
	if( $('#chkNot').is(':checked')) {
		$(".chk").attr("disabled", true);
	} else {
		$(".chk").attr("disabled", false);
	}
}

function returnDelete() {

	if (checkSelection() == 1){

		var res = confirm("Esta seguro que desea borrar los registros seleccionados ?");

		if(!res) return;

		document.frmSubmit.delState.value = 'DeleteMode';
		document.frmSubmit.pageNO.value=1;
		document.frmSubmit.submit();
	}else{

		alert("Debe seleccionar minimo un registro para borrar");
	}
}

function returnAuditar() {

	if (checkSelection() == 1){

		// var res = confirm("Esta seguro que desea auditar los registros seleccionados ?");

		// if(!res) return;
		let url = "callback/equipo.inc.php?menu=208&mode=search";
		let data = new FormData(document.querySelector('#frmSubmit'));
		fetch(url,{
			method: 'POST',
			body: data
		})
		.then(req => req.json())
		.then(res => {
			if(res.Calibrado == "No"){
				alert("El equipo debe estar calibrado");
			}else{
				$('#frmSubmit').attr('action','?menu=208&mode=aud');
				$('#frmSubmit').submit();
			}
		})
		.catch(err => console.log(err))
		// location.href = "?menu=208&mode=aud";
		
	}else{

		alert("Debe seleccionar minimo un registro para auditar");
	}
}

function returnCancelpp(){

	if (checkSelection() == 1){
		var res = confirm("Esta seguro que desea cancelar los registros seleccionados ?");

		if(!res) return;

		document.frmSubmit.delState.value = 'CancelMode';
		document.frmSubmit.pageNO.value=1;
		document.frmSubmit.submit();

	}else{
		alert("Debe seleccinar minimo un registro para cancelar");
	}

}

function returnanulpp(){

	if (checkSelection() == 1){
		var res = confirm("Esta seguro que desea anular los registros seleccionados ?");

		if(!res) return;

		document.frmSubmit.delState.value = 'AnulMode';
		document.frmSubmit.pageNO.value=1;
		document.frmSubmit.submit();
	}else{
		alert("Debe seleccinar minimo un registro para anular");
		}
}
function returnDisable() {

	if (checkSelection() == 1){

		var res = confirm("Esta seguro que desea desactivar los registros seleccionados ?");

		if(!res) return;

		document.frmSubmit.delState.value = 'DisableMode';
		document.frmSubmit.pageNO.value=1;
		document.frmSubmit.submit();
	}else{
		alert("Debe seleccionar minimo un registro para desactivar");
	}
}
function returnCorregir() {

	if (checkSelection() == 1){

		var res = confirm("Esta seguro que desea corregir los registros seleccionados ?");

		if(!res) return;

		document.frmSubmit.delState.value = 'CorrectMode';
		document.frmSubmit.pageNO.value=1;
		document.frmSubmit.submit();
	}else{
		alert("Debe seleccionar minimo un registro para corregir");
	}
}
function returnEnable() {

	if (checkSelection() == 1){

		var res = confirm("Esta seguro que desea activar los registros seleccionados ?");

		if(!res) return;

		document.frmSubmit.delState.value = 'EnableMode';
		document.frmSubmit.pageNO.value=1;
		document.frmSubmit.submit();
	}else{
		alert("Debe seleccionar minimo un registro para activar");
	}
}

function returnEquipos(){
	if (document.frmSubmit.loc_code.value == -1) {
		alert("Seleccione un campo de busqueda!");
		document.frmSubmit.loc_code.focus();
		return;
	};
	document.frmSubmit.captureState.value = 'SearchMode';
	document.frmSubmit.pageNO.value=1;
	document.frmSubmit.submit();
}

function returnSearch() {

	if (document.frmSubmit.loc_code.value == -1) {
		alert("Seleccione un campo de busqueda!");
		document.frmSubmit.loc_code.focus();
		return;
	};
	document.frmSubmit.captureState.value = 'SearchMode';
	document.frmSubmit.pageNO.value=1;
	document.frmSubmit.submit();

}
function returnSearcharm() {

	if (document.frmSubmit.loc_code.value == -1) {
		alert("Seleccione un campo de busqueda!");
		document.frmSubmit.loc_code.focus();
		return;
	};

	document.frmSubmit.enviado.value = 'Boton';
	document.frmSubmit.captureState.value = 'SearchMode';
	document.frmSubmit.pageNO.value=1;
	document.frmSubmit.submit();

}

function returnFilter() {
	document.frmSubmit.captureState.value = 'SearchMode';
	document.frmSubmit.pageNO.value=1;
	document.frmSubmit.submit();

}

function returnFilterLoad() {
	document.frmSubmit.enviado.value = 'Boton';
	document.frmSubmit.captureState.value = 'SearchMode';
	document.frmSubmit.pageNO.value=1;
	document.frmSubmit.submit();
}

function clearFilter() {
    for(i=0; i<document.frmSubmit.length; i++){
        document.frmSubmit[i].value = "";
    }
    document.frmSubmit.submit();
}
function clear_form() {
	document.frmSubmit.loc_code.options[0].selected=true;
	document.frmSubmit.loc_oper.options[0].selected=true;
	document.frmSubmit.loc_name.value='';
	document.frmSubmit.submit();
}
function reset() {
	$('frmSubmit').reset();
}
function actionSol(estado){
	$("#txtEstadoID").val(estado);
    $("#frmSubmit").submit();
}
function actionRes(estado){
	$("#txtEstadoID").val(estado);
    $("#frmSubmit").submit();
}
function edit() {
	var editBtn = $('#editBtn');
	if(editBtn.text()=='Guardar') {
		$("#frmSubmit").submit();
		return;
	}
	var frm=document.frmSubmit;
	for (var i=0; i < frm.elements.length; i++) {
		frm.elements[i].disabled = false;
	}
	editBtn.text("Guardar");
}
function make() {
	var makeBtn = $('#makeBtn');
	if(makeBtn.text()=='Generar') {
		document.frmSubmit.txtMake.value=1;
		$("#frmSubmit").submit();
		return;
	}
}
function closeVB(id,val) {
	//var closeBtn = $('#closeBtn');

	var res = confirm("Seguro que desea '"+val+"' la Viabilidad?");

	if(!res) return;

	if(val) {
		document.frmSubmit.txtClose.value=id;
		$("#frmSubmit").submit();
		return;
	}
}

function saveRespVB() {
	var makeBtn = $('#saveBtn');
	if(makeBtn.text()=='Guardar') {
		document.frmSubmit.txtSave.value=1;
		$("#frmSubmit").submit();
		return;
	}
}
function saveEstadoVB() {
	var makeBtn = $('#saveBtn');
	if(makeBtn.text()=='Actualizar') {
		document.frmSubmit.txtSave.value=1;
		$("#frmSubmit").submit();
		return;
	}
}
function makeRespVB() {
	var makeBtn = $('#makeBtn');
	if(makeBtn.text()=='Responder') {
		document.frmSubmit.txtSave.value=2;
		$("#frmSubmit").submit();
		return;
	}
}
function makePresupuesto() {
	document.frmSubmit.txtMake.value=100;
	$("#frmSubmit").submit();
}
function makeOrden(FechaRequerida) {
    var FechaR = new Date("'"+FechaRequerida+"'");
    var FechaHoy = new Date();
    var FechaActual = new Date(FechaHoy.getFullYear()+"-"+(FechaHoy.getMonth() +1)+"-"+FechaHoy.getDate());

    if(FechaR<FechaActual){
    alert("La fecha requerida debe ser mayor o igual al dia de hoy, modificarla en el cronograma");
     return false;
    } else {
        var res = confirm("Esta seguro que desea Iniciar la Orden de Trabajo ?");
        if(res){
            document.frmSubmit.txtMake.value=100;
            $("#frmSubmit").submit();
        }
	   return false;
    }
}

function onKeyPressed(ev) {
   var e = ev || event;
   if(e.keyCode == 13) {
		returnSearch();
   }
}
function nextPage() {
	var i=(document.frmSubmit.pageNO.value);
	document.frmSubmit.pageNO.value=i+1;
	document.frmSubmit.submit();
}

function prevPage() {
	var i=(document.frmSubmit.pageNO.value);
	document.frmSubmit.pageNO.value=i-1;
	document.frmSubmit.submit();
}

function chgPage(pNO) {
	document.frmSubmit.pageNO.value=pNO;
	document.frmSubmit.submit();
}
function isEventKey(event) {
	return ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 ||
					event.keyCode == 27 || event.keyCode == 13 ||
	// Allow: Ctrl+A
	(event.keyCode == 65 && event.ctrlKey === true) ||
	// Allow: home, end, left, right
	(event.keyCode >= 35 && event.keyCode <= 39));
}
/*function moneyFormat(value){
	var p = parseFloat(value).toFixed(2).split(".");
	return ["$", p[0].split("").reverse().reduce(function(acc, value, i) {
	   return value + (i && !(i % 3) ? "," : "") + acc;
   }, "."), p[1]].join("");
}*/
function roundNumber(value,len){
	len = typeof len !== 'undefined' ? len : 2;
	return new Number(value).toFixed(len);
}

function toFloat(nStr){
	return parseFloat(nStr.toString().replace(/,/g,''));
}

function toFormat(nStr) {
	nStr = roundNumber(toFloat(nStr));
	nStr +=''; //=toString();
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

/*function handleEnter (event) {
	var keyCode = event.keyCode ? event.keyCode :
		event.which ? event.which : event.charCode;
	if (keyCode == 13) {
		return false;
	} else return true;
}*/

function getDateFromString(value,add){
	var f = value.split("-");
	var date = new Date(f[0], f[1] - 1, f[2]);
	var days = parseInt(add,10);

	if(!isNaN(days) && days > 0){
		date.setDate(date.getDate() + days);
	}
	return date;
}
