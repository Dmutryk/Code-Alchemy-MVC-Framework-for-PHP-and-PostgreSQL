/**
 * Code_Alchemy Web Director
 */
Code_Alchemy_Web_Director = {

    /**
     * Hide a specific field
     * @param model
     * @param id
     * @param field
     * @param type
     */
    hide_field: function(model,id,field,type){

        type = type?type:'view';

        $.ajax({
            url: '/parnassus/hide_field',
            type: 'POST',
            data: 'type='+type+'&model='+model+'&id='+id+'&field='+field,
            success: function(json){

                if (json.result =='success')

                    window.location.reload();

            }
        })

    },

    /**
     * Substring Matcher
     * @param strs
     * @returns {Function}
     */
    substringMatcher: function(strs) {

        return function findMatches(q, cb) {
            var matches, substrRegex;

            // an array that will be populated with substring matches
            matches = [];

            // regex used to determine if a string contains the substring `q`
            substrRegex = new RegExp(q, 'i');

            // iterate through the pool of strings and for any string that
            // contains the substring `q`, add it to the `matches` array
            $.each(strs, function(i, str) {
                if (substrRegex.test(str)) {
                    // the typeahead jQuery plugin expects suggestions to a
                    // JavaScript object, refer to typeahead docs for more info
                    matches.push({ value: str });
                }
            });

            cb(matches);
        };
    }


};

$(function(){

    $('[data-toggle="tooltip"]').tooltip();

    var typeahead = $('.typeahead');


    // Enable type-ahead
    typeahead.typeahead({
            hint: true,
            highlight: true,
            minLength: 2
        },
        {
            name: 'states',
            displayKey: 'value',
            source: Code_Alchemy_Web_Director.substringMatcher(states)
        }).on('typeahead:selected',function(event,suggestion,dataset){

            $.each(states,function(model_name,label){

                if ( suggestion.value === label )

                    window.location.href = '/parnassus/list_of/'+model_name+'/1/25';


            });

        });

    typeahead.focus();


    $('a.hide-field').on('click',function(e){

        model = $(this).attr('data-model-name');

        id = $(this).attr('data-model-id');

        field = $(this).attr('data-field-name');

        Code_Alchemy_Web_Director.hide_field(model,id,field,'view');

        return false;
    });

});

