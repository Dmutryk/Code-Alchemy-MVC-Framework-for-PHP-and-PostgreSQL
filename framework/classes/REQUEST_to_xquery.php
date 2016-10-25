<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g42
 * Date: 30/09/13
 * Time: 11:06 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy;


class REQUEST_to_xquery {

    public static function create(){

        return new self;

    }

    public function as_array(){

        global $container;

        $tag = new \xo_codetag( xo_basename(__FILE__), __LINE__, get_class(), __FUNCTION__);

        $arr = array();

        /**
         *  Go through each of POST and GET
         */
        foreach( $_POST as $name=>$value)

        {

            if ( $container->debug ) echo "$tag->event_format: $name = $value<br>\r\n";

            $arr[] = "$name='$value'";
        }
        foreach( $_GET as $name=>$value)

        {

            if ( $container->debug ) echo "$tag->event_format: $name = $value<br>\r\n";

            $arr[] = "$name='$value'";
        }

        return $arr;
    }

}