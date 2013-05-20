/*
 * EntityId
 */
ko.bindingHandlers.entityId = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        var $element = $(element),
            $target = $(valueAccessor());

        $element.change(function() {
            $target.val($(this).val());
        }); 
    }
};

/*
 * List Filter
 */
ko.bindingHandlers.filter = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        var $element = $(element),
            filterValue = ko.utils.unwrapObservable(valueAccessor()),
            offset = viewModel.offset;

        $element.click(function(){
            var filter = viewModel.filter;
            filter(filterValue);
            $('#searchFormModal form').clearForm();
            if (filterValue == 'Search') {
                $('#searchFormModal').modal('show');
            } else {
                if (offset() == 0) {
                    offset.valueHasMutated();
                } else {
                    offset(0);
                }
            }
        }); 
    }
};

/*
 * OrderBy
 */
ko.bindingHandlers.orderBy = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        var $header = $(element);
        var field = ko.utils.unwrapObservable(valueAccessor());
        var filter = viewModel.filter;
        var orderBy = viewModel.orderBy;
        var offset = viewModel.offset;
        var direction = viewModel.direction;
        if ($header.find('i').length == 0) {
            $header.append('<i></i>');
        };
        var wrappedValueAccessor = function() {
            return function(data, event) {
                $header.closest('tr').find('i').removeClass('sprite-asc sprite-desc');
                if (offset() === 0) {
                    offset.valueHasMutated();
                } else {
                    offset(0);
                }
                if (orderBy() === field) {
                    if (direction() === 'ASC') {
                        direction('DESC');
                        $header.find('i').attr('class', 'sprite-asc');
                    } else {
                        direction('ASC');
                        $header.find('i').attr('class', 'sprite-desc');
                    }
                } else {
                    orderBy(field);
                    if (direction() === ('ASC')) {
                        $header.find('i').attr('class', 'sprite-asc');
                    } else {
                        $header.find('i').attr('class', 'sprite-desc');
                    }
                }
            };
        };
        ko.bindingHandlers.click.init(element, wrappedValueAccessor, allBindingsAccessor, viewModel);
    },
};

ko.bindingHandlers.paginate = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        var direction = valueAccessor;
        var $button = $(element);
        var offset = viewModel.offset;
        var limit = viewModel.limit;
        var target = allBindingsAccessor().target;
        var wrappedValueAccessor = function() {
            return function(data, event) {
                if (!$button.hasClass('disabled')) {
                    if (direction() === 'next') {
                        offset(avro.add(offset(), limit(), 0));
                    } else {
                        if (avro.subtract(offset(), limit(), 0) <= 0) {
                            offset(0);
                        } else {
                            offset(avro.subtract(offset(), limit(), 0));
                        }
                    }
                }
            };
        };
        ko.bindingHandlers.click.init(element, wrappedValueAccessor, allBindingsAccessor, viewModel);
    },
    update: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        var target = allBindingsAccessor().target;
        var direction = valueAccessor;
        var offset = viewModel.offset;
        var limit = viewModel.limit;
        var $button = $(element);
        $button.removeClass('disabled');
        if (direction() === 'next') {
            if (target().length < limit()) {
                $button.addClass('disabled');
            }
        } else {
            if (offset() <= 0) {
                $button.addClass('disabled');
            }
        }
    }
};

ko.bindingHandlers.searchForm = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        var $form = $(element);
        var target = valueAccessor();
        var offset = viewModel.offset;
        offset.subscribe(function() {
            // wait for offset to update
            setTimeout(function() {
                $form.submit();
            }, 0);
        });
        var wrappedValueAccessor = function() {
            return function(data, event) {
                var $form = $(element);
                avro.ajax({
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    success:   function(response){
                        if (response['status'] === 'OK') {
                            $('#searchFormModal').modal('hide');
                            target(response['data']);
                        } else {
                            avro.createError(response['notice']);
                        }
                    }
                });
            };
        };
        ko.bindingHandlers.submit.init(element, wrappedValueAccessor, allBindingsAccessor, viewModel);
    }
};

ko.bindingHandlers.limit = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        $(element).change(function() {
            viewModel.limit($(this).find('option:selected').text());
        });

        ko.bindingHandlers.chosen.init(element, valueAccessor, allBindingsAccessor, viewModel);
    }
};

