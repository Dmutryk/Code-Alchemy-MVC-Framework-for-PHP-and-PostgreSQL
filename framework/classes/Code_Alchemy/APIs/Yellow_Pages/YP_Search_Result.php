<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 7/27/16
 * Time: 4:03 PM
 */

namespace Code_Alchemy\APIs\Yellow_Pages;


use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class YP_Search_Result
 * @package Code_Alchemy\APIs\Yellow_Pages
 */
class YP_Search_Result extends Array_Representable_Object{

    /**
     * YP_Search_Result constructor.
     * @param array $result
     */
    public function __construct( array $result ) {

        $this->array_values = $result;

    }

}