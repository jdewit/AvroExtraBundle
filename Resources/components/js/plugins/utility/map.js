;(function(avro, $, undefined) {
    // creates a flash alert
    avro.getMap = function(lat, lng) {
        var point = new google.maps.LatLng(lat, lng);
        var options = {
            zoom: 14,
            center: point,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        return new google.maps.Map($('#mapCanvas')[0], options);
    };
//    avro.updateMap = function() {
//
//    };

}(window.avro = window.avro || {}, jQuery)); 
