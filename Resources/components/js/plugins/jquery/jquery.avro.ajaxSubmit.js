(function($) {
    jQuery.fn.avroAjaxSubmit = function(options) {
        var $form = this,
            $button = $form.find('button:submit');
        var defaults = {
            type: 'post',
            dataType: 'json',
            beforeSubmit: function(jqXHR, settings) {
                if ($form.valid()) {
                    avro.showSpinner();
                    $button.button('loading');
                } else {
                    return false;
                }
            },
            complete: function(jqXHR, textStatus) {
                avro.hideSpinner();
                setTimeout(function() {
                    $button.button('reset');
                }, 2000);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if(textStatus === 'timeout') {
                    $form.ajaxSubmit(this); // retry
                } else {
                    avro.createError(errorThrown);
                }
            },
        };
        options = $.extend(defaults, options);

        $form.refreshCollection()
        $form.ajaxSubmit(options);
    }
})(jQuery);
