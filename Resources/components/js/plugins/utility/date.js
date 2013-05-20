;(function(avro, $, undefined) {
    avro.formatDate = function(d) {
        var curr_date = d.getDate();
        if (curr_date < 10) {
            curr_date = '0' + curr_date;
        }
        var curr_month = d.getMonth() + 1; //months are zero based
        if (curr_month < 10) {
            curr_month = '0' + curr_month;
        }
        var curr_year = d.getFullYear();

        return curr_year + "-" + curr_month + "-" + curr_date;
    };

    avro.addDays = function(input, days) {
        var dateParts = input.split('-'),
            y = parseInt(dateParts[0], 10), 
            m = parseInt(dateParts[1], 10), 
            d = parseInt(dateParts[2], 10), 
            newDate = new Date(y, m - 1, d + parseInt(days));

        return avro.formatDate(newDate);
    };

    avro.getTodaysDate = function(options) {
        var defaults = {
            format: 'yyyy-MM-dd'
        };
        var options = $.extend(defaults, options);
            
        if (options.format == 'yyyy-MM-dd') {
            var d = new Date();
            var curr_date = d.getDate();
            if (curr_date < 10) {
                curr_date = '0' + curr_date;
            }
            var curr_month = d.getMonth() + 1; //months are zero based
            if (curr_month < 10) {
                curr_month = '0' + curr_month;
            }
            var curr_year = d.getFullYear();

            return curr_year + "-" + curr_month + "-" + curr_date;
        } else { 
            return false;
        }
    };

}(window.avro = window.avro || {}, jQuery)); 

