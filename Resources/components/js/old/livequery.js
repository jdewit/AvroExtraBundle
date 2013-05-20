$(document).ready(function () {    

//    //livequery setters
//    $('.accordion').livequery(function() {
//        var $container = $(this),
//            index,
//            newUrl;
//
//        // works with knp_menu_accordion
//        // index is generated by menu on each div
//        index = $container.find('li.current').closest('div').data('index');
//        if (!index) {
//            index = $container.find('h3.current').data('index');
//        }
//        $container.accordion({
//            autoHeight: false,
//            active: index,
//            change: function(event, ui) {
//                if (ui.newHeader.hasClass('empty-panel')) {
//                    newUrl = $(ui.newHeader).find('a').attr('href');
//
//                    if (newUrl !== document.URL) {
//                        window.location.href = newUrl;
//                    }
//                }
//            }
//        });
//    });


//    $('.ui-btn, .form-actions :submit').livequery(function() {
//        $(this).button();
//    });
//
//    $('.ui-dialog .ui-btn-cancel, .ui-dialog ui-btn-delete').livequery(function() {
//        var $link = $(this);
//        $link.click(function() {
//            $link.closest('.ui-dialog-content').dialog('close');
//        });
//    });


$('input#selectAll').click(function() {
    var checkedStatus = this.checked;
    $('input.selected').prop('checked', checkedStatus);
});
    

    $('select.chosen').livequery(function() {
        var randomNum = Math.ceil(Math.random()*100);
        var oldId = $(this).attr('id');
        var newId = oldId + '_' + randomNum;
        $(this).attr('id', newId);
        $(this).parents('.control-group').find('label').attr('for', newId);
        $(this).chosen({
            disable_search_threshold: 10
        });
    });

//    $('input.datepicker').livequery(function() {
//        $(this).datepicker({
//            dateFormat: 'yy-mm-dd'
//        }).parent().append('<i class="sprite-calendar"></i>');
//    });

    $('form.refresh-collections').livequery(function() {
        $(this).submit(function(event) {
            $(this).refreshCollection();
        });
    });

    $(".confirm").livequery(function() {
        $(this).click(function() {
            var text = this.title;
            return confirm(text);
        });
    });

    $("div.alert").livequery(function() {
        $(this).delay(20000).fadeOut(500, function() { $(this).remove(); });

        $('a.close').click(function() {
            $(this).parent().remove();
        });
    });

});