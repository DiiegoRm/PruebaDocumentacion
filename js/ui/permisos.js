$(document).ready(function(){
	jQuery.fn.exists = function(){return this.length>0;}
	$(function() {
		$("#txtRoles").multiselect().multiselectfilter();
	});	
});