/*
 * Avro Knockout Bindings
 *
 * Requires Avro utility functions
 */
//custom binding to just handle initializing a value
ko.bindingHandlers.initValue = {
    init: function (element, valueAccessor) {
        var value = valueAccessor();
        if (ko.isObservable(value)) {
             value(element.value);   
        }
    }
};

//custom binding that wraps the value binding and does initialization
ko.bindingHandlers.valueWithInit = {
    init: function(element, valueAccessor, allBindingsAccessor) {
        var value = valueAccessor();
        if (ko.isObservable(value)) {
            value(element.value);
        }    
        ko.bindingHandlers.value.init(element, valueAccessor, allBindingsAccessor);
    },
    update: function(element, valueAccessor) {
        ko.bindingHandlers.value.update(element, valueAccessor);
    }
}
/*
 * zipCode mask
 */
ko.bindingHandlers.zipCode = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        var country = ko.utils.unwrapObservable(valueAccessor());
        switch (country) {
            case 'CA':
                $(element).mask("a9a 9a9");
            break;
            case 'US':
                $(element).mask("99999");
            break;
        }
    },
    update: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        var country = ko.utils.unwrapObservable(valueAccessor());
        switch (country) {
            case 'CA':
				$(element).val($(element).val().toUpperCase());
            break;
            case 'US':
            break;
        }
    }
};

/*
 * Phone mask
 */
ko.bindingHandlers.phone = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        $(element).mask("(999) 999-9999");
    }
};

/*
 * Set Case
 */
ko.bindingHandlers.setCase = {
    update: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        var type = ko.utils.unwrapObservable(valueAccessor()),
            $element = $(element),
            value = $element.val();

        switch(type) {
            case 'title':
				$element.val(value.charAt(0).toUpperCase() + value.slice(1).toLowerCase());
            break;
            case 'pascal':
				value = value.toLowerCase().replace(/\b[a-z]/g, function(txtVal) {
					return txtVal.toUpperCase();
				});
				$element.val(value);
            break;
            case 'upper':
				$element.val(value.toUpperCase());
            break;
            case 'lower':
				$element.val(value.toLowerCase());
            break;
        }
    }
};

/*
 *
 * Chosen
 *
 * Turns select inputs into Harvest Chosen selects
 */
ko.bindingHandlers.chosen = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        return false;
        ko.utils.unwrapObservable(valueAccessor());
        var randomNum = Math.ceil(Math.random() * 1000),
            $select = $(element),
            oldId = $select.attr('id'),
            newId = oldId + '_' + randomNum
        ;
//            previousFilters = [],
//            options = allBindingsAccessor().options,
//            getOptions = allBindingsAccessor().getOptions,
//            optionsValue = allBindingsAccessor().optionsValue,
//            optionsText = allBindingsAccessor().optionsText,
//            json;

//        // populate observable array if select field is initialized with options
//        if (allBindingsAccessor().populateOptions) {
//            $select.find('option').each(function () {
//                json = {};
//                json[optionsValue] = $(this).val();
//                json[optionsText] = $(this).text();
//                previousFilters.push(json);
//            });
//
//            options(previousFilters);
//        }
//
//
        //ensure id is unique
        $select.attr('id', newId);
        $select.parents('.control-group').find('label').attr('for', newId);
//
//        // symfony2 preferred options fix
//        var $separator = $select.find("option:contains('-------------------')");
//        if ($separator.length) {
//            $separator.remove();
//            $select.find('option:eq(1)').attr('selected', 'selected');
//        }
//
        // initialize chosen 
        $select.chosen({
            disable_search_threshold: 15
        });
//
//        // update entity_id
//        if (allBindingsAccessor().options) {
//            $select.change(function() {
//                $($select.data('target')).val($(this).val());
//            });
//        }
    },
    update: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        return false;
        var value = ko.utils.unwrapObservable(valueAccessor());
        setTimeout(function() {
            $(element).trigger("liszt:updated")
        }, 250);
    }
};

/*
 * NewSelect
 *
 * Adds a new field to a select field and loads the entitys form if selected
 */
