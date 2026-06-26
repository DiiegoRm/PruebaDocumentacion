$(document).ready(function(){
  function fajax(url, params, metodo){
		$.ajax({
			url : url,
			data : params,
			type : 'POST',
    		dataType : 'html',
			success : function(data) {
		    metodo(data);
			},
    	error : function(xhr, status) {
        alert('Disculpe, existió un problema');
		}
		});
	}


  //--------aignacion de departamento a localidad----------------------------
      $("#txtDepto").change(function () {//si cambia el valor en el select se activa
          var variable=$("#txtDepto").val();//recoge el actual valor del select

          $.ajax({
          type: 'POST',
          url: '/includes/database.php',//ruta donde se ejecutan las funciones ligadas a los formularios
          data: {variable: variable},
          success : function(response) {
            $("#txtLocalidad").html(response);//envia el valor de variable recogido del select depto (se ejecuta solo en la funcion getComboBoxPrueba())CHR
          },
          error : function(xhr, status) {
            //alert('Disculpe, existió un problema a localidad');
          }});

      });

//----------asignacion de tipo proyecto (segun ftth) a cluster ---------------------------------------
      $("#txtTipo").change(function(){//adicion ftth
        var variable3=$("#txtTipo").val();//valor del campo tipo proyecto
        var variable4=$("#txtDepto").val();//valor del campo departamento
        $.ajax({
          type:'POST',
          url:'/includes/database.php',//ruta donde se ejecutan las funciones ligadas a los formularios
          data: {variable3:variable3,variable4:variable4},
          success:function(response){
            $("#txtidcluster").html(response);
          },
          error:function(xhr,status){
            alert("Disculpe, existio un problema");
          }
        });
      });
//----------asignacion de tipo proyecto (segun ftth) a subcluster---------------------------------------
      $("#txtTipo").change(function(){//adicion ftth
        var variable5=$("#txtTipo").val();//valor del campo tipo proyecto
        var variable6=$("#txtidcluster").val();//valor del campo departamento
        $.ajax({
          type:'POST',
          url:'/includes/database.php',//ruta donde se ejecutan las funciones ligadas a los formularios
          data: {variable5:variable5,variable6:variable6},
          success:function(response){
            $("#txtidsubcluster").html(response);
          },
          error:function(xhr,status){
            alert("Disculpe, existio un problema");
          }
        });
      });
//----------asignacion de departamento para escoger cluster---------------------------------------
      $("#txtLocalidad").change(function () {//si cambia el valor en el select se activa
          var variable1=$("#txtLocalidad").val();//recoge el actual valor del select

          $.ajax({
          type: 'POST',
          url: '/includes/database.php',//ruta donde se ejecutan las funciones ligadas a los formularios
          data: {variable1: variable1},
          success : function(response) {
            $("#txtidcluster").html(response);//envia el valor de variable recogido del select depto (se ejecuta solo en la funcion getComboBoxPrueba())CHR
          },
          error : function(xhr, status) {
            alert('Disculpe, existió un problema a cluster');
          }});

      });
//--------asignacion de cluster para escoger subcluster-----------------------------------------
      $("#txtidcluster").change(function(){
        var variable2=$("#txtidcluster").val();
        //alert(variable2);
        $.ajax({
          type:'POST',
          url:'/includes/database.php',//ruta donde se ejecutan las funciones ligadas a los formularios
          data: {variable2:variable2},
          success:function(response){
            $("#txtidsubcluster").html(response);
          },
          error:function(xhr,status){
            alert("Disculpe, existio un problema");
          }
        });
      });

//-------------------activacion y desactivacion de boton liquidar------------------
$("#activarliq").click(function(){
  //alert("boton 1");
  var variable22=$("#activarliq").val();//valor del campo boton activarliq
//alert(variable22);
      //alert("boton 2");
      var jsonm =variable22;
      var url='includes/controller.mliq.php';
      //alert("boton 4");
      var param=jsonm;
      //alert("boton 5");
      console.log(param);
      var ejecucion=function(datos){
        //alert("boton 6");
        console.log(datos);
        var data= JSON.parse(datos);
        console.log(data);
        if(data.result=='SI'){
          alert("boton activado");
      }else{
        alert("boton desactivado");
         }
      }
      fajax(url,param,ejecucion);
});

//----------------------activacion de numero cto viabilidades segun campo proyecto-----------------
function CtoOn(){
  $("#txtnumerocto").prop('disabled',false);
};
function CtoOff(){
  $("#txtnumerocto").prop('disabled',true);
  $("#txtnumerocto").html(0);
};
$("#txtProyecto").change(function(){
  var v=$("#txtProyecto").val();
  if((v=='35') || (v=='36')){
    CtoOn();
  }else {
    CtoOff();
  }
});
//-----------------------------------------------------------
//--------------------se activa para cambio de EECC de una OT---------------
$("#cambiaeecc").click(function(){
  var ot=$("#cambiaeecc").val();
  var ec=$("#eccotb").val();
  $.ajax({
    type:'POST',
    url:'/includes/database.php',//ruta donde se ejecutan las funciones ligadas a los formularios
    data: {oteecc:ot,ideecc:ec},
    success:function(response){
      console.log(response);
      alert("EECC de la OT Actualizado");
      document.location.reload();
    },
    error:function(xhr,status){
      alert("Disculpe, existio un problema");
      document.location.reload();
    }
  });

});

//----------------------------------------------------------------------------


//---------------------modificacion Agregar bandejasOT---------------------
$("#adicionarbto").click(function(){
  var botid=$("#adicionarbto").val();
  var bot=$("#gruposbto").val();
  $.ajax({
    type:'POST',
    url:'/includes/database.php',//ruta donde se ejecutan las funciones ligadas a los formularios
    data: {bot:bot,botid:botid},
    success:function(response){
      console.log(response);
      alert(response);
      document.location.reload();
    },
    error:function(xhr,status){
      alert("Disculpe, existio un problema");
      document.location.reload();
    }
  });
});
//-----------------------------------------------------------------

//---------------------modificacion Eliminar bandejasOT---------------------
$(".eliminarbot").click(function(){
  var otid=$("#eliminarbot").val();
  var gpot=$("#gpbto").val();
  $.ajax({
    type:'POST',
    url:'/includes/database.php',//ruta donde se ejecutan las funciones ligadas a los formularios
    data: {otid:otid,gpot:gpot},
    success:function(response){
      console.log(response);
      alert(response);
      document.location.reload();
    },
    error:function(xhr,status){
      alert("Disculpe, existio un problema");
      document.location.reload();
    }
  });
});
//-----------------------------------------------------------------

//---------------------modificacion Agregar bandejasliq---------------------
$("#adicionarbliq").click(function(){
  var bliqid=$("#adicionarbliq").val();
  var bliq=$("#gruposbliq").val();
  $.ajax({
    type:'POST',
    url:'/includes/database.php',//ruta donde se ejecutan las funciones ligadas a los formularios
    data: {bliq:bliq,bliqid:bliqid},
    success:function(response){
      console.log(response);
      alert(response);
      document.location.reload();
    },
    error:function(xhr,status){
      alert("Disculpe, existio un problema");
      document.location.reload();
    }
  });
});
//-----------------------------------------------------------------


//---------------------modificacion Eliminar bandejasliq---------------------
$(".eliminarliq").click(function(){
  var liqid=$("#eliminarliq").val();
  var gpliq=$("#gpliq").val();
  $.ajax({
    type:'POST',
    url:'/includes/database.php',//ruta donde se ejecutan las funciones ligadas a los formularios
    data: {liqid:liqid,gpliq:gpliq},
    success:function(response){
      console.log(response);
      alert(response);
      document.location.reload();
    },
    error:function(xhr,status){
      alert("Disculpe, existio un problema");
      document.location.reload();
    }
  });
});
//-----------------------------------------------------------------



//---------------------modificacion Agregar bandejasVB---------------------
$("#adicionarbvb").click(function(){
  var bvbid=$("#adicionarbvb").val();
  var bvb=$("#gruposbvb").val();
  $.ajax({
    type:'POST',
    url:'/includes/database.php',//ruta donde se ejecutan las funciones ligadas a los formularios
    data: {bvb:bvb,bvbid:bvbid},
    success:function(response){
      console.log(response);
      alert(response);
      document.location.reload();
    },
    error:function(xhr,status){
      alert("Disculpe, existio un problema");
      document.location.reload();
    }
  });
});
//-----------------------------------------------------------------

//---------------------modificacion Eliminar bandejasVB---------------------
$(".eliminarbvb").click(function(){
  var vbid=$("#eliminarbvb").val();
  var gpvb=$("#gpbvb").val();

  $.ajax({
    type:'POST',
    url:'/includes/database.php',//ruta donde se ejecutan las funciones ligadas a los formularios
    data: {vbid:vbid,gpvb:gpvb},
    success:function(response){
      console.log(response);
      alert(response);
      document.location.reload();
    },
    error:function(xhr,status){
      alert("Disculpe, existio un problema");
      document.location.reload();
    }
  });
});
//-----------------------------------------------------------------


});
//-------------------------------------------------



//--------asignacion de tipo proyecto (segun ftth) a MES-----------------------------------------
      $("#txtTipo").change(function(){//adicion ftth
        var variable7=$("#txtTipo").val();//valor del campo tipo proyecto
//alert(variable7);
        $.ajax({
          type:'POST',
          url:'/includes/database.php',//ruta donde se ejecutan las funciones ligadas a los formularios
          data: {variable7:variable7},
          success:function(response){
            $("#txtidmes").html(response);
          },
          error:function(xhr,status){
            alert("Disculpe, existio un problema");
          }
        });
      });