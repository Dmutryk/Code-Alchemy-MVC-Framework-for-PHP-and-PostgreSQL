<?php
/**
 * Project:
 * Module:
 * Component:
 * Description:
 * Author:
 * Copyright:
 */

namespace Code_Alchemy;


class restful_result extends api_result {

    public function __construct($options = array()){

        foreach( $options as $member=>$value)
            $this->rest_result[$member] = $value;
    }


}