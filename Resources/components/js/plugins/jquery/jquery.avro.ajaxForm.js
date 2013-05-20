(function($) {
    jQuery.fn.avroAjaxForm = function(options) {
        var $form = this;
console.log($form);
exit;
        var defaults = {
            dataType: 'json',
            beforeSubmit: function(jqXHR, settings) {

                var validateDefaults = {
                    ignoreTitle: true,
                    ignore: [],
                    onclick: false,
                    onkeyup: false,
                    unhighlight: function (element, errorClass) {
                        $(element).removeClass('error');
                        if ($(element).is('select')) {
                            $('#' + $(element).attr('id') + '_chzn a.chzn-single').removeClass('error');
                        }
                    },
                    errorPlacement: function(error, element) {
                        return false;
                    },
                    highlight: function (element, errorClass) {
                        $element = $(element);
                        if ($element.is('select')) {
                            $('#' + $element.attr('id') + '_chzn a.chzn-single').addClass('error');
                        }
                        $element.addClass('error');

                        $modal = $element.parents('.modal');
                        if ($modal.length) {
                            $modal.find('.active').removeClass('active');
                            var index = $element.parents('.tab-pane').index();
                            $modal.find('.tab-pane').eq(index).addClass('active');
                            $modal.find('.nav-tabs li').eq(index).addClass('active');
                        }
                        if (!$('#alertContainer .alert-notice').length) {
                            avro.createError('You missed a required field.');
                        }
                    },
                };

                $form.validate(defaults);

                if ($form.valid()) {
                    avro.showSpinner();
                } else {
                    return false;
                }
            },
            complete: function(jqXHR, textStatus) {
                avro.hideSpinner();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if(textStatus === 'timeout') {
                    $form.ajaxForm(this); // retry
                } else {
                    avro.createError(errorThrown);
                }
            },
        };
        options = $.extend(defaults, options);
        //$form.refreshCollection()
        //$form.ajaxForm(options);
    }
})(jQuery);
