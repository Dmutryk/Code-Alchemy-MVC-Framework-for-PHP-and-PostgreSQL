define([

    // Handlebars Template for all kinds of alerts
    'text!/templates/bootstrap-alert.hbs',

    // Allows for app-wide global events
    'channel'

],
    function(tmpl,Channel) {
        return Backbone.View.extend({

            // Set a timeout to remove alert after displaying
            timeout: 0,

            // Required Bootstrap style and behavior classes
            className: 'alert alert-dismissable alert-danger',

            // Stores user messages
            messages: [],

            // Put BackboneJS events here
            events: {},

            // Initialize the Alert
            initialize: function( options ) {

                var that = this;

                // Channel event to remove alerts
                Channel.on('remove.success.alerts',function(){

                    that.remove();

                });

                // ibid
                Channel.on('remove.alerts',function(){

                    that.remove();

                });

                // set some defaults
                this.timeout = options && typeof(options.timeout)!='undefined'? options.timeout: 0;

                this.messages['strong'] = options && options.strong_message ? options.strong_message:'';

                this.messages['standard'] = options && options.message ? options.message :'';

            },

            // Get the strong message
            strong_message: function(){ return this.messages['strong']; },

            // Get the standard message
            message: function(){ return this.messages['standard']; },


            render: function() {

                var that = this;

                this.$el.html( Handlebars.compile( tmpl)(this));

                if ( this.timeout )

                    setTimeout(function(){

                        that.$el.fadeOut('slow',function(){

                            that.remove();

                        });
                    },this.timeout);
                return this;
            }
        });
    }
);
