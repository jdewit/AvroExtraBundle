;(function(avro, $, undefined) {
    avro.createNotice = function(text) {
        $('div.alert').remove();
        $('html, body').animate({ scrollTop: 0 }, 'slow');
        $('#alertContainer').prepend('<div class="alert alert-notice fade in"><a class="close" data-dismiss="alert" href="#">&times;</a>'+ text +'</div>');

        $('html').one('click', function() {
            $('#alertContainer .alert').remove();
        });
    };

    avro.createError = function(text) {
        $('div.alert').remove();
        $('html, body').animate({ scrollTop: 0 }, 'slow');
        $('#alertContainer').prepend('<div class="alert alert-error fade in"><a class="close" data-dismiss="alert" href="#">&times;</a>'+ text +'</div>');

        $('html').one('click', function() {
            $('#alertContainer .alert').remove();
        });
    };

    avro.createSuccess = function(text) {
        $('div.alert').remove();
        $('html, body').animate({ scrollTop: 0 }, 'slow');
        $('#alertContainer').prepend('<div class="alert alert-success fade in"><a class="close" data-dismiss="alert" href="#">&times;</a>'+ text +'</div>');

        $('html').one('click', function() {
            $('#alertContainer .alert').remove();
        });
    };

}(window.avro = window.avro || {}, jQuery)); 
