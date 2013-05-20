;(function(avro, $, undefined) {

    avro.getCurrentTime = function() {
        var dTime = new Date();
        var hours = dTime.getHours();
        var minute = dTime.getMinutes();
        var period = "AM";
        if (hours === 0) {
            hours = 12;
        } else if (hours > 12) {
            hours = hours - 12;
            period = "PM";
        } else {
            period = "AM";
        }

       return avro.pad2(hours) + ":" + avro.pad2(minute) + " " + period;
    }

    avro.convertTo24Hr = function(input) {
        var hour,
            separator,
            minutes,
            pm;

        hour = input.substring(0,2);            
        separator = input.substring(2,3);
        minutes = input.substring(3,5);            
        pm = input.substring(6,8);
        if (pm == 'PM') {
            hour = avro.cleanNumber(hour) + 12;
        }
//console.log('24hr converter hours' + hour);
//console.log('24hr converter minutes' + minutes);

        input = hour + separator + minutes;

        return input;
    };

    avro.convertTo12Hr = function(input) {
        var meridian = 'AM',
            hours = input.substring(0,2),
            separator = input.substring(2,3),
            minutes = input.substring(3,5);

        if (hours > 12) {
            meridian = 'PM';
            hours -= 12;
        } else if (hours == 12) {
            meridian = 'PM';
        } else if (hours == 0) {
            hours = 12;
        }
//console.log('pad2 hours' + avro.pad2(hours));
//console.log('pad2 minutes' + avro.pad2(minutes));
        return avro.pad2(hours) + separator + avro.pad2(minutes) + ' ' + meridian;
    };

    avro.pad2 = function(number) {
        number = avro.cleanNumber(number);

        return (number < 10 ? '0' : '') + number    
    };

}(window.avro = window.avro || {}, jQuery)); 

