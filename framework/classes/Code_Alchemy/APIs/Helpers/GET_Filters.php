<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/26/15
 * Time: 1:20 PM
 */

namespace Code_Alchemy\APIs\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class GET_Filters
 * @package Code_Alchemy\APIs\Helpers
 *
 * GET filters for the current query
 *
 * Only certain elements are eligible:
 *
 * 1) _id fields
 */
class GET_Filters extends Array_Representable_Object {

    public function __construct(){

        $filters = array();

        foreach ( $_GET as $name => $value ){

            if ( preg_match('/(.+)id$/',$name)

            && ! in_array($name,(new Exclude_from_Id_Columns())->as_array())

            )

                $filters[] = "$name='$value'";

            // For limit
            if ( preg_match('/LIMIT_([0-9]+)/i',$name,$matches)){

                $filters[] = "LIMIT ".$matches[1];

            }

            // For offset
            if ( preg_match('/OFFSET_([0-9]+)/i',$name,$matches)){

                $filters[] = "OFFSET ".$matches[1];

            }



        }


        $this->array_values = $filters;
    }
}