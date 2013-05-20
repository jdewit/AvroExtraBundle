(function(avro, $, undefined) {
    avro.showSpinner = function() {
        $('#spinner').show();
    };
     
    avro.hideSpinner = function() {
        $('#spinner').hide();
    };
}(window.avro = window.avro || {}, jQuery)); 
