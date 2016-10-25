define([
    '__module_name__'
],function(__module_name__){

    var directive = __module_name__.directive(
        '__directive_name__',
        function(){
            return {
                restrict: 'E',

                controller: '__controller_name__',
                link: function(scope,element,attrs,controller){

                },
                templateUrl: '/templates/__template_name__.hbs'
            };
        });

    return directive;

});
