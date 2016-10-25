<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/28/16
 * Time: 6:09 PM
 */

namespace Code_Alchemy\Models\Characteristics;


use Code_Alchemy\Core\Boolean_Value;

/**
 * Class Is_Excluded_from_Insert_Field
 * @package Code_Alchemy\Models\Characteristics
 *
 * Is the given field, within the context of a model, excluded from insert?
 *
 */
class Is_Excluded_from_Insert_Field extends Boolean_Value{

    /**
     * Is_Excluded_from_Insert_Field constructor.
     * @param string $field_name
     * @param string $model_name
     */
    public function __construct( $field_name, $model_name ) {

        $this->boolean_value = in_array($field_name,[

            $model_name.'_id', 'id','created_date','created_by','last_modified_date',
                'last_modified_by','is_deleted','deleted_date','deleted_by','uuid', 'seo_name'

        ]);
    }
}