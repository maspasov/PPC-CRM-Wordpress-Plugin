jQuery(document).ready(function($){ 



    $('input[name$="input_8"]').attr('placeholder','Enter Security Code');
    
    $('a').click(function() {
        if ($(this).attr('href').indexOf(contact_us_slug) != -1) {
            _gaq.push(['_trackEvent', 'webform', 'visit', 'quote']);
        };
    });

    //Cookie Policy Message
    var ln = bloginfo_url.split('.').length;
    var domain_extension = bloginfo_url.split('.')[ln-1];
    $(".close_privacy").remove();
    
    if(domain_extension != "au") {
          //UK Cookie Policy
         $('#cookie-policy').html('<p>Updated cookies policy - you\'ll see this message only once. ' + bloginfo_name + ' uses cookies on this website. They help us to know more about you and how you use our website, which improves the browsing experience and marketing - both for you and for others. They are stored locally on your computer or mobile device. To accept cookies continue browsing as normal. Learn more <a href="http://ico.org.uk/" class="close_privacy" target="_blank" rel="nofollow">about cookies</a>.<span class="close_privacy">x</span></p>');
    } else {
         //AU Cookie Policy
         $('#cookie-policy').html('<p>Updated cookies policy - you\'ll see this message only once. ' + bloginfo_name + ' uses cookies on this website. They help us to know more about you and how you use our website, which improves the browsing experience and marketing - both for you and for others. They are stored locally on your computer or mobile device. To accept cookies continue browsing as normal. Learn more <a href="http://www.oaic.gov.au/" class="close_privacy"  target="_blank" rel="nofollow">about cookies</a>.<span class="close_privacy">x</span></p>');
    }

   
    $('.close_privacy').click(function() {
          setPrivacyCookie();
    });
     
    //GForm Placeholder 
    $('input[type=text]').ToggleInputValue();
    $('textarea').ToggleInputValue();

    //Filled Form Data Lose Protection
    jQuery('.gform_wrapper input, .gform_wrapper select,.gform_wrapper textarea').LeavePageProtection();

 
});



function setPrivacyCookie() {
    document.cookie = 'cpolicy=true; expires=Fri, 27 Jul 2033 02:47:11 UTC; path=/';
    jQuery("#cookie-policy").slideUp('slow');
}


/*-------------- SET SPECIAL OFFER SERVICE TO BOOKING FORM -----/*/
function bookSpecialOffer(currentOffer) {
    var specialOffers = new Array;
    jQuery("#currentOffer").val(currentOffer);
    jQuery.each(jQuery("img[data-special-offers]"), function() {
        specialOffers.push(jQuery(this).attr("data-special-offers") + ":::" + jQuery(this).attr("data-offer-title"));
    });
    jQuery("#specialOffers").val(specialOffers);
    jQuery("#bookSpecialOffersForm").submit();
}
/*-------------- END SET SPECIAL OFFER SERVICE TO BOOKING FORM -----/*/ 

(function ($) {


     //If you have some filled data in the form, 
     //when you try to leave the page it will warn you for data lost.
     //Ex: jQuery('input field1,input field2...etc').LeavePageProtection();
     $.fn.LeavePageProtection = function() {

        //Get Default values
        form_container = $(this);

        var defaultValues = [];
        var input_flag = false;
        var submit_trigger = false;


        form_container.each(
            function(index) {
                var input = jQuery(this);
                if (input.attr('type') != 'hidden' && input.attr('type') != 'submit') {
                    defaultValues.push(input.val());
                }
            }
        );

        //Check form is filled if there is values Alert on leave
        jQuery('[id^="gform_"]').submit(function(event) {
            submit_trigger = true;
        });


        window.onbeforeunload = function() {
            form_container.each(
                function(index) {
                    var input = jQuery(this);

                    if (input.attr('type') != 'hidden' && input.attr('type') != 'submit') {
                        if (jQuery.inArray(input.val(), defaultValues) === -1) {
                            input_flag = true;
                        }
                    }

                }
            );
            if (input_flag && !submit_trigger)
                return "All the entered data will be lost!";
        };
    }


    $.fn.ToggleInputValue = function(){
        return $(this).each(function(){
            var Input = $(this);
            var default_value = Input.val();
            Input.addClass('grey');

            Input.focus(function() {
               if(Input.val() == default_value) 
               {
                Input.val("");
                Input.removeClass('grey');
               }    
            }).blur(function(){
                if(Input.val().length == 0) {
                    Input.val(default_value);
                    Input.addClass('grey');
                }   
            });
        });
    }

    /**
     * @function
     * @property {object} jQuery plugin which runs handler function once specified element is inserted into the DOM
     * @param {function} handler A function to execute at the time when the element is inserted
     * @param {bool} shouldRunHandlerOnce Optional: if true, handler is unbound after its first invocation
     * @example $(selector).waitUntilExists(function);
     */
    $.fn.waitUntilExists = function (handler, shouldRunHandlerOnce, isChild) {
        var found = 'found';
        var $this = $(this.selector);
        var $elements = $this.not(function () {
            return $(this).data(found);
        }).each(handler).data(found, true);
        if (!isChild) {
            (window.waitUntilExists_Intervals = window.waitUntilExists_Intervals || {})[this.selector] =
                window.setInterval(function () {
                    $this.waitUntilExists(handler, shouldRunHandlerOnce, true);
                }, 500);
        } else if (shouldRunHandlerOnce && $elements.length) {
            window.clearInterval(window.waitUntilExists_Intervals[this.selector]);
        }
        return $this;
    }
}(jQuery));