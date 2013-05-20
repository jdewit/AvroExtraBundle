$(document).ready(function(){
    
    if ($('#invoiceForm').length) {
        loadServicesSelect();
        //loadDefaultEmployees();       
        sumEmployeeHours();
    }

    function listTaxes () {
        var taxes = $('#Invoice_subTotal').data('taxes');
        var taxTable = $('#taxTable');
        
        $.each(taxes, function(k, v) {    
            
            //alert(v.name);
            var prototype = taxTable.find('script[type="text/html"]').text();

            //alert(taxTable.attr('class'));
            
            //copy template and append a clone of it to a new row
            taxTable.append(prototype);
            var newRow = taxTable.find('tr.taxItem:last-child');
            newRow.addClass('taxItem-'+v.id);
            newRow.find('label').text(v.name);
            newRow.find('input:last-child').addClass('taxTotal taxTotal-'+v.id);

        });   
        
            //rename input ids and names
            var index = 0;
            taxTable.find('tr.taxItem').each(function() {                
                $(this).find('input, select').each(function() {
                    $(this).attr('id', $(this).attr('id').replace(/\$\$name\$\$/g, index));
                    $(this).attr('name', $(this).attr('name').replace(/\$\$name\$\$/g, index));
                });
                $(this).find('label').each(function() {
                    $(this).attr('for', $(this).attr('for').replace(/\$\$name\$\$/g, index));
                });
                index = index + 1;
            });

          
    }

    function refreshRow(container) {

        var serviceSelect = container.find('select.service');
        var serviceId = serviceSelect.val();
        
        if (serviceId != "") {            
            if (serviceId == 'new') {
                alert('create new service dialog.');
            } 
            var subTotal = container.find('input.rate').val() * container.find('input.actHours').val();                   
            var option = serviceSelect.find(':selected');

//        row.find('select.tax1').val(option.data('tax1Id'));
//        row.find('.tax1Amount').attr('class, tax1Amount taxAmount taxId-' + option.data('tax1Id'));
//        row.find('select.tax2').val(option.data('tax2Id'));
//        row.find('.tax2Amount').attr('class, tax2Amount taxAmount taxId-' + option.data('tax2Id'));

            
            //if tax exists, set each serviceItems taxId and taxTotal
            var tax1Id = option.data('tax1Id');
            var taxAmountInput = container.find('input.tax1Amount');
            if (tax1Id != '') {
                //alert(option.data('tax1Rate'));
                var tax1Total = roundNumber(option.data('tax1Rate') * subTotal,2);
                taxAmountInput.val(tax1Total);
                taxAmountInput.attr('class', 'tax1Amount taxAmount taxId-' + option.data('tax1Id') );                
            } else {
                var tax1Total = '';
                taxAmountInput.val(tax1Total);
                taxAmountInput.attr('class', 'tax1Amount taxAmount');               
            }
            
            var tax2Id = option.data('tax2Id');
            var taxAmountInput = container.find('input.tax2Amount');
            if (tax2Id != '') {
                //alert(option.data('tax2Rate'));
                var tax2Total = roundNumber(option.data('tax2Rate') * subTotal,2);                
                taxAmountInput.val(tax2Total);
                taxAmountInput.attr('class', 'tax2Amount taxAmount taxId-' + option.data('tax2Id') );                
            } else {
                tax2Total = '';
                taxAmountInput.val(tax2Total);
                taxAmountInput.attr('class', 'tax2Amount taxAmount');               
            }
//            var services = serviceSelect.data('services');
//            //console.log(services);
//            var tax1Id = services[serviceId].tax1Id;
//            //alert(tax1Id);
//            var tax1Rate = services[serviceId].tax1Rate;
//            var tax2Rate = services[serviceId].tax2Rate;    
//
//            var tax1Total = roundNumber(tax1Rate * subTotal, 2);
//            var tax2Total = roundNumber(tax2Rate * subTotal, 2);
            //alert(tax1Total);
            container.find('.subTotal').val(subTotal);
            
            var total = subTotal + tax1Total + tax2Total
            container.find('.total').val(total);
//            container.find('.tax1').val(tax1Total);
//            container.find('.tax2').val(tax2Total);

            refreshTotals();            
        }

    }

    function refreshTotals () {
        //sum estHourTotals
        var estHours = 0;
        $('input.estHours').each(function() {
            estHours += roundNumber($(this).val(),2);
        });
        $('input#Invoice_estHoursTotal').val(estHours);

        //sum actHourTotals
        var actHours = 0;
        $('input.actHours').each(function() {
            actHours += roundNumber($(this).val(),2);
        });
        $('input#Invoice_actHoursTotal').val(actHours);

        //sum subTotals
        var subTotal = 0;
        $('input.subTotal').each(function(){
            if (!($(this).parents('tr').hasClass('template'))) {
                subTotal += roundNumber($(this).val(), 2);
            }            
        });  
        $('#Invoice_subTotal').val(subTotal);    
        
        var taxes = $('#Invoice_subTotal').data('taxes');

        //sum taxes for each serviceItem and create a taxItem if it doesn't already exist'
        $.each(taxes, function(k, v) {    
            //alert(v.name);

            var taxTable = $('#taxTable');
            var taxTotalElement = $('input.taxTotal.taxId-'+v.id);
                //alert('.taxTotal-'+v.id);
            var total = 0;
            $('input.taxAmount.taxId-'+v.id).each(function(){  
               total += roundNumber($(this).val(), 2);              
            });
            //alert(total);
            //only do something if the tax has value
            if (total >= 0) {
                //if the taxItem does not exist, add it via prototype, if it does, just change the value
                if ($('tr.taxItem-'+v.id).length > 0) {
                    //alert('aa'+total);
                    
                    taxTotalElement.val(roundNumber(total, 2));                 
                } else {
                    var prototype = taxTable.find('script[type="text/html"]').text();
                    //console.log(prototype);
                    taxTable.append(prototype);
                    var newRow = taxTable.find('tr.taxItem:last-child');
                    newRow.addClass('taxItem-'+v.id);
                    newRow.find('select').val(v.id);
                    newRow.find('label.taxName').text(v.name);
                    newRow.find('input.taxTotal').addClass('taxTotal taxId-'+v.id);  
                    //console.log(taxTotalElement);
                    //alert('a'+total);
                    $('input.taxTotal.taxId-'+v.id).val(roundNumber(total, 2));
                    
                    var index = 0;
                    taxTable.find('.taxItem').each(function() {
            //                    $(this).attr('label', $(this).attr('name').replace(/\$\$name\$\$/g, index));                    
                        $(this).find('input, select').each(function() {
                            $(this).attr('id', $(this).attr('id').replace(/\$\$name\$\$/g, index));
                            $(this).attr('name', $(this).attr('name').replace(/\$\$name\$\$/g, index));
                        });
                        index = index + 1;
                    });  
                }
            } else {
                //alert('tr.taxItem-'+v.id);
                $('tr.taxItem-'+v.id).hide().find('input.taxTotal').val('');
            }
           

        });  
        
        var taxTotal = 0;
        $('input.taxTotal').each(function(){
            taxTotal += roundNumber($(this).val(), 2);
        });
        
        var grandTotal = subTotal + taxTotal;
        
        $('#Invoice_total').val(grandTotal);
        
        refreshEmployeeHours();
        
    }

    $('a.addService').live('click', function(event) {
        event.preventDefault();

        //get target container
        var collectionItemsContainer = $( $(this).data('target') );
        //alert(formOptionsTable.attr('class'));
        var newRow = collectionItemsContainer.find('script[type="text/html"]').text();         

        //append and show new collection item to collection container
        collectionItemsContainer.append( newRow );  

        refreshCollection(collectionItemsContainer);
        //refreshRow(newRow);
    });

    $('#invoiceItems a.removeInvoiceItem').live('click', function() {
        $(this).parents('tr').remove();
        refreshTotals(); 
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

    //load services select options with estimated service items
    function loadServicesSelect() {
        var jobSelect = $('select#Invoice_job');
        //alert(jobSelect.val());
        if (jobSelect.val() == '') {
            return false;
        } else if (jobSelect.val() == 'new') {
            return false;
        } else if (jobSelect.val() == null) {
            return false;
        } else {
            var url = jobSelect.data('url');
            //alert(url);
            $.get(url, {id: jobSelect.val()}, function(data){
                  //console.log(data);
                  var prototype2 = $('tbody#invoiceItems').find('script[type="text/html"]').text();
                  //console.log(prototype2);
                  var serviceSelect = $(prototype2).find('select.service').html();
                  //console.log(serviceSelect);
                  //console.log(data);
                  var servicePrepend = "<option>Select an item</option><option disabled>-----------All Services----------</option>";
                  //var newOptions = servicePrepend + serviceSelect + data;
                  var newOptions = data;
                  var a = prototype2.replace(serviceSelect, newOptions);
                  //alert(a);
                  $('tbody#invoiceItems').find('script[type="text/html"]').text(a);

                  $('tbody#invoiceItems select.service').each(function() {
                      var selected = $(this).val();
                      //$(this).prepend(servicePrepend);
                      //$(this).append(data);
                      $(this).html(data);
                      $(this).val(selected);
                  });

                  if ($('tbody#invoiceItems tr.item').length == 0) {
                     $('a.addService').click(); 
                  }                
            });
              

              //console.log(updatedPrototype);
              //prototype.html(updatedPrototype);
              //
              //var prototypeText = prototype.text();  
              
               // $(prototype).find('select.service').html(data);                  
        }        
    }
    
    function loadDefaultEmployees() {
        loadHourItems();
    }
    
    function loadHourItems(){       
        var crewSelect = $('#invoice_Crew');
        var array = $('#Invoice_crew').data('crews');
        //console.log(array[$('#Invoice_crew').val()]);
        $.each(array[$('#Invoice_crew').val()], function() {
            //alert(this.id); 
            $('#hourItems .addCollectionItem').click();
            //console.log($('#hourItems .collectionItems').find('select:last-child').val());
            $('#hourItems tr.item:last-child select').val(this.id);
            //alert($('#hourItems tr.item:last-child select').attr('id'));
        });        
    }
    
    $('#Invoice_crew').live('change', function(){
        $('#hourItems tr.item').remove();
        loadHourItems();
    });
    
    $('#Invoice_job').live('change', function(){
            loadServicesSelect();            
    });
    

    function loadServiceItems() {
        var clientSelect = $('#Invoice_client');
        //if (clientSelect.parents('form').hasClass('new')) {
            var serviceItems = $('#Invoice_job').find('option:selected').data('serviceitems');

            //console.log(serviceItems);
            var formOptionsTable = $('#invoiceForm table.formOptions');
            //remove previous rows
            //formOptionsTable.find('tr.option').remove();
            $.each(serviceItems, function() {
                var serviceItem = formOptionsTable.find('tr.template').clone().removeClass('template').addClass('option').show(); 
                //alert(this.estHours);
                serviceItem.find('input, select').removeClass('template').removeAttr('disabled', true)
                serviceItem.find('.service').val(this.id);
                console.log(this.id);
                serviceItem.find('.description').val(this.description);
                serviceItem.find('.rate').val(this.rate);
                serviceItem.find('.estHours').val(this.estHours);
                serviceItem.find('.actHours').val(this.estHours);
                serviceItem.find('.subTotal').val(this.subTotal);
                serviceItem.find('.tax1').val(this.tax1Total);
                serviceItem.find('.tax2').val(this.tax2Total);
                //alert(formOptionsTable.attr('class'));
                formOptionsTable.append(serviceItem);
            });

            refreshTotals();

            var index = 0;
            formOptionsTable.find('tbody tr.option').each(function() {
                $(this).find('input, select').each(function() {
                    $(this).attr('id', $(this).attr('id').replace(/\$\$name\$\$/g, index));
                    $(this).attr('name', $(this).attr('name').replace(/\$\$name\$\$/g, index));
                });
                index = index + 1;
            });  
//        } else {
//            return false;
//        }
    };

    function refreshEmployeeHours() {
        var totalHours = $('#Invoice_actHoursTotal').val();
        //alert(totalHours);
        if (totalHours > 0) {
            var count = $('#hourItems input.amount').length;
            //alert(count);
            var dividedHours = totalHours/count;            
        } else {
            var dividedHours = 0;
        }
        
        $('#hourItems input.amount').val(dividedHours);
        
        sumEmployeeHours();
    }

    function sumEmployeeHours() {
        var totalHours = 0;
        $('#hourItems input.amount').each(function() {
            totalHours += roundNumber($(this).val(), 2);
        })
        
        $('#employeeHoursTotal').val(roundNumber(totalHours,2));
    }

    //update employee hours if any changes are made
    $('#hourItems a').live('click', function() {
       refreshEmployeeHours(); 
    });
    
    $('#hourItems input.amount').live('change', function() {
            sumEmployeeHours(); 
    });

    $('select#Invoice_client').append('<option disabled>------------------------------</option><option value="new">+ Create new client</option>');
    //$('select#Invoice_job').append('<option disabled>------------------------------</option><option value="new">+ Create new job</option>');
    $('select#Invoice_crew').append('<option disabled>------------------------------</option><option value="new">+ Create new crew</option>');

    // on client change, update the jobs select input
    $('#Invoice_client').live('change', function() {
        if ($(this).val() != '') {
            var url = $(this).data('url'); 
            
            $.get(url, {id: $(this).val()}, function(data){
              //alert(data);
              $("select#Invoice_job").html(data).find('option:first').attr('selected','selected');

              loadServicesSelect();
            });            
        }
    });

    //update employees assigned to job if selected crew changes
    $('#Invoice_crew').live('change', function() {
        //clear employees table
        $('#hourItems item').remove();
        
        //get url for getting crews employees json array via ajax request
        var url = $(this).data('url');
        var collectionItemsContainer = $('#hourItems .collectionItems');
 
        $.getJSON(url, {id: $(this).val()}, function(data){

          $.each(data, function() {
            var newRow = collectionItemsContainer.find('.template').clone().removeClass('template').addClass('item'); 
            //find employee selector and select the crews employee and activate the input
            newRow.find('select').val(this.id).removeClass('template').removeAttr('disabled', true);
            //activate hours input
            newRow.find('input').removeClass('template').removeAttr('disabled', true);
            
            //append and show new collection item to collection container
            collectionItemsContainer.append( newRow );
            newRow.show();   
          });

            refreshEmployeeHours();
        });        
    });

    //update row if actHours changes
    $('input.actHours').live('change', function() {
        var container = $(this).parents('tr');
        
        refreshRow(container);
    });

    //update row if rate changes 
    $('input.rate').live('change', function() {
        var container = $(this).parents('tr');
        
        refreshRow(container);
    });
    
    $('select.service').live('change', function() {
        var row = $(this).parents('tr');
        var option = $(this).find(':selected');
        
        row.find('input.description').val(option.data('description'));
        row.find('input.rate').val(option.data('rate'));
        row.find('input.estHours').val(option.data('esthours'));
        row.find('input.actHours').val(option.data('acthours'));
        
        refreshRow(row);
    });
    
    
    
//    $('.addService').live('click', function(){
//       refreshTotals();
//    });
    
    
//NOTE===================================================================     
    // on client change, update the jobs select input
    $('#note_client').live('change', function() {
        if ($(this).val() != '') {
            var url = $(this).data('url'); 
            
            $.get(url, {id: $(this).val()}, function(data){
              //alert(data);
              $("#note_job").html(data).find('option:first').attr('selected','selected');
            });            
        }
    });
//=========================================================================    
    function roundNumber(number, decimals) { // Arguments: number to round, number of decimal places
            var newnumber = new Number(number+'').toFixed(parseInt(decimals));
            //console.log(parseFloat(newnumber));
            return  parseFloat(newnumber); 
    }    
});