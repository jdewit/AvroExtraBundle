(function(avro, $, undefined) {
    avro.throttle = function (fn, delay) {
        var timer = null;
        return function () {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                fn.apply(context, args);
            }, delay);
        };
    }
}(window.avro = window.avro || {}, jQuery)); 