ko.bindingHandlers.newSelect = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        // if new is selected, get the form via ajax
//        $select = $(element);
//        $select.change(function() {
//        var selectObservable = ko.utils.unwrapObservable(valueAccessor()),
//            newSelectTarget = allBindingsAccessor().newSelectTarget,
//            options = allBindingsAccessor().options,
//            optionsValue = allBindingsAccessor().optionsValue,
//            optionsText = allBindingsAccessor().optionsText,
//            randomNum = Math.ceil(Math.random() * 1000),
//            $select = $(element),
//            field,
//            target,
//            $form;
//
//            if ($select.val() == 'new') {
//
//              return false;
//                // reset select
//                $select.val("");
//
//                //get variables for dialog
//                field = $select.data('field') ? $select.data('field') : $select.attr('id').split('_')[3];
//                target = $select.data('target') ? $select.data('target') : '.' + field + '-form-container';
//                //get form via ajax
//                avro.ajax({
//                    dataType: 'html',
//                    url: $select.data('href'),
//                    success: function (response) {
//                        //attach response to body
//                        $('body').append('<div id="ajax_content_' + randomNum +'">' + response + '</div>');
//
//                        //remove form binding to allow override
//                        $form = $('#ajax_content_' + randomNum + ' form');
//                        var binding = $form.data('bind');
//                        binding = binding.replace("submit: bindForm, ", "");
//                        $form.attr('data-bind', binding);
//
//                        //execute javascript
//                        eval($('#ajax_content_' + randomNum + ' script[type="text/html"]').text());
//                        avro.ajaxManager.clearCache();
//                        //submit form via ajax and update select, close dialog
//                        $form.avroAjaxForm({
//                            success:   function (response) {
//                                if (newSelectTarget) {
//                                    newSelectTarget.push(response['data']);
//                                }
//                                $select.find('option:last').before('<option value="' + response['data']['id'] +'" selected="selected">' + (response['data']['first_name'] ? response['data']['first_name'] +' ' + response['data']['last_name'] : response['data']['name']) + '</option>');
//                                $select.val(response['data']['id']);
//                                $select.trigger("liszt:updated");
//                                $form.closest('.ui-dialog-content').dialog('close');
//                            }
//                        });
//                    }
//                });
//            }
//        });

        ko.bindingHandlers.chosen.init(element, valueAccessor, allBindingsAccessor, viewModel);
    },
    update: function (element, valueAccessor, allBindingsAccessor, viewModel) {
//        return false;
//        ko.bindingHandlers.chosen.update(element, valueAccessor, allBindingsAccessor, viewModel);
//
//        $select = $(element);
//        // add new option
//        if (!$select.find('option[value="new"]').length) {
//console.log('append new');
//            if (options) {
//                json = {};
//                if (optionsValue) {
//                    json[optionsValue] = 'new';
//                    json[optionsText] = '+ Create New';
//                }
//                options.push(json);
//            } else {
//                $select.append('<option value="new">+ Create New</option>');
//            }
//
        ko.bindingHandlers.chosen.update(element, valueAccessor, allBindingsAccessor, viewModel);
    }
};

/*
 * Bootstrap Popover Binding
 */
ko.bindingHandlers.popover = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        var placement = $(element).data('placement');
        $(element).popover({'placement': placement });
    }
};

/*
 * Bootstrap Tooltip Binding
 */
ko.bindingHandlers.tooltip = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        $(element).tooltip();
    }
};

/*
 * Bootstrap Timepicker Binding
 */
ko.bindingHandlers.timepicker = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        var $input = $(element),
            observable = valueAccessor(),
            time = ko.utils.unwrapObservable(valueAccessor());

        $input.timepicker({
            minuteStep: 5,
            template: 'dropdown'
        });
        $input.timepicker().on('hide', function() {
            if (false !== time) {
                observable($input.val());
            }
        });
    },
    update: function (element, valueAccessor, allBindingsAccessor) {
        var $input = $(element),
            time = ko.utils.unwrapObservable(valueAccessor());

        $input.timepicker('updateFromElementVal');
    }
};

ko.bindingHandlers.datepicker = {
    init: function (element, valueAccessor, allBindingsAccessor) {
        var $input = $(element),
            observable = valueAccessor(),
            date = ko.utils.unwrapObservable(valueAccessor());

        $input.parent().datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        }).on('hide', function() {
            if (false !== date) {
                observable($input.val());
            }
        });
        
//    },
//    update: function (element, valueAccessor, allBindingsAccessor) {
//        var $input = $(element),
//            date = ko.utils.unwrapObservable(valueAccessor());
//
//            $input.datepicker('update');
    }
};

/*
 * Google Authenticator
 *
 * Make sure google password has a value observable otherwise it won't update
 */
ko.bindingHandlers.googleAuthenticator = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        $(element).parent().append('<a href="#" id="googleAuthenticator" style="margin-top: 0.25em; display: block;"><i class="sprite-shield-go"></i>Test Connection</a>');
        var observable = valueAccessor();
        $('#googleAuthenticator').click(function() {
            observable.valueHasMutated();
        });
    },
    update: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        var $input,
            href,
            username,
            password;

        $input = $(element);
        href = $input.data('href');
        username = ko.utils.unwrapObservable(valueAccessor());
        password = $input.val();
        if (username && password) {
            avro.ajax({
                data: {googleUsername: username, googlePassword: password},
                url: href,
                success: function (response) {
                    if (response['status'] == 'OK') {
                        avro.createSuccess('Google connection successful.');
                    } else {
                        avro.createError('Google connection failed.');
                    }
                },
            });
        }
    }
};

/*
 * Readonly binding
 */
ko.bindingHandlers.readOnly = {
    update: function(element, valueAccessor) {
        var value = ko.utils.unwrapObservable(valueAccessor());
        if (value) {
            element.setAttribute("readOnly", "readonly");

            if ($(element).is('select')) {
                $('#' + $(element).attr('id') + '_chzn .chzn-single').addClass('chzn-disabled');
            }

        }  else {
            element.removeAttribute("readOnly");

            if ($(element).is('select')) {
                $('#' + $(element).attr('id') + '_chzn .chzn-single').removeClass('chzn-disabled');
            }
        }  
    }  
}

/*
 * page binding
 */
ko.bindingHandlers.page = {
    update: function(element, valueAccessor) {
        var show = ko.utils.unwrapObservable(valueAccessor());
        var $page = $(element);

        $('html, body').animate({ scrollTop: 0 }, 'slow');
        if (show == true) {
                $page.slideDown(500)
        } else {
            $page.hide();
        }
    }  
}
