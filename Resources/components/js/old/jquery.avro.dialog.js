(function($) {
    jQuery.fn.avroDialog = function(options) {
        var $target = this;

        var defaults = {
            autoOpen: true,
            width: '50%',
            resizable: false,
            position: ['center', 30],
            show: 'drop',
            hide: 'drop',
            stack: true,
            modal: true,
            create: function(event, ui){
                //ui-tabs fix
                if($target.find('.ui-tabs').length) {
                    $('.ui-tabs a.dialog-close').hover(
                        function () {
                            $(this).addClass('ui-state-hover');
                        }, 
                        function () {
                            $(this).removeClass('ui-state-hover');
                        }
                    ).click(function(event) {
                        $target.dialog('close');
                    });
                }

            },
            open: function(event, ui){
                setTimeout(function() {
                    $target.find('.hasDatepicker').datepicker('enable'); 
                }, 0);
                $target.find('input:focus').blur();
                if (!$target.find('div.form-actions').length) {
                    $target.append('<div class="form-actions"><a href="#" class="ui-btn ui-btn-cancel">Close</a></div>');
                }
                $target.dialog('moveToTop');
                //$(this).css('overflow','hidden');
                //$('.ui-widget-overlay').css('width','100%'); 
                avro.hideSpinner();
            },
            close: function(event, ui){
                $target.find('input.hasDatepicker').datepicker('hide').datepicker('disable');
                $tabs = $target.find('.ui-tabs');
                if ($tabs.length) {
                    $tabs.tabs('select', 0);
                }
                if (options.destroy) {
                    $target.dialog("destroy").remove();
                } 
            }  
        };

        options = $.extend(defaults, options);

        $target.dialog(options);
    }
    
})(jQuery);
