define([
    'text!/templates/{{name}}.hbs',
    'channel'
],
    function(tmpl,Channel) {
        return Backbone.View.extend({

            // CSS classname for Node
            className: '{{name}}',

            // TagName
            tagName: 'div',

            // set name
            _name: '{{name}}',

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
             * Render the view
             * @returns {object} View, for chained commands
             */
            render: function() {

                var that = this;

                // Optional method to fetch model before render
                this.fetch_model_or_collection( function(){

                    // Render the template
                    that.$el.html( Handlebars.compile( tmpl)(that));

                    // Hang on...
                    setTimeout(function(){

                        /**
                         * Place here custom View setup required to be done
                         * once the template has been rendered
                         */

                    },50);


                });

                return this;
            },

            /**
             * Fetch the Model
             * @param {function} callback
             */
            fetch_model_or_collection: function( callback ){

                // perform any model fetch here if needed
                // be sure to callback only after fetched!

                callback();

            },

            get_name: function(){

                return this._name;
            }
        });
    }
);
