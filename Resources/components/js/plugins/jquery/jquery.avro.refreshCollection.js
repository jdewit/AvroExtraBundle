(function($) {
    jQuery.fn.refreshCollection = function()
    {
//        console.log('refresh collection');
        return this.each(function() {
            $(this).find('.collection-container:not(.collection-container .collection-container)').each(function() {
            var itemIndex = 0;
                // first rename all collections
                $(this).find('.collection-item:not(.collection-item .collection-item)').each(function() {
                    var childIndex = 0;
                    $(this).find('.collection-container .collection-item').each(function() {
                        $(this).find('input, select, textarea').not('.chzn-search input').each(function() {
                             $(this).attr('id', $(this).attr('id').replace(/__name__/, itemIndex));
                             $(this).attr('name', $(this).attr('name').replace(/__name__/, itemIndex));
                        });

                        $(this).find('input, select, textarea').not('.chzn-search input').each(function() {
                             $(this).attr('id', $(this).attr('id').replace(/__name__/, childIndex));
                             $(this).attr('name', $(this).attr('name').replace(/__name__/, childIndex));
                        });

                        childIndex++;
                    });

                    $(this).find('input, select, textarea').not('.chzn-search input').each(function() {
                        if ($(this).attr('id') && $(this).attr('name')) {
                            $(this).attr('id', $(this).attr('id').replace(/__name__/g, itemIndex));
                            $(this).attr('name', $(this).attr('name').replace(/__name__/g, itemIndex));
                        }
                    });
                    itemIndex++;

                });
            });
        });
    }
})(jQuery);


