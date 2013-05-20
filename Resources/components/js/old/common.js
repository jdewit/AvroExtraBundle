$(document).ready(function () {    

    //ajax link
    $("a.loadHtml").livequery(function() {
        $(this).click(function(event) {
           event.preventDefault();
           var get = $(this).data('get') ? '?'+$(this).data('get') : '';
           showSpinner();
            $.ajax({
                dataType: 'html',
                url: this.href + get,
                success: function(data){
                    hideSpinner()
                    $('body').append(data);
                    reloadJS();
                }
            });
        });
    });

    $("a.ajaxLink").livequery(function() {
        var link = $(this);
        link.click(function(event) {
            event.preventDefault();
            $.ajax({
                dataType: 'json',
                url: this.href,
                success: function(notice){
                    createSuccessNotice(notice);
                    link.parents('.ui-dialog-content').dialog('close');
                }
            });
        });
    });


});
