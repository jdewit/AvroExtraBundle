;(function(avro, $, undefined) {
    avro.ajax = function(options) {

        var defaults = {
            type: 'post',
            dataType: 'json',
        };
        options = $.extend(defaults, options);

        // add to ajax manager
        return avro.ajaxManager.add(options);
    }
}(window.avro = window.avro || {}, jQuery)); 
