define([
    'text!/templates/{{name}}.hbs',
    'channel',
    'xo-validate',
    'models/{{model}}'
],
    function(tmpl,Channel,XOValidate,Model) {
        return Backbone.View.extend({

            // Handle for existing fetch
            _fetch: null,

            // set name
            _name: '{{camelcase_name}}',

            // toggle debugging
            debug: false,

            // jQuery Events
            events: {

            },

            initialize: function( options ) {

                // Normalize...
                options = typeof(options)!='undefined'?options:{};

            },

            /**
             * Render the form
             * @returns {object} View
             */
            render: function() {

                var that = this;

                this.$el.html( Handlebars.compile( tmpl)(this));

                // Hang on...
                setTimeout(function(){

                    // Set up typeahead
                    that.$el.find('.typeahead').each( function(){

                        that.setup_typeahead( $(this) );

                    });

                    // Fetch values for Model Picklists
                    that.$el.find('.model-picklist').each( function(){

                        that.fetch_model_picklist( $(this) );

                    });

                    // Fetch values for Autogen fields
                    that.$el.find('.autogen').each( function(){

                        // Get and place value
                        that.get_autogen_value( $(this), $.xo.get_kid($(this),'key'));

                    });

                    // Forms Validation
                    that.$el.find('form').xo_validate({

                        // Language
                        lang: 'en',

                        // Before starting validation
                        onStart: function(){

                        },

                        // Once done, with result
                        onComplete: function(result){

                            // If successful
                            if ( result.result )

                                that.save_model();

                        }
                    });

                    /**
                     * Place here custom View setup required to be done
                     * once the template has been rendered
                     */

                },50);

                return this;
            },

            /**
             * Set up the typeahead module
             * @param {object} element
             */
            setup_typeahead: function( element ){

                console.log(element.attr('class'));

                var key = $.xo.get_kid(element, 'typeaheadkey');

                element.typeahead({
                        minLength: 1,
                        highlight: true,
                        hint: true
                    },
                    {
                        name: 'my-dataset',
                        source: this.typeahead_dataset_for(key)

                    });
            },

            typeahead_dataset_for: function( key ){

                console.log('typeahead key = '+key);

                var that = this;


                    // Return the find matches function
                    return function findMatches(q, cb) {

                        var matches, substrRegex;

                        // an array that will be populated with substring matches
                        matches = [];

                        // regex used to determine if a string contains the substring `q`
                        substrRegex = new RegExp(q, 'i');

                        // Load required Assets
                        require(['collections/'+key],function(Collection){
                            // Set the collection, to fetch only matches
                            var coll = new Collection({ query: q });

                            // If existing fetch
                            if ( that._fetch )

                            // Abort it
                                that._fetch.abort();

                            that._fetch = coll.fetch({

                                success: function(collection,result,options){

                                    // iterate through the pool of strings and for any string that
                                    // contains the substring `q`, add it to the `matches` array
                                    $.each(coll.models, function(i, model) {

                                        matches.push({ value: model.get('name') });

                                    });

                                    cb(matches);

                                }

                            });


                        });


                    };

            },

            /**
             * Fetch Model Picklist Values
             * @param {object} element
             */
            fetch_model_picklist: function( element ){

                var id = element.attr('id');

                if ( matches = /([a-z_]+)_id/.exec( id )){

                    var model_name = matches[1];

                    // Load assets
                    require(['collections/'+model_name],function(Collection){

                        var coll = new Collection;

                        coll.fetch({

                            success: function(collection,result,options){

                                $.each( collection.models,function(index,model){

                                    element.append('<option value"'+model.id+'">'+model.get('name')+'</option>');
                                });
                            }
                        });
                    });
                }
            },

            /**
             * Get an AutoGen Value for a form field
             * @param {object} element
             * @param {string} key
             */
            get_autogen_value: function( element, key ){

                $.ajax({

                    url: '/autogen/'+key,

                    success: function( value ){

                        element.val( value );

                    }
                });
            },

            /**             *
             * @returns {*} JSON of data from Form
             */
            get_data: function(){

                data = {};

                this.$el.find('.saveable-data').each(function(){

                    data[ $(this).attr('id')] = $(this).val();

                });

                return data;
            },

            /**
             * Save Model, using form values
             */
            save_model: function(){

                this.model = new Model;

                this.model.save( this.get_data(),{

                    success: function(model,result,options){


                    }
                })
            },


            get_name: function(){

                return this._name;
            },

            /**
             * Toggle lock/unlock the button
             */
            toggle_lock_button: function(){

                var button = this.$el.find('button[type="submit"]');

                if ( button.hasClass('locked')){

                    button.removeClass('locked').removeAttr('disabled').html('Guardar');
                } else {

                    button.addClass('locked').attr('disabled','disabled').html('Espera...');

                }

            }


        });
    }
);
