// Filename: router.js
 define([
     'views/welcome'
     ], function(
        Welcome
     ){
        var AppRouter = Backbone.Router.extend({
            debug: false,
            routes: {
                'welcome':'welcome',
                '*actions': 'defaultAction'
            },
            welcome: function(){
                var view = new Welcome({
                    el: $('div.content-container')
                });
            }

        });
        var initialize = function(){

            var app_router = new AppRouter;

            app_router.on('route:defaultAction', function(actions){

                console.log(actions);

                if ( ! actions ) app_router.welcome();

            });

            // We will use pushState, instead of #!
            Backbone.history.start({ pushState: true });

     };
 return {
     initialize: initialize
    };
 });