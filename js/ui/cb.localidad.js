$(document).ready(function(){
	jQuery.fn.exists = function(){return this.length>0;}
	$(function() {
		$("#txtLocalidad").multiselect({
			multiple: false,
			header: "Seleccione uno",
			selectedList: 1
		}).multiselectfilter();
	});	
});
function enableLocalidad(){
	$("#txtLocalidad").multiselect().multiselect('enable');
}