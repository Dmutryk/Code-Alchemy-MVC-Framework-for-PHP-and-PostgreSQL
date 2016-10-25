/**
 * Project:     Your Project Name, Description
 * Collection:  Describe this Collection
 */
define(['models/_hyphenated_name_'],function (Model) {
    return Backbone.Collection.extend({

        // Set a query
        _query: '',

        url: function(){

            var query = this._query.length ? '?query='+this._query:'';

            return '/_api_base_/_model_name_'+query ;

        },
        model: Model,
        defaults: {
            id: 1
        },
        initialize: function( options ){

            // Normalize Options
            options = options? options:{};

            // If we have a query
            if ( options && typeof(options.query)!='undefined')

                this._query = options.query;

        }
    });
});

