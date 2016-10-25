<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/1/16
 * Time: 12:17 PM
 */

namespace Code_Alchemy\Database\Table\Fields;


use Code_Alchemy\Core\Boolean_Value;

/**
 * Class Is_Website_Image_Field
 * @package Code_Alchemy\Database\Table\Fields
 *
 * Is the given field a website image field?
 */
class Is_Website_Image_Field extends Boolean_Value{

    /**
     * Is_Website_Image_Field constructor.
     * @param string $field_name to check
     */
    public function __construct( $field_name ) {

        $this->boolean_value = !! preg_match('/website_image([0-9]*)_id/',$field_name);

    }
}