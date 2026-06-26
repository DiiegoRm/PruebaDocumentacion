$(function() {

    let txtFiles = $("#files");
    let message = $('.error');
    let result = $(".result");
    message.css("display", "none");

    //validation functions
    function validatetxtFiles(){
      //if it's NOT valid
      if(txtFiles.val().length === 0) {
        message.css("display", "block");
        txtFiles.addClass('error');
        return false;
      }
      else{
        message.css("display", "none");
        txtFiles.removeClass('error');
        return true;
      }
    }

    //
    function formUploadAjax(handleData) {
        var f = $("#formUploadAjax");
        var formData = new FormData(document.getElementById("formUploadAjax"));
        formData.append(f.attr("name"), $('#formUploadAjax')[0].files[0]);
        $.ajax({
            url: "callback/usuarios.inc.php?mode=import",
            type: "post",
            dataType: "html",
            async: false,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
              handleData(response);
            }
        }); 
    }

    //
    var progressTimer,
      progressbar = $("#progressbar"),
      progressLabel = $(".progress-label"),
      dialogButtons = [{
        text: "Cancelar",
        click: closeDownload
      }],
      dialog = $("#dialog").dialog({
        autoOpen: false,
        closeOnEscape: false,
        resizable: false,
        modal: true,
        buttons: dialogButtons,
        open: function() {
          progressTimer = setTimeout( progress, 2000 );
        },
        beforeClose: function() {
          location.reload();
        }
      }),
      downloadButton = $("#downloadButton").button()
        .on("click", function() {
          if(validatetxtFiles()) {
              // Upload File
              formUploadAjax(function(output) {
                if(output == 'Ok') {  
                  dialog.dialog("open");
                  message.css("display", "none");
                  txtFiles.removeClass('error');
                } else {
                  message.html(output);
                  message.css("display", "block");
                  txtFiles.addClass('error');                  
                }
              });
              return false;

            } else {
              return false;
            }
      });
 
      progressbar.progressbar({
      value: false,
      change: function() {
        progressLabel.text("Progreso: " + progressbar.progressbar("value") + "%");
      },
      complete: function() {
        formProcessAjax();
        progressLabel.text("Importaci\u00F3n ejecutada correctamente!");
        dialog.dialog("option", "buttons", [{
          text: "Cerrar",
          click: closeDownload
        }]);
        $(".ui-dialog button").last().trigger("focus");
      }
    });
 
    function progress() {
      var val = progressbar.progressbar("value") || 0;
      progressbar.progressbar("value", val + Math.floor( Math.random() * 3 ));
      if ( val <= 99 ) {
        progressTimer = setTimeout( progress, 50);
      }
    }
    
    //
    function closeDownload() {
      location.reload();
    }

    //
    function formProcessAjax() {
      $.ajax({
          url: "callback/usuarios.inc.php?mode=process",
          type: "post",
          dataType: "html",
          async: false,
          cache: false,
          contentType: false,
          processData: false,
          success: function(response) {
            
            let result = JSON.parse(response);
            let html = "<br/><span style='color: green;'>Cantidad Correctos: " + result.success.length + "</span>";
              html += "<br/><span style='color: red;'>Cantidad Errores: " + result.errors.length + "</span>";
            if ((result.success.length > 0) || (result.errors.length > 0)) {  
              html += '<br/>Log: <a href="includes/descarga.inc.php?document=imp_usuarios.log&ruta=/data/files/log/&name=imp_usuarios.log">Click Aqui!</a>';
            } else {
              html += "<br/><span style='color: red;'>El documento se encuentra vacio.</span>";
            }
            $('#json').html(html);
          }
      }); 
  }
});