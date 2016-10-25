define([
    'text!/templates/{{name}}.hbs',
    'channel',
    'xo-validate',
    'models/{{model}}'
],
    function(tmpl,Channel,XOValidate,Model) {
        return Backbone.View.extend({

            // set name
            _name: '{{camelcase_name}}',

            // toggle debugging
            debug: true,

            // jQuery Events
            events: {
            },
            initialize: function( options ) {

                // Normalize...
                options = typeof(options)!='undefined'?options:{};

            },

            render: function() {

                var that = this;

                this.$el.html( Handlebars.compile( tmpl)(this));

                // Hang on...
                setTimeout(function(){

                    // Forms Validation
                    that.$el.find('form').xo_validate({

                        // Language
                        lang: 'en',

                        // Before starting validation
                        onStart: function(){

                            that.$el.find('.xo-validate').removeClass('error');

                            Channel.trigger('remove.alerts');

                        },

                        // Once done, with result
                        onComplete: function(result){

                            // If successful
                            if ( result.result )

                                that.save_model();

                            else

                                that.show_errors( result );

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
             * Show errors
             * @param json
             */
            show_errors: function( json ){

                var that = this;

                var message = '';

                // Compose message
                $.each( json.error_msgs, function(id,msg){

                    var name = that.$el.find('label[for="'+id+'"]').html();

                    that.$el.find('#'+id).addClass('error');

                    message = message + name+': '+msg+'. ';

                });
                // Load assets
                require(['views/error-alert'],function(Alert){

                    var alert = new Alert({
                        strong_message: 'Oops!',
                        message: message,
                        timeout: 5000
                    });

                    that.$el.find('.alerts-here').append(alert.render().el );

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

                if ( this.debug ) console.log(data);

                return data;
            },

            /**
             * Save Model, using form values
             */
            save_model: function(){

                var that = this;

                this.model = new Model;

                this.model.save( this.get_data(),{

                    success: function(model,result,options){

                        // If successful
                        if ( typeof(result.id)!='undefined'){

                            // Slide out controls
                            that.$el.find('.slide-toggle').slideToggle();

                            that.$el.find('legend,button').fadeOut('med');

                            // Load assets
                            require(['views/success-alert'],function(Alert){

                                var alert = new Alert({
                                    strong_message: 'Yes!',
                                    message: 'Your account has been created'
                                });

                                that.$el.find('.alerts-here').append( alert.render().el );

                            });

                        } else {

                            // Load assets
                            require(['views/error-alert'],function(Alert){

                                var alert = new Alert({
                                    timeout: 6000,
                                    strong_message: 'oops!',
                                    message: result.error
                                });

                                that.$el.find('.alerts-here').append( alert.render().el );


                            });
                        }

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
