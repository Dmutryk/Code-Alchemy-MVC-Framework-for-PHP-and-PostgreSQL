<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/10/15
 * Time: 9:50 AM
 */

namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Model_Field_Label
 * @package Code_Alchemy\Models\Helpers
 *
 * Gets representation of Model Field (column name) as a human-friendly label
 */
class Model_Field_Label extends Stringable_Object {

    /**
     * @param string $field_name to parse
     */
    public function __construct( $field_name ){

        // Get Language
        $language = (new Configuration_File())->language();

        switch ( $field_name ){

            case 'website_image_id':

                $field_name = $language == 'es'? 'Imagen Seleccionada' :'Selected Image';

                break;

            case 'sortable_id':

                $field_name = $language == 'es'? 'Orden de Clasificación': "Sort Order";

            break;

            default:

                if ( preg_match('/([a-z_]+)_website_image/',$field_name,$hits))

                    $field_name = $language == 'es' ? "Imagen <strong>".new CamelCase_Name($hits[1],'_',' ')."</strong>":

                        '<strong>'.new CamelCase_Name($hits[1],'_',' ')."</strong> Image"

                    ;
                break;

        }

        $this->string_representation = (string) $field_name;

    }

}