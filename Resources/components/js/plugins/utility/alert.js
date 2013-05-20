;(function(avro, $, undefined) {
    // creates a flash alert
    avro.createAlerts = function(alerts) {
        $alertContainer = $('#alertContainer');
        $('html, body').animate({ scrollTop: 0 }, 'slow');

        $alertContainer.find('.alert').remove();
        $.each(alerts, function(type, messages) {
            $.each(messages, function(key, message) {
                $alertContainer.prepend('<div class="alert alert-'+ type +' fade in"><a class="close" data-dismiss="alert" href="#">&times;</a>'+ message +'</div>');
            });
        });
        
        $alertContainer.slideDown('slow');
        setTimeout(function() {
            $alertContainer.slideUp('slow');
        }, 8000);
    };

}(window.avro = window.avro || {}, jQuery)); 
