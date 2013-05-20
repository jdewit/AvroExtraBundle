;(function(avro, $, undefined) {
    avro.getCoordinates = function(callback) {
        var lat = avro.getCookie('local_food_network_lat');
        var lng = avro.getCookie('local_food_network_lng');

        if (lat == undefined || lng == undefined) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) { 
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        avro.setCookie('local_food_network_lat', lat, 7);
                        avro.setCookie('local_food_network_lng', lng, 7);

                        callback(lat, lng);
                    },
                    function() {
                        callback(59, -123);
                    }
                );
            } else {
                callback(59, -123);
            }
        } else {
            callback(lat, lng);
        }
    }
}(window.avro = window.avro || {}, jQuery)); 


