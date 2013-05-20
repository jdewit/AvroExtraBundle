$(document).ready(function () {    
    $(".dialog").livequery(function() {
        createDialog($(this), this.title, $(this).data('open'), $(this).data('width'));
    });

    $(".dialogClosed").livequery(function() {
        createDialog($(this), this.title, false);
    });

    // open target in a dialog
    $("a.dialogButtonAndShow").livequery(function() {
        $(this).click(function(event) {
            event.preventDefault();
            createDialogAndShow($($(this).data('target')), $(this).data('title'), $($(this).data('show')), true);
        });
    });
    // open target in a dialog
    $(".createDialog").livequery(function() {
        $(this).click(function(event) {
            event.preventDefault();
            createDialog($($(this).data('target')), $(this).data('title'), true, $(this).data('width'));
        });
    });

    $('a.openDialog').click(function(event) {
        event.preventDefault();
        $($(this).data('target')).dialog("open");
    });

    $('a.openDialogAndShow').click(function(event) {
        event.preventDefault();
        $($(this).data('target')).dialog("open");
        $($(this).data('show')).show();
    });

    $("#dialog form.ajax").each(function() {
        $(this).ajaxForm({
            dataType:  'html',
            success:   function(data){
                $('body').prepend('<div class="alert-notice info">'+data+'</div>');
                $('#dialog').dialog('close');
            },
            resetForm: false
        });
    });

    //ajax link
    $("a.getAjaxInDialog").livequery(function() {
        $(this).click(function(event) {
           event.preventDefault();
           showSpinner();
            $.ajax({
                dataType: 'html',
                url: this.href +'?'+ $(this).data('get'),
                success: function(data){
                    hideSpinner()
                    $('body').append('<div class="dialog">'+ data +'</div>');
                }
            });
        });
    });
    

});
