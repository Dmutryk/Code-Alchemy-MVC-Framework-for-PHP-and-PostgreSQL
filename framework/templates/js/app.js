 define([
     'router',
     'globals',
     'channel'
     ], function(AppRouter,Globals,Channel){
     var initialize = function(){
         var debug = false;
         var $this = this;

         /** Globally capture clicks. If they are internal and not in the pass
          *  through list, route them through Backbone's navigate method.
          */
         $(document).on('click',"a[href^='/']",function(event){

             var href = $(event.currentTarget).attr('href');

             //chain 'or's for other black list routes
             var passThrough = href.indexOf('sign_out') >= 0;

             //Allow shift+click for new tabs, etc.
             if ( !passThrough && !event.altKey && !event.ctrlKey && !event.metaKey && !event.shiftKey)

                 event.preventDefault();

             // Remove leading slashes and hash bangs (backward compatablility)
             var url = href.replace(/^\//,'').replace('\#\!\/','');

             console.log(url);

             // Instruct Backbone to trigger routing events
             Channel.trigger('navigate.to',url);

             return false;

         });


         AppRouter.initialize();

         $(document).ready(function(){

         });
  };
     return {
         initialize: initialize
     };
     // What we return here will be used by other modules
});