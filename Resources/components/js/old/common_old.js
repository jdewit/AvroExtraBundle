var myLayout;

$(document).ready(function () {    

        $('div.prototype').find('input, select').each(function() {
            $(this).attr('id', $(this).attr('id').replace(/\$\$name\$\$/g, 0));
            $(this).attr('name', $(this).attr('name').replace(/\$\$name\$\$/g, 0));
        });

    // apply jquery ui styling
    $(".ui-btn").ui-btn();
    
    myLayout = $("body").layout({       
        useStateCookie: true,
        applyDefaultStyles: false,
        defaults: {
            spacing_open: 6,
            resizable: false
        },
        north: {
            closable: false,
            spacing_open: 0,
            size: "auto"
        },
        south: {
            size: 100,
            spacing_open: 0
        },
        west: {
            size: 250,
            spacing_closed: 22,
            togglerLength_closed: 140,
            togglerAlign_closed: "top",
            togglerContent_closed: "M<BR>e<BR>n<BR>u",
            togglerTip_closed: "Open Menu",
            sliderTip: "Slide Open Contents"
        },
        cookie: {
        //  State Management options
            name: "" // If not specified, will use Layout.name
        ,   autoSave: true // Save cookie when page exits?
        ,   autoLoad: true // Load cookie when Layout inits?
        //  Cookie Options
        ,   domain: "localhost"
        ,   path: "/crm.applicationapps.com/web/app_dev.php/"
        ,   expires: 30 
        ,   secure: false
        //  State to save in the cookie - must be pane-specific
        ,   keys: "north.isClosed,south.isClosed,east.isClosed,west.isClosed,"+

    "north.isHidden,south.isHidden,east.isHidden,west.isHidden"
        }
    }); 
    
    myLayout.allowOverflow('north');
    
    reloadJS();




    $('.currency').formatCurrency();

    $('.tabs').tabs();
    $("select").chosen({
        disable_search_threshold: 10
    });

    $('.tab a.nav').click(function(){
       var form = $(this).parents('form');
       var good = true;
       $(this).parents('.tab').find('input').each(function() {
           var valid = form.validate().element( $(this) );
           if (valid == false) {
               good = false;
           }
       });   
       if (good == true) {
          $(this).parents('.tabs').find('a[href$="'+$(this).data('target')+'"]').click(); 
       }
       
       return false;
    });

//    $(".formWizard").formwizard({ 
//            formPluginEnabled: true,
//            historyEnabled : true,
//            //validationEnabled: true,
//            focusFirstInput : true,
//            formOptions :{
//                    success: function(data){
//
//                    },
//                    beforeSubmit: function(data){
//
//                    },
//                    dataType: 'json',
//                    resetForm: true
//            }	
//     });

    $('#selectivePaginator a').live('click', function(event) {
        event.preventDefault();
        if ($(this).hasClass('prev')) {
            var currentLink = $('#selectivePaginator a.current');
            var currentLinkIndex = currentLink.parent().index();
            if (currentLinkIndex != '1') {
                currentLink.parent().prev().children('a').click();
            }

        } else if ($(this).hasClass('next')) {
            var lastLinkIndex = $(this).parent().prev().index();
            var currentLink = $('#selectivePaginator a.current');
            var currentLinkIndex = currentLink.parent().index();
            if (currentLinkIndex != lastLinkIndex) {
                currentLink.parent().next().children('a').click();
            }
        } else {
            $('#selectivePaginator a.current').removeClass('current');
            $(this).addClass('current');
            var target = this.target;
            $('#quiz .page').hide();
            $(target).show();
        }

    });




    $('a.hideOnClick').live('click', function(event) {
        event.preventDefault();
        $(this).hide();
    });

    $('a.showOrHideToggle').live('click', function(event) {
        event.preventDefault();
        var target = this.target;
        //alert(target);
        if ($(this).hasClass('hideOnClickOutside')) {
            $(target).slideToggle("showOrHide").parent().bind( "clickoutside", function(event){
                $(target).hide();
            });
        } else {
            $(target).slideToggle("showOrHide");
        }
    });

//var url = document.domain;
//// IF THERE, REMOVE WHITE SPACE FROM BOTH ENDS
//url = url.replace(new RegExp(/^\s+/),""); // START
//url = url.replace(new RegExp(/\s+$/),""); // END
// 
//// IF FOUND, CONVERT BACK SLASHES TO FORWARD SLASHES
//url = url.replace(new RegExp(/\\/g),"/");
// 
//// IF THERE, REMOVES 'http://', 'https://' or 'ftp://' FROM THE START
//url = url.replace(new RegExp(/^http\:\/\/|^https\:\/\/|^ftp\:\/\//i),"");
// 
//// IF THERE, REMOVES 'www.' FROM THE START OF THE STRING
//url = url.replace(new RegExp(/^www\./i),"");
// 
//// REMOVE COMPLETE STRING FROM FIRST FORWARD . ON
//var subdomain = url.replace(new RegExp(/\.(.*)/),"");
//
//$('#global a.'+ subdomain).addClass('current');
//
////    //navigation
////    var path = location.pathname.substring(1);
////    if ( url ) {
////        var controller = path.split("/",4);
////        alert(controller[0]);
////        $('#headerNav a[href*="'+controller[3]+'"]').parent().addClass('current ui-state-hover');
////        //$('#headerSubNav a[href$="' + path + '"]').attr('class', 'current');
////    }
//
//    //tablesorter
//    $(".tableSorter").tableSorter();

    //declare loose variables
    var lastAjax;

    $('#nav li.current.ui-state-default').removeClass('ui-state-default').addClass('ui-state-active');
    $('a.ui-state-default').live({
        mouseenter:
           function()
           {
            $(this).addClass('ui-state-hover');
           },
        mouseleave:
           function()
           {
            $(this).removeClass('ui-state-hover');
           }
       });

    

    //centerCol ajax link
    $("#headerSubNav ul li a").click(function(){
        if (this.href == '') {
            return false;
        }
        if ($(this).attr('class') != 'current') {
           $(this).parents('ul').find('a.current').removeClass('current');
           $(this).addClass('current');
           showSpinner();
            $.ajax({
                dataType: 'html',
                url: this.href,
                success: function(data){
                    hideSpinner()
                    $('#centerCol').html(data);
                    reloadJS();
                }
            });
        }
        return false;
    });

    //centerCol ajax link
    $(".ajaxLink").live('click', function(event){
           event.preventDefault();
           showSpinner();
            $.ajax({
                dataType: 'html',
                url: this.href,
                success: function(data){
                    hideSpinner()
                    $('#centerColContent').append(data);
                    reloadJS();
                }
            });
            return false;
    });

    

    $(".ajaxAddTabButton").live('click', function(event){
           event.preventDefault();
           showSpinner();
           var block = this.target;
            $.ajax({
                dataType: 'html',
                url: this.href,
                success: function(data){
                    hideSpinner();
                    $(block).html(data);
                    $(block).find('a.tab:last-child').click();
                    reloadJS();
                }
            });
            return false;
    });

//dynamically change the target
//$('.changeTarget').live('change', function() {
//    var oldTarget = $(this).parents('form').attr('target');
//    var target = $(this).attr('target') + $(this).val();
//    alert(target);
//    var form = $(this).parents('form');
//    var append = form.hasClass('append');
//    form.ajaxForm({
//        success:   function(data){
//            hideSpinner();
//            destroyDialog();
//            $(oldTarget).remove();
//            if (append) {
//                $(target).append(data);
//            } else {
//                $(target).append(data);
//            }
//        }
//    });
//});

$( "#sortable" ).sortable({ handle: '.moveHandle'});
$( "#sortable" ).disableSelection();

$( "#draggable" ).draggable({
        axis: "y",
        connectToSortable: "#sortable",
        helper: "clone",
        revert: "invalid"
});
$( "ul, li" ).disableSelection();

// form helpers ----------------------------------------------------------------

    //capitalize inputs


    //disable dialog save ui-btn on submit
    function disableSaveButton(formId) {
       var ui-btns = $(formId+':ui-btn');
       for ( var i = 0; i < ui-btns.length; ++i )
          {
             var jButton = $( ui-btns[i] );
             if ( jButton.text() == 'Save' )
             {
                 jButton.attr('disabled', 'disabled' ).addClass( 'ui-state-disabled' );
             }
          }
          return null;
    }



    function destroyDialog()
    {
        $(".formDialog").dialog("destroy").remove();
    }

//    //cycle tabs
//    $.fn.cycle.updateActivePagerLink = function(pager, currSlideIndex) {
//        $(pager).find('li').removeClass('active')
//            .filter('li:eq('+currSlideIndex+')').addClass('active');
//    };
//
//    $('#side_tabs .content').cycle({
//        timeout: 10000,
//        speed: 150,
//        pause: 1,
//        pager:  '#side_tabs ul',
//        pagerAnchorBuilder: function(idx, slide) {
//            return '<li><a href="#">' + slide.target + '</a></li>';
//        }
//    });

    $('.click').find('.addCollectionItem').click();

    // error notice helper
    $("#priority_notices").hide().fadeIn(2000).delay(4000).fadeOut(4000);

//    $('#slides').slides({
//        play: 5000,
//        pause: 2500,
//        hoverPause: true
//    });

    $('.uniqueRadio').live('click', function() {
        if ($(this).parents('table').find('.uniqueRadio:checked').length > 1) {
            $(this).parents('table').find('.uniqueRadio:checked').removeAttr("checked");
            $(this).attr('checked', true);
        }
    })

//    var instance = false;
//    $('.questionListItemSelect').live('click', function() {
//        if (instance == false) {
//            var options = '<option value="">None</option>';
//            $('tr.questionListOptions.option').each(function() {
//                options += '<option value="' + $(this).find('.questionListOptionText').val() + '">' + $(this).find('.questionListOptionText').val() + '</option>'
//            })
//            $(this).html(options);
//            $('.questionListItems.template').find('.questionListItemSelect').html(options);
//            instance = true;
//        } else {
//            instance = false;
//        }
//    })


    function reloadJS(){
//        $( '#side_tabs ul li a' ).click(function(){
//            $('#side_tabs ul li a.active').removeClass('active');
//            $('#side_tabs #content .active').removeClass('active');
//            $(this).addClass('active');
//            $('#'+$(this).parent().attr("class")).addClass('active');
//            return false;
//        });

//    var $$ = $("tr.template");
//    $$.find("input, select, textarea").attr('disabled', true);
//    $$.hide();
//
////    /*
////    $$.find("div").each(function(i, item) {
////        // console.log(i);
////        // console.log(item);
////
////        var k = i + 1;
////        k = "numero";
////        console.log(k);
////
////        var pattern = /\$\$name\$\$/g;
////
////        var tmp = $(item).find("label");
////        tmp.attr("for", tmp.attr("for").replace(pattern, k));
////
////        var tmp = $(item).find("label");
////        tmp.attr("for", tmp.attr("for").replace(pattern, k));
////
////        var tmp = $(item).find("input, select");
////        tmp.attr("id", tmp.attr("id").replace(pattern, k));
////        tmp.attr("name", tmp.attr("name").replace(pattern, k));
////    });
////    */

//        $('.collectionItems').each(function(){
//            $(this).find('.prototype').find('input, select, textarea').attr('disabled', true);
//            
//        });
  
        
        $('.addCollectionItem').click(function(event) {
                event.preventDefault();

                //get target container
                var collectionItemsContainer = $( $(this).data('target') );
                //alert(formOptionsTable.attr('class'));
                var newRow = collectionItemsContainer.find('script[type="text/html"]').text();         

                //append and show new collection item to collection container
                collectionItemsContainer.append( newRow );  
                
                refreshCollection(collectionItemsContainer);

            });

            $("a.removeCollectionItem").live('click',function(event) {
                event.preventDefault();
                var collectionItemsContainer = $( $(this).data('target') );

                $(this).parents('.item').remove();

                refreshCollection(collectionItemsContainer);
            });

            function refreshCollection(collectionItemsContainer) {
                //rename collection inputs
                var index = 0;
                collectionItemsContainer.find('.item').each(function() {
//                    $(this).attr('label', $(this).attr('name').replace(/\$\$name\$\$/g, index));                    
                    $(this).find('input, select').each(function() {
                        $(this).attr('id', $(this).attr('id').replace(/\$\$name\$\$/g, index));
                        $(this).attr('name', $(this).attr('name').replace(/\$\$name\$\$/g, index));
                    });
                    index = index + 1;
                });                
            }

//        $('.addCollectionItem').each(function(){
//            $( this ).click();           
//        });

        $('table.formOptions').each(function(){

            $(this).find('tr.template').find('input, select, textarea').attr('disabled', true);

            $(this).find("a.addFormOption").click(function(event) {
                event.preventDefault();
                var formOptionsTable = $(this).closest('table');
                //alert(formOptionsTable.attr('class'));
                var newRow = formOptionsTable.find('tr.template').clone().removeClass('template').addClass('option'); 
                newRow.find('input, select, textarea').each(function() {
                    $(this).removeClass('template').removeAttr('disabled', true);
                });
                
                newRow.show();                
                
                formOptionsTable.children('tbody').append( newRow );



                var index = 0;
                formOptionsTable.find('tbody tr.option').each(function() {
//                    $(this).attr('label', $(this).attr('name').replace(/\$\$name\$\$/g, index));                    
                    $(this).find('input, select').each(function() {
                        $(this).attr('id', $(this).attr('id').replace(/\$\$name\$\$/g, index));
                        $(this).attr('name', $(this).attr('name').replace(/\$\$name\$\$/g, index));
                    });
                    index = index + 1;
                });

            });
            //alert($(this).find('tbody tr.option').length);
            if ($(this).find('tbody tr.option').length < 1) {
                //$(this).find('tfoot a.addFormOption').click();
            }

            $(this).find("a.removeFormOption").live('click',function(event) {
                event.preventDefault();
                formOptionsTable = $(this).parents('table');
                //if (formOptionsTable.find('tbody tr.option').length > 1) {
                    $(this).parents('tr.option').remove();

                    var index = 0;
                    formOptionsTable.find('tbody tr.option').each(function() {
                        var input = $(this).find('input');
                        oldIndex = input.attr("target");
                        $(this).find('input').each(function() {
                            $(this).attr('id', $(this).attr('id').replace(oldIndex, index));
                            $(this).attr('name', $(this).attr('name').replace(oldIndex, index));
                        });
                        index = index + 1;
                    });
               // }
                return false;
            });
        });

//        $('select.populator').live('change', function(){
//            var selectedOption = $(this).val();
//            var json = $(this).data('fields');
//            var formRow = $(this).parents('tr');
//            
//            var fieldsArray = json[selectedOption];
//            $.each(fieldsArray, function(k, v) {
//                var input = $(formRow).find('.'+k);
//                    input.val(v);
//            });
//        });

        $('.multi option:first-child').attr("selected", "selected");

        $('.multi').live('change', function(){
            $(this).find('option:selected').each(this, function(){
               alert(this).val(); 
            });
        });
//        $('input.tax').live('change', function(){
//           var id = $(this).val();
//           
//        });



        //calculate input values in a form with data-attribute
        //input data-calculate={ "type": "multiply", "target": "total" , "container": "tr", "fields": { "0": "qty", "1": "rate" } }
//        $('input.calculate').live('change', function(){
//           var json = $(this).data("calculate");
//           var container = $(this).parents(json.parent);
//           var target = json.target;
//           var fields = json.fields;
//
//           var values = [];
//           $.each(fields, function(k,v){
//               values.push( container.find('.'+v).val() );
//           });
//
//           if (json.type == 'multiply') {        
//               var total = array_product(values);               
//           } else if (json.type == "add") {
//               var total = array_sum(values);
//           } else {
//               return false;
//           }
//           
//           container.find('.'+target).val(total);           
//        });

//        $('multiplyFields').live();

//        //paginator
//        $('#paginator a.previous').live('click', function(event){
//            event.preventDefault();
//            var currentLink = $('#paginator a.current');
//
//
//            if (currentLink.prev().index() == $('#paginator a.page:first').index()) {
//                $('#paginator a.previous').hide();
//            }
//            if (currentLink.index() != $('#paginator a.page:first').index()) {
//                currentLink.prev().click();
//                $('#paginator a.next').show();
//            }
//        })
//        $('#paginator a.next').live('click', function(event){
//            event.preventDefault();
//            var currentLink = $('#paginator a.current');
//
//        })

        $('.dialogSwitch').click(function(event) {
            event.preventDefault();
            //alert(this.target);
            $(this.target).dialog("open");
        });

        $('.ui-btn-cancelOnClick a').click(function() { $(this).parents('.dialog').dialog("close"); });
        $('.destroyDialogOnClick').click(function() { $(this).dialog("destroy").remove(); });

        //dialog
        $(".dialog").dialog({
            autoOpen: false,
            minHeight: '400',
            minWidth:  $(".dialog").width(),
            position: 'center',
            show: 'slide',
            modal: true,
            ui-btns: {
                'Close': function() {
                    $(this).dialog("close");
                }
            }
        });

        //form dialog
        $(".formDialog").dialog({
            autoOpen: true,
            minHeight: '325',
            //width: 'auto',
            resizable: false,
            minWidth: $(".formDialog form").width(),
            position: 'center',
            modal: true,
            show: 'slide',
            hide: 'slide',
            open: function(event, ui){
                $(this).css('overflow','hidden');
                $('.ui-widget-overlay').css('width','100%'); 
            },
            close: function(event, ui){
                $(this).dialog("destroy").remove();
            },            
            ui-btns: {
                'Save': function() {
                   disableSaveButton( $(this) );
                   showSpinner();
                   $(".formDialog form").submit();
                },
                'Cancel': function() {
                    $(this).dialog("close");
                }
            }
        });

        $(".formDialog form").each(function() {
            var target = $(this).data('target');
            var append = $(this).hasClass('append');
            $(this).ajaxForm({
                dataType:  'html',
                success:   function(data){
                    hideSpinner();
                    destroyDialog();
                    if (append) {
                        $(target).append(data);
                    } else {
                        $(target).replaceWith(data);
                    }
                },
                resetForm: false
            });
        });
        
        $(".formDialog form.ajaxTabs").each(function() {
            var target = $(this).attr('target');
            $(this).ajaxForm({
                dataType:  'html',
                success:   function(data){
                   //tabsContainer = $('#tabs'+ target);
                   // alert(tabsContainer.attr('class'));
                    hideSpinner();
                    destroyDialog();
                    //alert(target);
                    //alert(data);
                    $(target).html(data);
                    $(target).find('a.tab.current').removeClass('current');
                    $(target).find('#tabsHeader').find('a.tab:last').click();
//                    alert(data);
//                    var a = $(data).find('a.tab').clone().wrap('<div></div>').parent().html()
//                    data = data.replace(a, "");
//                    alert(data);
//                    $(tabsContainer).find('a.current').removeClass('current');
//                    $(tabsContainer).find('.tabContent').hide();
//                    $(tabsContainer).find('#tabsHeader li.addTab').before('<li>'+a+'</li>');
                    //$(tabsContainer).find('#tabsContainer').append(data);
                },
                resetForm: false
            });
        });

        //tabs ui
        $("#tabs a.tab").live('click', function(event){
            event.preventDefault();
            var tabsContainer = $(this).parents("#tabs")[0];
            //console.log(tabsContainer);
            //alert(tabsContainer.attr('class'));
            $(tabsContainer).find("a.current").removeClass('current');
            //alert(this.target);
            //$(tabsContainer).find(this.target +".tabContent").closest('.tabsContainer').find('.current').removeClass('current');
            $(this).addClass('current');
            $(tabsContainer).find('.tabContent').hide();
            //alert(this.target);
            $(tabsContainer).find(this.target).find('.tab:first').addClass('current');
            $(tabsContainer).find(this.target).show().addClass('current').find('#tabs').show().find('.tabContent:first').show();
        });

        //input formatting
        var toggle = false;
        $(" form input[type=checkbox].toggleCheckbox ").click(function () {
            if (!toggle) {
                $(" form input[type=checkbox] ").attr('checked','checked');
                toggle = true;
            } else {
                $(" form input[type=checkbox] ").removeAttr('checked');
                toggle = false;
            }           
        });
        
//        $("input.uppercase").bestupper();
        
//        $("select").each(function() {
//            if (!$(this).hasClass('template') && !$(this).hasClass('multi')) {
//                //$(this).selectmenu({style:'dropdown'});
//                $(this).multiselect({
//                   multiple: false,
//                   selectedList: 1, // 0-based index
//                   header: false,
//                   minWidth: "100px"
//                });   
//            }
//        });
//   
//        $("select").live('multiselect', function(){
//           selectedList: 4, // 0-based index
//           header: false,
//           minWidth: "100px"
//        });
        
        $('input').live('click', function() {
           $(this).focus();
           $(this).select(); 
        });
        
        $('.expand').live('click', function () {
            var originalHeight = $(this).data('height');
            
            if (!$(this).hasClass('current')) {
                $(this).addClass('current');   
                $(this).animate({ height: "4em" }, 500);                             
            }

            $(this).bind( "clickoutside", function(event){
                $(this).removeClass('current');
                $(this).animate({ height: originalHeight }, 500);
                $(this).unbind('clickoutside');
                
            });
        });

        // open a default dialog to confirm an action
        //<a href="#" class="confirm" title="Are you sure?">Delete</a>
        $('.confirm').click(function() {
            var text = this.title;
            return confirm(text);
        });

    }

});
  
