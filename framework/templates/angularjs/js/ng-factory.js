// Create a factory for fetching models
angular.module('__angular_factory__',[]).factory('__angular_factory__',
    [ '$http', function(http){

    // Now get the factory methods
    return {
        list: function (modelName,callback){
            http({
                method: 'GET',
                url: '/api/v1/'+modelName,
                cache: true
            }).success(callback);
        },
        find: function(id, modelName,callback){
            http({
                method: 'GET',
                url: '/ap1/v1/'+modelName+'/'+id,
                cache: true
            }).success(callback);
        }
    };
}]);


