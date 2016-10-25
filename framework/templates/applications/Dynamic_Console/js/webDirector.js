/**
 * Web Director Module
 */
var webDirector = (function() {

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
    var imageTypeAhead = [];

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

        var fieldName = elem.attr('data-field-name');

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
                    name: 'search-results-'+fieldName,
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

                var value = suggestion.id?suggestion.id:suggestion.website_image_id;

                console.log('webDirector: Setting Website Image field '+fieldName+' to value ' + value);

                $('input[name="'+fieldName+'"]').val(value);

                    $('input.image-typeahead[data-field-name="'+fieldName+'"]').val(suggestion.caption?suggestion.caption:(suggestion.title?suggestion.title:(suggestion.name?suggestion.name:'')));

                });

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

        // For each eligible element
        $('.image-typeahead').each(function(){

            var element = $(this);

            imageTypeAhead.push( getTypeahead('website_image',element));

        });

    };

    /**
     *
     * Initialize the application
     */
    var initialize = function(){

        // Get Model Hierarchy
        $.ajax({

            url: '/parnassus/model-hierarchy',

            success: function( data ){
                // Load jsTree async
                $script('/js/tree.jquery.js',function(){


                    $('#jstree-navigation').tree({

                        data:data,

                        autoEscape: false,

                        closedIcon: $('<i class="fa fa-arrow-circle-right"></i>'),
                        openedIcon: $('<i class="fa fa-arrow-circle-down"></i>')                    });

                });


            }
        });

        // Get Business Processes
        if ( $('body').hasClass('search-home'))

            $.ajax({

                url: '/parnassus/business-processes',

                success: function(json){

                    // if we have some processes
                    if ( json.length > 0 ){

                        var source   = $("#business-processes").html();

                        var template = Handlebars.compile(source);

                        $('a[name="business-services"]').after( template(json) );
                    }

                }
            });

            <!-- Menu Toggle Script -->
        $("#menu-toggle").click(function(e) {

            e.preventDefault();

            $("#wrapper").toggleClass("toggled");

        });

        // if we have an image typeahead
        if ( $('.image-typeahead').length )

            // Configure'em
            configureImageTypeahead();

    };

    /**
     * Remove a website image
     */
    var removeWebsiteImage = function( modelName, modelId, fieldName ){

        $.ajax({

            type: 'POST',

            url: '/parnassus/remove_website_image',

            data: 'model='+modelName+'&id='+modelId,

            success: function(json){

                if ( json.result == 'success' ){

                    toastr.success('The image has been removed');

                    $('.image-preview-pane').hide();

                    $('input[name="'+fieldName+'"]').val('');

                }

            }
        })
    };

    /**
     * Clear the typeahead
     * @returns {boolean}
     */
    var clearTypeahead = function( fieldName ){

        $('.image-typeahead').val('');

        $('input[name="'+fieldName+'"]').val('');

        return false;
    };

    // Public API
    return {

        initialize: initialize,

        disable_preloader: disablePreloader,

        clear_website_image_typeahead: clearTypeahead,

        remove_website_image: removeWebsiteImage

    };

})();
