;(function(avro, $, undefined) {

    // --- Multiply an array of inputs---
    avro.arrayProduct = function(input, decimals, trim) {
        var Index = 0,
            product = 1;    

        if (input instanceof Array) {
            while (Index < input.length) {
                product *= (!isNaN(input[Index]) ? input[Index] : 0);
                Index++;
            }    
        } else {
            product = null;
        }

        return avro.roundNumber(product, decimals, trim);
    };

    // --- add an array of inputs ---
    avro.arrayAdd = function(input, decimals, trim) {
        var index = 0,
            total = 0,
            number;

        if (input instanceof Array) {
            while (index < input.length) {
                total = avro.add(input[index], total, decimals, trim);
                index++;
            }    
        } else {
            total = 0.00;
        }

        return avro.roundNumber(total, decimals, trim);
    };

    // --- multiply two inputs ---
    avro.multiply = function(input1, input2, decimals, trim) {
        input1 = avro.cleanNumber(input1);
        input2 = avro.cleanNumber(input2);

        return avro.roundNumber(( input1 * input2 ), decimals, trim);
    };

    // --- add two inputs ---
    avro.add = function(input1, input2, decimals, trim) {
        input1 = avro.cleanNumber(input1);
        input2 = avro.cleanNumber(input2);

        return avro.roundNumber(input1 + input2, decimals, trim);
    };

    // --- subtract two inputs ---
    avro.subtract = function(input1, input2, decimals, trim) {
        input1 = avro.cleanNumber(input1);
        input2 = avro.cleanNumber(input2);

        return avro.roundNumber((input1 - input2), decimals, trim);
    };

    // --- divided two inputs ---
    avro.divide = function(input1, input2, decimals, trim) {
        input1 = avro.cleanNumber(input1);
        input2 = avro.cleanNumber(input2);

        if (input1 === 0 || input2 === 0) {
            return avro.roundNumber(0, decimals, trim);
        } 

        return avro.roundNumber(( input1/input2 ), decimals, trim);
    };

    // --- round a number ---
    avro.roundNumber = function(number, decimals, trim) {
        if (decimals === undefined) {
            var decimals = 2;
        }

        var $result = String(parseFloat(Math.round(number * 10000) / 10000).toFixed(decimals));
        if (trim) {
            $result = $result.replace(/(\.[1-9]*)0+$/, "$1"); 
            $result = $result.replace(/\.$/, ""); 
        }

        return $result;
    };


    // --- parse a currency ---
    avro.parseCurrency = function(input) {
        var num = parseFloat(value.replace(/[^\d.]+/g, ""));

        return isNaN(num) ? 0.00 : num;
    };

    avro.cleanNumber = function(input) {
        if ((input === null) || (input === "") || (input === undefined)) {
            return 0;
        }

        var number = parseFloat(input);
        
        if (isNaN(number)) {
            //alert(input + 'is not a number');
            return 0;
        } else {
            return number;
        }
    };

    avro.fractionToDecimal = function(n, decimals) {
        if (decimals === undefined) {
            var decimals = 2;
        }

        var arr = n.split(" ");
        if (arr.length = 1) {
            console.log(1);
        } else{
            console.log(2);
        }

        return n.toFixed(decimals);
    };

    avro.decimalToFraction = function(n) {
        // Check if the value is a number
        if (!isNaN(n) && n!== "" && n!=="0" && n!=="Infinity" && n!=="-Infinity") {
            // to count the number of decimal places
            var dn=n.substr(n.lastIndexOf('.'));
            var nd=dn.length-1;
            var den=1,i;

            // Prepare an appropriate denominator
            for (i=1; i<=nd; i++) {
                den*=10
            }

            var num=Math.floor(n*den);

            // This is the heart of the function
            // Successively check for common factors of numerator and denominator
            // Divide if a common factor is found
            for (i=2;num>den?i<num:i<den;i++) {
                if (num%i==0 && den%i==0) {
                    den/=i;
                    num/=i;
                    i=1;
                }
            }

            // This part prepares the mixed fraction form
            var num2=num%den;
            var den2=den;
            var fr=(num-num2)/den;
            var result;

            if(fr!==0) {
                result = fr +' ' + num2 + '/' + den2;
            }
            return result;
        } else {
            alert('Invalid number');
        }
    };
}(window.avro = window.avro || {}, jQuery)); 
