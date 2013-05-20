(function($)
{
    jQuery.fn.capitalize = function(options)
    {
        var defaults = {
            event: 'keyup'
        };
        $.extend(defaults, options);
        return this.each(function(){
            jQuery(this).bind(defaults.event, function(){
                jQuery(this).val(jQuery.cap(jQuery(this).val()));
            });
        });
    }
})(jQuery);

jQuery.cap = function capitalizeText(text)
{
  text = text.toLowerCase();  
  return text.replace(/(\b)([a-zA-Z])/g,
           function(firstLetter){
              return   firstLetter.toUpperCase();
           });
}; 
