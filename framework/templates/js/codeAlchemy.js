/**
 * Code Alchemy jQuery Module
 */
var codeAlchemy = (function() {

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
     * The image typeahead Object
     * @type {Object}
     */
    var imageTypeAhead = null;

    /**
     * Get bloodhound
     * @param type
     * @returns {Bloodhound}
     */
    var bloodHound = function( type ) {

        return new Bloodhound({
            name: 'search-results',
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: '/parnassus/bloodhound/'+type+'?q=a',
            remote: '/parnassus/bloodhound/'+type+'?q=%QUERY'
        });
    };

    /**
     * Get Typeahead
     * @param type
     * @param elem
     * @returns {*}
     */
    var getTypeahead = function ( type, elem ){

        console.log('getting typeahead for '+type);

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
                    displayKey: 'caption',
                    minLength: 3,
                    source: bloodhound.ttAdapter(),
                    templates: {
                        empty: [
                            '<div class="empty-message">',
                            'no matches',
                            '</div>'
                        ].join('\n'),
                        suggestion: Handlebars.compile('<div><img style="max-width:80px;" src="{{image_filename_url}}">{{name}}&nbsp;{{title}}&nbsp;{{caption}}</div>')
                    }


                }).on('typeahead:selected',function(event,suggestion,dataset){

                    $('.image-advisory').fadeIn('slow');

                    $('.edit-model-preview').attr('src',suggestion.image_filename_url);

                    $('input[name="website_image_id"]').val(suggestion.id);

                    $('input.image-typeahead').val(suggestion.caption?suggestion.caption:(suggestion.title?suggestion.title:(suggestion.name?suggestion.name:'')));

                });

        console.log(tobject);

        return tobject;


    };




    /**
     * Is validation active on the form?
     * @type {boolean}
     */
    var isFormValidated = false;

    var validateEmail = function(email) {

        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);

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

    var disablePreloader = function(){

        $('#preloader').hide();

        $('.navbar').show();

    };

    /**
     * Configure the typeahead
     */
    var configureImageTypeahead = function(){

        console.log('configuring image typeahead');

        imageTypeAhead = getTypeahead('website_image',$('.image-typeahead'));

        console.log(imageTypeAhead);

    };

    /**
     *
     * Initialize the application
     */
    var initialize = function(){

        // Get settings from server
        $.ajax({

            url: '/setup-management',

            success: function( json ){

                var language = json.language;

                if ( json.may_manage ){

                    var label = language == 'es' ? 'Editar': 'Edit';

                    // For every managed element
                    $('[data-code-alchemy-management="yes"]').each( function(){

                        var elem = $(this);

                        var suffix = elem.attr('data-code-alchemy-manage-label');

                        var label2 = suffix ? label + ' '+ suffix: label;

                        var url = elem.attr('data-code-alchemy-manage-url');

                        elem.prepend('<a target="_parnassus" class="btn btn-default ca-manage" href="'+url+'">'+label2+'</a>');

                        var buttonPosition = elem.attr('data-code-alchemy-button-position');

                        if ( buttonPosition ) {

                            position = buttonPosition.split(',');

                            elem.find('.ca-manage').css('top',position[0]+'px').css('left',position[1]+'px');
                        }

                        // For add models
                        var addModel = elem.attr('data-code-alchemy-add-model');

                        // If necessary
                        if ( addModel ){

                            var parts = addModel.split(',');

                            var label3 = language=='es'? 'Agregar '+ parts[1]: 'Add '+parts[1];

                            var url2 = '/parnassus/add/'+parts[0];

                            elem.find('.ca-manage').after('<a target="_parnassus" class="btn btn-default ca-manage-add" href="'+url2+'">'+label3+'</a>');

                        }

                        // For adding multiple
                        var addModels = elem.attr('data-code-alchemy-add-models');
                        // If necessary
                        if ( addModels ){

                            console.log('add Models');


                            var parts2 = addModels.split(';');

                            // for each one
                            for (var i in parts2 ){

                                console.log(parts2[i]);

                                var parts3 = parts2[i].split(',');

                                var label4 = language=='es'? 'Agregar '+ parts3[1]: 'Add '+parts3[1];

                                var url3 = '/parnassus/add/'+parts3[0];

                                elem.find('.ca-manage')

                                    .after(

                                    '<a target="_parnassus" class="btn btn-default ca-manage-add add-'+i+'" href="'+url3+'">'+label4+'</a>');


                            }

                        }

                        // If manage delete?
                        var delete_attr = elem.attr('data-code-alchemy-manage-delete');

                        if ( typeof(delete_attr) != 'undefined' && delete_attr.length ){

                            var parts4 = delete_attr.split('.');

                            var label5 = language=='es'? 'Borrar '+ suffix: 'Delete '+suffix;


                            elem.find('.ca-manage').after("<a onclick=\"return codeAlchemy.delete_model('"+parts4[0]+"','"+parts4[1]+"');\"  class=\"btn btn-default ca-manage-delete\" href=\"#\">"+label5+'</a>');

                            var delPost = elem.attr('data-code-alchemy-delete-position');

                            if ( typeof(delPost)!='undefined'){

                                position = delPost.split(',');

                                elem.find('.ca-manage-delete').css('top',position[0]+'px').css('left',position[1]+'px');


                            }

                        }

                        var custom = elem.attr('data-code-alchemy-custom-url');

                        var customlabel = elem.attr('data-code-alchemy-custom-label');

                        var customPos = elem.attr('data-code-alchemy-custom-position');

                        if ( typeof(custom)!= 'undefined'){


                            elem.find('.ca-manage').after(

                                '<a target="_parnassus" class="btn btn-default ca-manage-custom" href="'+custom+'">'+customlabel+'</a>'

                        );

                            position = customPos.split(',');

                            elem.find('.ca-manage-custom').css('top',position[0]+'px').css('left',position[1]+'px');

                        }


                    });


                }

            }

        });

        // Check for notifications
        checkForNotification();

    };

    /**
     * Remove a website image
     */
    var removeWebsiteImage = function( modelName, modelId ){

        $.ajax({

            type: 'POST',

            url: '/parnassus/remove_website_image',

            data: 'model='+modelName+'&id='+modelId,

            success: function(json){

                if ( json.result == 'success' ){

                    toastr.success('The image has been removed');

                    $('.image-preview-pane').hide();

                    $('input[name="website_image_id"]').val('');

                }

            }
        })
    };

    /**
     * Clear the typeahead
     * @returns {boolean}
     */
    var clearTypeahead = function(){

        $('.image-typeahead').val('');

        $('input[name="website_image_id"]').val('');

        return false;
    };

    /**
     * Toggle disable a form
     * @param jObjForm
     */
    var toggleDisableForm = function( jObjForm ){

        var btn = jObjForm.find('[type="submit"]');

        if ( btn.hasClass('disabled'))

            btn.removeClass('disabled').removeAttr('disabled');

        else

            btn.addClass('disabled').attr('disabled','disabled');

    };

    /**
     * Save a new Model
     * @param jObjForm
     * @param model_name
     * @param callback
     */
    var saveModel = function( jObjForm, model_name, callback ){

        toggleDisableForm( jObjForm );

        $.ajax({

            type: 'POST',

            url: '/rest/'+model_name,

            data: jObjForm.serialize(),

            success: function( model ){

                toggleDisableForm( jObjForm );

                callback( model );

            },

            error: function( jqxhr ){

                toggleDisableForm( jObjForm );

                callback( jqxhr );

            }

        });

    };

    /**
     * Update existing Model
     * @param jObjForm
     * @param model_name
     * @param model_id
     * @param callback
     */
    var updateModel = function( jObjForm, model_name, model_id, callback ){

        toggleDisableForm( jObjForm );

        $.ajax({

            type: 'POST',

            url: '/rest/'+model_name+'/'+model_id,

            data: jObjForm.serialize()+'&_PARNASSUS_SIMULATE_PUT=yes',

            success: function( model ){

                toggleDisableForm( jObjForm );

                callback( model );

            },

            error: function( jqxhr ){

                toggleDisableForm( jObjForm );

                callback( jqxhr );

            }

        });

    };


    /**
     * Update a Model without a form
     * @param {String} data
     * @param model_name
     * @param model_id
     * @param callback
     */
    var updateModelNoForm =

        function( data, model_name, model_id, callback ){

        $.ajax({

            type: 'POST',

            url: '/rest/'+model_name+'/'+model_id,

            data: data+'&_PARNASSUS_SIMULATE_PUT=yes',

            success: function( model ){

                callback( model );

            },

            error: function( jqxhr ){

                callback( jqxhr );

            }

        });

    };


    /**
     *
     */
    var checkForNotification = function(){

        setTimeout(function(){

            $.ajax({

                url: '/notification-check',

                success: function( json ){

                    if ( json.has_notification ){

                        $.each( json.notifications,function(index,notif){


                            switch( notif.type ){

                                case 'info':

                                    toastr.info(notif.message,notif.title,{

                                        timeOut: notif.timeout,


                                        preventDuplicates: true,

                                        allowDuplicates: false



                                    });

                                    break;

                                case 'success':

                                    toastr.success(notif.message,notif.title,{

                                        timeOut: notif.timeout,


                                        preventDuplicates: true,

                                        allowDuplicates: false



                                    });
                            }
                        });

                    }

                }
            });


        },3000);

    };

    /**
     * Fetch a model
     * @param model_name
     * @param id
     * @param callback
     */
    var fetchModel = function( model_name, id, callback ){

        $.ajax({

            url: '/rest/'+model_name+'/'+id,

            success: function( model ){


                callback( model );
            }

        });

    };

    /**
     * Fetch a model and replace a jQuery object with it, using a template
     * @param model_name
     * @param id
     * @param template_name
     * @param jObjElement
     * @param {function} fnCallback
     */
    var fetchModelAndReplace = function( model_name, id, template_name, jObjElement, fnCallback ){


        fetchModel( model_name, id, function( model ){

            if ( typeof(fnCallback) =='function')

                fnCallback();

            jObjElement.replaceWith( (Handlebars.compile($('#'+template_name).html()))( model ));



        });

    };

    /**
     * Fetch a model and append a jQuery object with it, using a template
     * @param model_name
     * @param id
     * @param template_name
     * @param jObjElement
     */
    var fetchModelAndAppend = function( model_name, id, template_name, jObjElement ){


        fetchModel( model_name, id, function( model ){

            jObjElement.append( (Handlebars.compile($('#'+template_name).html()))( model ));

        });

    };

    /**
     * Delete a Model
     * @param model_name
     * @param id
     */
    var deleteModel = function( model_name, id ){

        $.ajax({

            type: 'DELETE',

            url: '/rest/'+model_name+'/'+id,

            success: function( model ){

                window.location.reload();

            }
        });

        return false;

    };

    /**
     * Handle a Model save result
     * @param model
     * @param jObjForm
     * @param callback
     */
    var handleSaveResult = function( model, jObjForm, callback ){

        if ( typeof(model.error)!='undefined' && model.error ){

            toastr.error( model.error, 'Error',{

                preventDuplicates: true,

                timeOut: 10000

            });

            $.each(model.codeAlchemy_data.missing_fields,function(index,field){

                jObjForm.find('[name="'+field+'"]').addClass('has-error');

            });


        } else callback( model );

    };

    /**
     * Get some models!
     * @param model_name
     * @param query_string
     * @param callback
     */
    var getModels = function( model_name, query_string, callback ){

        $.ajax({

            url: '/rest/'+model_name+'?'+ query_string,

            success: function( models ){

                callback( models );
            }

        })

    };

    // Public API
    return {

        initialize: initialize,

        check_for_notifications: checkForNotification,

        disable_preloader: disablePreloader,

        clear_website_image_typeahead: clearTypeahead,

        remove_website_image: removeWebsiteImage,

        toggle_disable_form: toggleDisableForm,

        save_model: saveModel,

        update_model: updateModel,

        update_model_no_form: updateModelNoForm,

        check_for_notification: checkForNotification,

        fetch_model: fetchModel,

        fetch_model_and_replace: fetchModelAndReplace,

        fetch_model_and_append: fetchModelAndAppend,

        delete_model: deleteModel,

        handle_save_result: handleSaveResult,

        get_models: getModels,

        validate_email: validateEmail

    };

})();
