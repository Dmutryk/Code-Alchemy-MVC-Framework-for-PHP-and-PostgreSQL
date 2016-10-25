/**
 *
 * Provides a simple jQuery plugin for forms that enables simple client-side validation
 * based on rules specified in CSS classes.
 *
 * valid options:
 *
 * debug:       (Boolean) true|false: if set to true, the plugin will output messages to
 *              the console
 * lang:        (string): optional language indicator, "en", "es" et all
 *              Right now only "en" and "es" are supported.
 *              Default is "en"
 * onStart:     (function): if defined and a function, will be called
 *          prior to beginning validation
 * onComplete: (function): if defined and a function, will call this function
 *         as a callback when the validation is complete.  The function receives
 *         a JSON Object describing the results of the validation
 *
 * Created by David Owen Greenberg <owen.david.us@gmail.com>
 * Date: 06/11/12
 * Time: 10:55 AM
 */
(function( $ ){

    $.fn.xo_validate = function( options ) {
        if ( ! $(this).length){
            return false;
        }
        // setup new form record
        var form_id = $(this).attr('id');
        // skip if alredy defined
        //if ( typeof( $.xo_validate.forms[form_id]) != 'undefined')
          //  return false;
        $.xo_validate.forms[ form_id ] = {
            form_id : form_id
        };
        // set various options
        $.xo_validate.forms[ form_id ].debug = options && options.debug?true:false;
        $.xo_validate.forms[ form_id ].onStart = options && options.onStart&&
            typeof(options.onStart)=='function'?options.onStart:null;

        $.xo_validate.forms[ form_id ].onComplete=options && options.onComplete &&
            typeof(options.onComplete)=='function'?options.onComplete:null;
        if ($.xo_validate.forms[ form_id].debug) console.log( "xo_validate: debugging enabled");
        if ($.xo_validate.forms[ form_id].debug) console.log( "xo_validate: form id is "+$(this).attr('id'));
        $.xo_validate.forms[ form_id].lang = options && options.lang? options.lang: "en";
        $.xo_validate.forms[ form_id].options = options;
        $.xo_validate.forms[ form_id].form = $(this);
        var btn = $(this).find('button[type=submit],input[type=submit]');
        if ( btn.length) {
            if ($.xo_validate.forms[ form_id].debug) console.log("xo_validate: found submit button for form");
            $.xo_validate.forms[ form_id].submit_btn = btn;
            btn.removeAttr('disabled');
            btn.click(function(e){
                if ($.xo_validate.forms[ form_id].debug) console.log("xo_validate: submit button clicked");
                e=e?e:window.event;
                e.preventDefault();
                if ($.xo_validate.forms[ form_id].onStart) $.xo_validate.forms[ form_id].onStart();
                $.xo_validate.submit_handler(e,$(this),form_id);
                return false;
            });
        }
        return true;
    };
})( jQuery );

