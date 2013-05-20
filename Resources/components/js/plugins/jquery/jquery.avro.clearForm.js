(function($) {
    jQuery.fn.clearForm = function() {
        return this.each(function() {
            $form = $(this);
            $form.find('input[type="text"], input[type="password"], textarea').val('');
            $form.find('input[type="checkbox"], input[type="radio"]').attr('checked', false);
            $form.find('select option:first').attr('selected', 'selected').change();
        });
    };
})(jQuery);
