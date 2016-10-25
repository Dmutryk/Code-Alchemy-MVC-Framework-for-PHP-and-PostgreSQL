<?php


namespace Code_Alchemy\Core;


class Float_Value {

    /**
     * @var float float value
     */
    protected $float_value = 0.00;

    /**
     * @return float value
     */
    public function float_value(){

        if ( ! is_float($this->float_value))

            \FB::error(get_called_class().": $this->float_value: Not a Float value");

        return $this->float_value;

    }

}