// ajax editor module
$.xo_validate= {
    // need to save each form in its own JSON, so multiple can be supported
    "forms": {},
    "resources" : {
        "en": {
            "required": "This field is required",
            "email": "Invalid Email",
            "min": " Characters Minimum",
            "match": " Must Match ",
            "alphanum":"Must be alphanumeric",
            'alpha': 'Alphabetical characters only'
        },
        "es":{
            "required": "Este campo es obligatorio",
            "email": "Debe ser un correo electrónico",
            "min": " caracteres",
            "match": "Debe coincidir ",
            'alpha': 'caracteres alphanumericos solamente'
        }
    },
    "submit_handler":function(e,elem,form_id){
        if ($.xo_validate.forms[ form_id].debug) console.log("xo_validate: trapped submit button, prior to form submission");
        $.xo_validate.validate_fields(form_id);
    },
    // validate fields
    "validate_fields":function(form_id){
        //console.log("xo_validate: validating fields for "+form_id);

        var result = {
            result: true,
            errors: {

            },
            error_msgs:{

            }

        };
        // clear any errors
        var visible_elements = $('form#'+form_id+ ' .xo-validate:visible');
        visible_elements.removeClass('validation-error');
        // clear any notices
        $('div.notice').html('');
        // clear any checkboxes
        $('div.check-x').removeClass('error').hide();
        visible_elements.each( function(){
            var $this = $(this);
            var id = $this.attr('id');
            var chid = "ch_"+id;
            var chx = $('#'+chid);
            var fnid = "fn_"+id;
            var notice = $('#'+fnid);
            var classes = $this.attr('class');
            var val = $this.val();
            var title = $this.attr('title');
            //clear input error before validate. In this case all previous errors will be removed
            $.xo_validate.clear_error(id);
            if ($.xo_validate.forms[ form_id].debug) console.log("xo_validate: validating element id "+id+ " value "+ val);

            if ( ($this.hasClass('presence') || $this.hasClass('required')) && ! val.length){
                notice.html($.xo_validate.resources[ $.xo_validate.forms[ form_id].lang ]["required"]);
                chx.addClass('error').show();
                $this.addClass('validation-error');
                result.result = false;
                result.errors[id] = "presence";
                result.error_msgs[id] = $.xo_validate.resources[  $.xo_validate.forms[form_id].lang]['required'];
            }
            var matches = /depends-([a-z_]+)/.exec( classes);
            if ( matches){
                var val2 = $('#'+matches[1]).val();
                if ( ! val2.length && val.length){
                    $this.addClass('validation-error');
                    console.log( "error in "+id);
                    result.result = false;
                }
            }
            var matches = /min-([0-9]+)/.exec( classes);
            if ( matches){
                //console.log("found field with min rule");
                var min = parseInt( matches[1] );
                if ( val.length != 0 && val.length < min) {
                    if ($.xo_validate.debug) console.log("xo_validate: element failed min rule");

                    $this.addClass('validation-error');
                    //console.log( "error in "+id);
                    notice.html("min "+min+ $.xo_validate.resources[$.xo_validate.forms[ form_id].lang]["min"]);
                    chx.addClass('error').show();
                    result.result = false;
                    result.errors[id] = "min";
                    result.error_msgs[id] = min+ $.xo_validate.resources[  $.xo_validate.forms[form_id].lang]['min'];

                }
            }

            /**
             * Validate a zip code
             */
            if ( $this.hasClass('zip')){
                if ( ! /^\d{5}(-\d{4})?$/.test(val)) {
                    $this.addClass('validation-error');
                    notice.html('Not a valid US zip code');
                    chx.addClass('error').show();
                    result.result = false;
                    result.errors[id] = "card";
                    result.error_msgs[id] =  'Not a valid US zip code';
                }
            }

            /**
             * Validate a credit card
             */
            if ( $this.hasClass('card')){

                if ($.xo_validate.forms[ form_id].debug) console.log("xo_validate: validating credit card");

                if ( ! /^([3-6]){1}([0-9]){15}$/.test(val.replace(/\-/g,''))) {
                    $this.addClass('validation-error');
                    notice.html('Not a valid Credit Card number');
                    chx.addClass('error').show();
                    result.result = false;
                    result.errors[id] = "card";
                    result.error_msgs[id] =  'Not a valid Credit Card number';
                }
            }

            // must be alphabetical characters only
            if ( $this.hasClass('alpha')){
                if ( ! /^[a-z|A-Z\s]+$/.test(val)) {
                    $this.addClass('validation-error');
                    notice.html("min "+min+ $.xo_validate.resources[$.xo_validate.forms[ form_id].lang]["min"]);
                    chx.addClass('error').show();
                    result.result = false;
                    result.errors[id] = "alpha";
                    result.error_msgs[id] =  $.xo_validate.resources[$.xo_validate.forms[ form_id].lang]["alpha"];
                }
            }



            if ( $this.hasClass('alphanum')){
                if ( ! /^[a-z|A-Z|0-9]+$/.test(val)) {
                    $this.addClass('validation-error');
                    notice.html("min "+min+ $.xo_validate.resources[$.xo_validate.forms[ form_id].lang]["min"]);
                    chx.addClass('error').show();
                    result.result = false;
                    result.errors[id] = "alphanum";
                    result.error_msgs[id] =  $.xo_validate.resources[$.xo_validate.forms[ form_id].lang]["alphanum"];
                }
            }
            var matches = /match-([a-z_]+)/.exec( classes );
            if ( matches){
                if ( val.length && val != $('input#'+matches[1]).val()){
                    if ($.xo_validate.forms[ form_id].debug) console.log("xo_validate: element failed matches rule");

                    $this.addClass('validation-error');
                    $('#'+fnid).html($.xo_validate.resources[$.xo_validate.forms[ form_id].lang]["match"]+matches[1]);
                    $('#'+chid).addClass('error').show();
                    //console.log( "match error in "+id);
                    result.result = false;
                    result.errors[id] =  title+" must match "+matches[1];
                    result.error_msgs[id] =  title+ $.xo_validate.resources[$.xo_validate.forms[ form_id].lang]["match"]+ matches[1];
                }
            }

            if ( $this.hasClass('email')){
                if ($.xo_validate.forms[ form_id].debug) console.log("xo_validate: validating as email");

                if ( !$.xo_validate.valid_email(val)){
                    if ($.xo_validate.forms[ form_id].debug) console.log("xo_validate: invalid email");
                    $this.addClass('validation-error');
                    notice.html("Invalid email address");
                    chx.addClass('error').show();
                    result.result = false;
                    result.errors[id] = "email";
                    result.error_msgs[id] =  "Invalid email address";
                    /*$('#'+fnid).html($.xo_validate.resources[$.xo_validate.forms[ form_id].lang]["email"]);
                    $('#'+chid).addClass('error').show();
                    result.result = false;
                    result.error_msgs[id] =  $.xo_validate.resources[$.xo_validate.forms[ form_id].lang]["email"];
                    */
                }
            }

            if ( $this.hasClass('url')){
                if ( val && ! valid_url(val)){
                    $this.addClass('validation-error');
                    $('#'+fnid).html("debe ser sitio");
                    $('#'+chid).addClass('error').show();
                    result.result = false;
                }
            }
            if ( $this.hasClass('checked')){
                if ( ! $this.is(':checked')){
                    $this.addClass('validation-error');
                    $('#'+fnid).html("debe ser confirmado");
                    $('#'+chid).addClass('error').show();
                    result.result = false;
                }
            }
            if ( $this.hasClass('integer')){
                if ( ! /^([0-9]+)$/.test( val)) {
                    $this.addClass('validation-error');
                    $('#'+fnid).html("debe ser un número");
                    $('#'+chid).addClass('error').show();
                    result.result = false;
                    result.errors[id] =  "must be an integer";
                    result.error_msgs[id] =  "A whole number value is required";
                }
            }
        });
        if ($.xo_validate.forms[ form_id].onComplete)
            $.xo_validate.forms[ form_id].onComplete(result);
    },
   "clear_error":function (id) {
        $("#"+id).removeClass('error');
        $('span.'+id+'-notice').html('');
    },
    // valid email
    "valid_email": function( val){
        return ( /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/.test( val.toLowerCase()))?true:false;
    },
    "valid_username": function(val){
        return ( val.length >= 5 && val.length <= 20 && /^[a-z|0-9|\.|\-|_]+/.test( val.toLowerCase()));
    }
};


