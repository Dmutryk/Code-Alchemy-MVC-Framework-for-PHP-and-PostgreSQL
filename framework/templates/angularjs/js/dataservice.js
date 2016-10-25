
app.factory('dataservice', ['$http','$window',function dataservice($http,$window) {

    // enable CORS
    $http.defaults.useXDomain = true;

    delete $http.defaults.headers.common['X-Requested-With'];

    return {

        url: url,

        base_url: base_url,

        getModels: getModels,

        getModel: getModel,

        addModel: addModel,

        findModel: findModel,

        findModels: findModels,

        deleteModel: deleteModel,

        putModel: putModel

    };

    /**
     * Get the base URL
     * @returns {string}
     */
    function base_url(){

        return /localhost/.test($window.location.href) ?

            'http://class-rest.localhost/':
            'http://class-rest.parnassusframework.com/'

            ;
    }

    /**
     * Get the base URL
     * @returns {string}
     */
    function url(){

        return base_url()+'rest/';

    }

    /**
     * Gets a Model
     * @param {string} modelName
     * @param {Number} modelId
     * @returns {*}
     */
    function getModel( modelName, modelId ) {

        return $http.get( url() + modelName+ '/'+modelId )

            .then(getModelComplete)

            .catch(getModelFailed);

        /**
         * Promise upon completing
         * @param response
         * @returns {*}
         */
        function getModelComplete(response) {

            return response.data;

        }

        /**
         * Error handler
         * @param error
         */
        function getModelFailed(error) {

            return [ error ];

        }
    }

    /**
     * Puts a Model
     * @param {string} modelName
     * @param {Number} modelId
     * @param {Object} values
     * @returns {*}
     */
    function putModel( modelName, modelId, values ) {

        // For Code Alchemy
        values['_PARNASSUS_SIMULATE_PUT'] = true;

        return $http.post( url() + modelName+ '/'+modelId,values )

            .then(putModelComplete)

            .catch(putModelFailed);

        /**
         * Promise upon completing
         * @param response
         * @returns {*}
         */
        function putModelComplete(response) {

            return response.data;

        }

        /**
         * Error handler
         * @param error
         */
        function putModelFailed(error) {

            return [ error ];

        }
    }


    /**
     * Delete a Model
     * @param {string} modelName
     * @param {Number} modelId
     * @returns {*}
     */
    function deleteModel( modelName, modelId ) {

        return $http.delete( url() + modelName+ '/'+modelId )

            .then(deleteModelComplete)

            .catch(deleteModelFailed);

        /**
         * Promise upon completing
         * @param response
         * @returns {*}
         */
        function deleteModelComplete(response) {

            return response.data;

        }

        /**
         * Error handler
         * @param error
         */
        function deleteModelFailed(error) {

            return [ error ];

        }
    }


    /**
     * Find a Model
     * @param {string} modelName
     * @param {string} customSearch
     * @returns {*}
     */
    function findModel( modelName, customSearch) {

        return $http.get( url() + modelName+ '/'+customSearch )

            .then(findModelComplete)

            .catch(findModelFailed);

        /**
         * Promise upon completing
         * @param response
         * @returns {*}
         */
        function findModelComplete(response) {

            return response.data;

        }

        /**
         * Error handler
         * @param error
         */
        function findModelFailed(error) {

            return [ error ];

        }
    }


    /**
     * Find a Model
     * @param {string} modelName
     * @param {string} query
     * @param {array} filters
     * @returns {*}
     */
    function findModels( modelName, query, filters ) {

        filters = filters ? filters: [];

        return $http.get( url() + modelName+ query_string(filters,'?_q='+query ))

            .then(findModelsComplete)

            .catch(findModelsFailed);

        /**
         * Promise upon completing
         * @param response
         * @returns {*}
         */
        function findModelsComplete(response) {

            return response.data;

        }

        /**
         * Error handler
         * @param error
         */
        function findModelsFailed(error) {

            return [ error ];

        }
    }


    /**
     * Construct query string
     * @param oQueries
     * @param {string} seedString
     * @returns {string}
     */
    function query_string( oQueries, seedString ){

        seedString = seedString ? seedString : '';

        var qstr = seedString;

        $.each( oQueries,function(index,query){

            query = ( query );

            qstr = qstr+ ( qstr ? '&'+query : '?'+query );

        });

        return qstr;
    }


    /**
     * Gets a list of Models
     * @param {string} modelName
     * @param {Array} oQueries
     * @returns {*}
     */
    function getModels( modelName, oQueries ) {

        oQueries = oQueries ? oQueries : [];

        return $http.get( url() + modelName + query_string( oQueries ) )

            .then(getModelsComplete)

            .catch(getModelsFailed);

        /**
         * Promise upon completing
         * @param response
         * @returns {*}
         */
        function getModelsComplete(response) {

            return response.data;

        }

        /**
         * Error handler
         * @param error
         */
        function getModelsFailed(error) {

            return [ error ];

        }
    }

    /**
     * Add a new Model
     * @param modelName
     * @param data
     * @returns {*}
     */
    function addModel( modelName, data ){

        return $http.post(url() + modelName,data)

            .then(addModelComplete).catch(addModelFailed);

        /**
         * Promise upon completing
         * @param response
         * @returns {*}
         */
        function addModelComplete(response) {

            return response.data;

        }

        /**
         * Error handler
         * @param error
         */
        function addModelFailed(error) {

            return [ error ];

        }

    }


}])