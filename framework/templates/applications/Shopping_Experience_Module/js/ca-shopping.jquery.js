/**
 * Code Alchemy Shopping Module
 *
 * Version: 1.0.0
 *
 * (c) 2015 Alquemedia SAS, all rights reserved
 *
 * Author: David Greenberg <david@alquemedia.com>
 *
 */
var caShopping = (function() {

    /**
     * Settings
     * @type {{}}
     */
    var settings = {

    };

    /**
     * Whether or not to debug the app
     * @type {boolean}
     */
    var debug = false;

    /**
     * The typeahead Object
     * @type {Object}
     */
    var typeAhead = null;

    // What callback to make after logging in
    var callbackAfterLogin = '';

    /**
     * Get bloodhound
     * @param type
     * @returns {Bloodhound}
     */
    var bloodHound = function( type ) {

        return new Bloodhound({
            name: 'search-results',
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: '/bloodhound/'+type+'?q=%QUERY'
        });
    };

    /**
     * Get Typeahead
     * @param type
     * @param elem
     * @returns {*}
     */
    var getTypeahead = function ( type, elem ){

        var bloodhound = bloodHound( type );

        bloodhound.initialize();

        tobject =

            // Enable type-ahead
            elem.typeahead({
                    hint: true,
                    hightlight: true,
                    minLength: 1
                },
                {
                    name: 'search-results',
                    displayKey: 'name',
                    minLength: 1,
                    source: bloodhound.ttAdapter()

                }).on('typeahead:selected',function(event,suggestion,dataset){

                    console.log(suggestion);

                });

        return tobject;


    };

    /**
     *
     * @param string
     * @returns {{}}
     */
    var unserialize = function( string ){

        var data = {};

        var split = string.split('&');

        for ( var index in  split ){

            var pair = split[index].split('=');

            data[ pair[0] ] = decodeURIComponent(pair[1]);
        }

        return data;

    };

    /**
     * Update Cart on the UI
     * @param cart
     * @param store_item_id
     */
    var updateCartOnUI = function( cart, store_item_id ){

        // Reload Cart
        var source   = $("#mini-cart").html();

        var template = Handlebars.compile(source);

        // Is mini cart visible
        var shopping_cart = $('.replace-me');

        shopping_cart.replaceWith( template(cart) );


        // Update button label
        var label = $('span.button-label[data-store-item-id="' + store_item_id + '"]');

        label.html( label.html()=='Agregar'?'Quitar':'Agregar');

        // Update review cart
        var review = $('.review-cart');

        if ( review.length ){

            // For each item
            $.each(cart.items,function(index,item){

                console.log(item);

                var item_row = $('tr.cart-item[data-item-id="'+item.item_id+'"]');

                if ( item_row.length ){

                    item_row.find('td.formatted-item-total').html( item.formatted_total );
                }

            });

            // Update total for cart
            $('.display-total').html( cart.display_total );

            // refresh ready status
            var is_ready = (cart.total >= cart.store_settings.minimum_order);

            review.attr('data-is-ready', is_ready?"1":"");

            var alert_container = review.find('.alert-container');

            // refresh alert view
            if ( is_ready ) {

                alert_container.addClass('hidden');
            } else

                alert_container.removeClass('hidden');




        }

    };

    /**
     * Add an Item to the Cart
     * @param store_item_id
     * @returns {boolean}
     */
    var addToCart = function( store_item_id, button ){

        button.addClass('loading-cart').attr('disabled','disabled');

        // Add to Cart
        $.ajax({

            url: '/agregar-al-carrito/',

            data: 'action=toggle&item_id='+store_item_id,

            success: function( json ){

                button.removeClass('loading-cart').removeAttr('disabled');

                var result = true;

                if ( json.is_added ){

                    console.log( store_item_id + ' was added');


                } else if ( json.is_removed ){

                    console.log( store_item_id + ' was removed');

                    // Remove any cart items if present
                    var item = $('tr.cart-item[data-item-id="'+store_item_id+'"]');

                    if ( item.length )

                        item.fadeOut('med',function(){

                            item.remove();

                        });

                } else {

                    result = false;

                    toastr.error("Se ocurrió un error: "+json.error+ '. Puedes intentar de nuevo más tarde.','Error',{

                        timeOut: 6000,

                        preventDuplicates: true

                    });
                }

                if ( result ) {

                    updateCartOnUI( json.cart, store_item_id );
                }
            }
        });



        return false;

    };

    /**
     * Scroll to a specific location
     * @param jqSelector
     * @param {NUmber} offset
     * @returns {boolean}
     */
    var scrollTo = function( jqSelector, offset ){

        $('html, body').animate({
            scrollTop: $(jqSelector).offset().top + offset
        }, 2000);

        return false;

    };


    /**
     * Return the miniCart
     */
    var miniCart = function(){

        return $('.shopping-cart-view');

    };

    var shoppingCart = function(){

        return $('#shopping-cart');

    };

    /**
     * Open the full Cart
     */
    var openCart = function(){

        miniCart().hide();

        shoppingCart().modal('show');

        $.ajax({

            type: 'POST',

            url: '/view-cart',

            success: function( json ){

                shoppingCart().find('.modal-body').removeClass('loading-cart');

                var source   = $("#shopping-cart-templ").html();

                var template = Handlebars.compile(source);

                $('.cart-here').html( template(json.cart) );

            }

        });

        return false;

    };

    /**
     * Stow carts
     */
    var stowCarts = function(){

        // Hide Cart
        $('#shopping-cart').modal('hide');

        // Clear cart and reset status
        $('.cart-here').empty();

        shoppingCart().find('.modal-body').addClass('loading-cart');

        miniCart().hide();


    };

    /**
     * Check the Signin State of current user
     * @param callback
     */
    var checkSigninState = function( callback ){

        $.ajax({

            url: '/check-signin-state',

            success: function( json ){

                callback( json );

            }
        })

    };

    /**
     * Check if the user has been confirmed
     * @param callback
     */
    var checkIfConfirmed = function( callback ){

        $.ajax({

            url: '/check-confirmed',

            success: function(json){

                callback( json );

            }
        });

    };

    /**
     * Go to the checkout Window
     */
    var goToCheckout = function(){

        checkIfConfirmed(function(result){

            if ( result.is_verified )

                window.location.href = '/checkout';

            else

                $('#confirm-email').modal('show');

        });


    };

    /**
     * Shows the Checkout
     * @returns {boolean}
     */
    var showCheckout = function(){

        // Hide Shopping Carts
        stowCarts();

        // Set callback after login
        callbackAfterLogin = 'goToCheckout';

        // First check we are logged in
        checkSigninState( function( result ){

            // If not logged in
            if ( ! result.is_signed_in )

                // Show Modal to sign in or sign up
                $('#signin-signup').modal('show');

            else{

                goToCheckout();

            }


        });

        return false;

    };

    var signupModal = function(){ return $('#signin-signup'); };

    /**
     * Open up the Signup Form
     * @returns {boolean}
     */
    var openSignup = function(){

        if ( ! signupModal().is(":visible") )

            signupModal().modal('show');

        $('#login-form').fadeOut('fast',function(){

            $('#register-form').show();

        });

        return false;

    };

    var showSignin = function(){

        if ( ! signupModal().is(":visible") )

            signupModal().modal('show');



        $('#register-form').fadeOut('fast',function(){

            $('#login-form').show();

        });

    };

    /**
     * Register a new User
     * @param jObjForm
     * @returns {boolean}
     */
    var register = function( jObjForm ){

        $('input').removeClass('field-error');

        $.ajax({

            type: 'POST',

            url: '/rest/user',

            data: jObjForm.serialize(),

            success: function( json ){

                if ( typeof(json.error)!='undefined' && json.error.length ){

                    toastr.error(json.error,'Se occurió un error',{

                        timeOut: 10000,

                        preventDuplicates: true,

                        allowDuplicates: false

                    });

                    $.each(json.codeAlchemy_data.missing_fields,function(index,field){

                        $('input[name="'+field+'"]').addClass('field-error');

                    });

                } else {

                    checkSigninState(function( json ){

                        if ( json.is_signed_in ){

                            // When callback is set
                            if ( typeof(callbackAfterLogin)=='function')

                                callbackAfterLogin();

                            else

                                window.location.reload();



                        } else {

                            showSignin();
                        }

                    });

                }

            }

        });

        return false;

    };

    /**
     * Ajax for last change quantity
     * @type {null}
     */
    var changeQuantityAjax = null;

    var changeQuantityFor = function( store_item_id, new_quantity ){

        // if Valid
        if ( ! isNaN( new_quantity ) ){

            // Ajax already running?
            if ( changeQuantityAjax )

            // cancel
                changeQuantityAjax.abort();

            // Change via Ajax
            $.ajax({

                type: 'POST',

                url: '/update-quantity',

                data: 'action=update_quantity&item_id='+store_item_id+'&quantity='+new_quantity,

                success: function( json ){

                    if (json.quantity_updated ){

                        // Update UI accordingly
                        updateCartOnUI( json.cart, store_item_id );

                    } else toastr.error(json.error,'Se occurió un error',{

                        timeOut: 10000,

                        allowDuplicates: false,

                        preventDuplicates: true

                    });

                }

            });


        }
    };

    /**
     * Return an Item from the Cart
     * @param {Number} item_id
     * @param {Object} jObjButton
     * @returns {boolean}
     */
    var removeFromCart = function( item_id, jObjButton ){

        // Use existing function
        addToCart( item_id, jObjButton );

        return false;

    };

    var checkForNotification = function(){

        setTimeout(function(){

            $.ajax({

                url: '/notification-check',

                success: function( json ){

                    if ( json.has_notification ){

                        switch( json.type ){

                            case 'info':

                                toastr.info(json.message,json.title,{

                                    timeOut: json.timeout,


                                    preventDuplicates: true,

                                    allowDuplicates: false



                                });

                                break;

                            case 'success':

                                toastr.success(json.message,json.title,{

                                    timeOut: json.timeout,


                                    preventDuplicates: true,

                                    allowDuplicates: false



                                });
                        }
                    }

                }
            });


        },3000);

    };

    /**
     * Initialize app
     */
    var initialize = function(){

        var matching_elems = $('.checkbox-picker');

        if ( matching_elems.length )

            matching_elems.checkboxpicker({

                offLabel: 'No',

                onLabel: 'Sí'

        }).change(function() {

            var num_accepted = $('.required-acceptance:checked').length;

            $('.step-4').attr('data-is-ready', !! num_accepted );

        });

        // Check for a notification
        checkForNotification();

        // If input on page, set typeahead
        var $input = $('input.typeahead');

        if ( $input.length )

            typeAhead  = getTypeahead('store_item', $input);

        var ddslick = $('.ddslick');

        var firstTime = true;

        if ( ddslick.length )

            ddslick.ddslick({

                height: 300,

                onSelected: function(data){

                    if ( firstTime )

                        firstTime = false;

                    else

                        window.location.href = '/tienda/'+data.selectedData.value;

                }

            });

    };

    /**
     * Do a login
     * @param jObjForm
     * @returns {boolean}
     */
    var doLogin = function( jObjForm ){

        $.ajax({

            url: '/acceder',

            data: jObjForm.serialize(),

            success: function( json ){

                if ( json.is_logged_in ){

                    window.location.reload();

                }
            }

        });

        return false;

    };

    /**
     * Show form to add an address
     */
    var showAddAddress = function(){

        $('.add-address').fadeIn('slow');

    };

    var saveAddress = function( jObjForm ){

        // Fetch address Id for updates
        var address_id = $('[name="address_id"]').val()?$('[name="address_id"]').val():null;

        // Lock button to prevent duplicates
        var button = jObjForm.find('button[type="submit"]');

        button.prop('disabled',true);

        $('input,select,textarea').removeClass('field-error');

        $.ajax({

            type: 'POST',

            data: (address_id ? '_PARNASSUS_SIMULATE_PUT=1&'+jObjForm.serialize(): jObjForm.serialize()),

            url: '/rest/delivery_address'+ (address_id? '/'+address_id:''),

            success: function( json ){

                button.prop('disabled',false);

                if ( typeof( json.id) !='undefined' && json.id && ( typeof(json.error)=='undefined'|| ! json.error) ){

                    toastr.success("La Dirección ha sido guardado exitosamente",
                    "Dirección Guardada",{

                            timeOut: 10000,

                            preventDuplicates: true,

                            allowDuplicates: false




                        });

                    // If new
                    // Add to select
                    if ( ! address_id ) $('#choose-address').append('<option value="'+json.id+'">'+json.address_name+'</option>');


                } else {

                    toastr.error(json.error,'Dirección no Guardada',{

                        timeOut: 6000,


                        preventDuplicates: true,

                        allowDuplicates: false



                    });

                    $.each(json.codeAlchemy_data.missing_fields,function(index,field){

                        $('[name="'+field+'"]').addClass('field-error');

                    });
                }

            }

        });

        return false;

    };

    /**
     * Place an order
     * @param jObjForm
     * @returns {boolean}
     */
    var placeOrder = function( jObjForm ){

        console.log('Placing Order');

        var is_ready = true;

        var button = jObjForm.find('[type="submit"]');

        button.prop('disabled',true).html('espera').addClass('placing-order');

        // Check each step
        for (var step =1; step <= 4; step++){

            var css_class = "step-"+step;

            var jQSelector = '.' + css_class;

            var element = $(jQSelector);

            var ready_flag = !! element.attr('data-is-ready');

            if ( ! ready_flag ){

                console.log( 'Step '+step+' is not ready');

                is_ready = false;

                var action = element.attr('data-unready-action');

                switch( action ){

                    case 'confirm':

                        $('#confirm-email').modal('show');

                        break;

                    case 'login':

                        showSignin();

                    break;

                    case 'scroll-here':

                        scrollTo( jQSelector, -250 );

                        toastr.warning(element.attr('data-unready-message'),'Faltan Pasos',{

                            timeOut: 10000,


                            preventDuplicates: true,

                            allowDuplicates: false



                        });

                    break;

                }

                break;

            }
        }

        if ( is_ready ){

            $.ajax({

                type: 'POST',

                url: '/realizar-pedido',

                data: 'delivery_address_id='+$('#choose-address').val(),

                success: function ( json ){

                    button.prop('disabled',false).html('realizar pedido').removeClass('placing-order');


                    if ( json.result == 'success'){

                        window.location.href = '/gracias-por-tu-compra'

                    } else {

                        var alert = $('.order-alert');

                        alert.find('.message').html( json.error );

                        alert.fadeIn('fast');

                        toastr.error(json.error,'Se ocurrió un error',{

                            timeOut: 10000

                        });

                    }


                },
                error: function(){

                    button.prop('disabled',false).html('realizar pedido').removeClass('placing-order');

                }

            });

        } else {

            button.prop('disabled',false).html('realizar pedido').removeClass('placing-order');

        }

        return false;

    };

    /**
     * Select an address
     * @param jObjSelect
     */
    var selectAddress = function( jObjSelect ){

        // Fetch address from server
        $.ajax({

            url: '/rest/delivery_address/'+jObjSelect.val(),

            type: 'GET',

            success: function( address ){

                var div = $('.add-address');

                $.each(address,function(name,value){

                    $('[name="'+name+'"]').val(value);

                });

                $('[name="address_id"]').val(address.id);

                $('button.delete-address').attr('data-address-id',address.id).show();

                div.fadeIn('fast');

                // Enable Step
                if ( address.id)

                    $('.step-3').attr('data-is-ready',true);



            }
        });

    };

    var deleteAddress = function( button ){

        button.html('espera').prop('disabled',true);

        $.ajax({

            type: 'DELETE',

            url: '/rest/delivery_address/'+button.attr('data-address-id'),

            success: function( address ){

                button.html('borrar dirección').prop('disabled',false);

                if ( address.is_deleted ){

                    // remove from select
                    $('#choose-address').find('option[value="'+address.id+'"]').remove();

                    // Clear fields
                    var add = $('.add-address');

                    add.find('.clearable').val('');

                    // reset button
                    button.attr('data-address-id','').hide();

                    // hide edit
                    add.fadeOut('fast',function(){

                        // Scroll back to top
                        scrollTo('.step-3',-250);

                    });
                }


            }
        })

    };

    /**
     * Show confirmation modal
     * @returns {boolean}
     */
    var showConfirmationModal = function(){

        $('#confirm-email').modal('show');

        return false;

    };

    /**
     * Resend email confirmation
     * @returns {boolean}
     */
    var resendEmailConfirmation = function(){

        $.ajax({

            url: '/enviar-correo-de-confirmacion',

            success: function(json){

                if ( json.result == 'success' && ! json.error ){

                    toastr.success("Revisa tu bandeja de entrada de correo y sigue las instrucciones contenidas en en mensaje.  Si no recibes el correo, revisa bien tu bandejad de correo no deseado, o Spam/Junk",
                    'Código de Confirmación Enviado',{

                            timeOut: 15000

                        });
                } else

                    toastr.error(json.error,
                        'Se Occurió un Error',{

                            timeOut: 8000

                        });


            }
        });

        return false;

    };

    /**
     * Confirm User's email
     * @param jObjForm
     * @returns {boolean}
     */
    var confirmEmail = function( jObjForm ){

        var button = jObjForm.find('[type="submit"]');

        button.prop('disabled',true).html('Espera');

        $.ajax({

            type: 'POST',

            data: jObjForm.serialize(),

            url: '/confirmar-correo',

            success: function( json ){

                if ( json.is_verified ){

                    button.prop('disabled',false).removeClass('btn-primary').addClass('btn-success').html('¡Confirmado!');

                    window.location.reload();

                } else{

                    button.prop('disabled',false).html('¡confirmar mi correo ya!');

                    toastr.error(json.error,'Error en Confirmación',{

                        timeOut: 10000

                    });


                }

            }
        });

        return false;
    };


    /**
     * Show the mini cart
     * @returns {boolean}
     */
    var showMiniCart = function(){

        if ( miniCart().length && miniCart().is(":hidden") )

            miniCart().fadeIn('fast');

        else if ( miniCart().length && miniCart().is(":visible") )

            miniCart().hide();

        return false;

    };

    // Public API
    return {

        initialize: initialize,

        add_to_cart: addToCart,

        scroll_to: scrollTo,

        open_cart: openCart,

        show_checkout: showCheckout,

        open_signup: openSignup,

        register: register,

        remove_from_cart: removeFromCart,

        change_quantity_for: changeQuantityFor,

        show_signin: showSignin,

        do_login: doLogin,

        show_add_address: showAddAddress,

        save_address: saveAddress,

        place_order: placeOrder,

        select_address: selectAddress,

        delete_address: deleteAddress,

        show_confirmation_modal: showConfirmationModal,

        resend_email_confirmation: resendEmailConfirmation,

        confirm_email: confirmEmail,

        show_mini_cart: showMiniCart
    };

})();
