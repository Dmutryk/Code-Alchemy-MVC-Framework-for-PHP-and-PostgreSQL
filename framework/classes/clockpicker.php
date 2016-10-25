<?php


namespace Code_Alchemy\html5\widgets;


use Code_Alchemy\Core\Random_Password;

class clockpicker {

    /**
     * @var string Starting value
     */
    private $starting_value = '';

    /**
     * @var null After callback function name
     */
    private $after_callback = null;

    /**
     * @var bool Use twelve hour?
     */
    private $use_twelve_hour = false;

    /**
     * @var array of HTML attributes
     */
    private $attributes = array();

    /**
     * @var bool true to send output to Firebug
     */
    private $firebug = false;

    public function __construct(
        $atributes = array(),
        $after_callback = null,
        $starting_value = '',
        $use_twelve_hour = false
    ){

        $this->attributes = $atributes;

        $this->after_callback = $after_callback;

        $this->starting_value = $starting_value;

        $this->use_twelve_hour = $use_twelve_hour;

    }

    /**
     * @return clockpicker
     */
    public static function create( $attributes = array(), $after_callback = null, $starting_value = '', $use_twelve_hour = false ){

        return new self( $attributes, $after_callback, $starting_value , $use_twelve_hour);

    }

    /**
     * @return string parsed attributes
     */
    private function parse_attributes(){

        $parse = '';

        foreach ( $this->attributes as $name=>$value )

            $parse .= " $name='$value' ";

        return $parse;
    }

    /**
     * @return string HTML for the widget
     */
    public function html(){

        // If no ID, set one
        $id = isset( $this->attributes['id'])? $this->attributes['id']:(string) new Random_Password(5);

        $id_attr = "id='$id'";

        if ( $this->firebug ) \FB::log("Clockpicker: Id attribute is $id_attr");

        unset( $this->attributes['id']);

        // Set options
        $options = array();

        $after_callback = $this->after_callback?$this->after_callback:'NO_CALLBACK';

        $twelve_hour = $this->use_twelve_hour? 'true':'false';

        $html = "

        <div $id_attr ". $this->parse_attributes()." class=\"input-group clockpicker\"
        data-align=\"top\" data-autoclose=\"true\">
    <input type=\"text\" class=\"form-control\" value=\"$this->starting_value\">
    <span class=\"input-group-addon\">
            <i class=\"fa fa-clock-o\"></i>
    </span>
</div>
<script type=\"text/javascript\">

    var clockpicker_$id = $('#".$id."');

    clockpicker_$id.clockpicker({
        twelvehour: $twelve_hour,
        afterDone: function(){


            var input = clockpicker_$id.find('input');

            var value = input.val();

            input.val( value + ':00');
            var NO_CALLBACK = null;

            var callback = $after_callback;
            if ( typeof(callback)=='function')
                callback(input.val());
        }
});
</script>

        ";

        return $html;
    }

}