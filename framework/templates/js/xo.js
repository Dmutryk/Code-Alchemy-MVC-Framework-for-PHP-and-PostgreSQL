/**
 * Created with JetBrains PhpStorm.
 * User: Aspire
 * Date: 06/11/12
 * Time: 05:02 PM
 * To change this template use File | Settings | File Templates.
 */
$.xo = {
    /**
     * extracts a key/id pair value embedded in the CSS classes of an element
     * @param elem the element from which to extract
     * @return string|null the kid if found using regex, otherwise null
     */
    "get_kid":function(elem,prefix,debug){
        var debug = debug?debug:false;
        if (debug) console.log('prefix is '+prefix);
        var id = null;
        if ( elem.length ){
            var classes = elem.attr('class');
            // console.log("classes="+classes);
            var expr = prefix+"\\-"+"([a-z|A-Z|0-9|_|\\-|\\.]+)";
            var regexp = new RegExp(expr,"g");
            var m = regexp.exec(  classes );
            id = m?m[1]:id;
        } else {
            if ( debug){
                console.log(elem);
                console.log("above element not found");
            }
        }
        return id;
    },

    /**
     * extracts a numeric value embedded in the CSS classes of an element
     * @param elem the element from which to extract
     * @return string|null the id if found using regex, otherwise null
     */
    "get_numeric":function(elem,prefix){
        var id = null;
        if ( elem.length ){
            var classes = elem.attr('class');
            var regexp = new RegExp(prefix+"-"+"([0-9]+)","g");
            var m = regexp.exec(  classes );
            id = m?m[1]:id;
        }
        return id;
    },

    /**
     * extracts a numeric id embedded in the CSS classes of an element
     * @param elem the element from which to extract
     * @return string|null the id if found using regex, otherwise null
     */
  "get_id": function(elem){ return $.xo.get_numeric(elem,"id"); }
};
