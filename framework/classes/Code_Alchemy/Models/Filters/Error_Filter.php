<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/26/15
 * Time: 12:11 PM
 */

namespace Code_Alchemy\Models\Filters;


use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Error_Filter
 * @package Code_Alchemy\Models\Filters
 *
 * Filters the error message using internal rules
 */
class Error_Filter extends Stringable_Object {

    /**
     * @param string $error_text
     */
    public function __construct( $error_text ){

        // For duplicate key entries
        if ( preg_match('/Duplicate entry \'(.+)\' for key \'([a-zA-Z0-9_]+)\'/',$error_text,$hits)){

            $key = array_shift(explode('_',$hits[2]));

            $error_text = "There is already a record with $key set to ".$hits[1];
        }

        // For foreign key constraints
        if ( preg_match('/Cannot add or update a child row\: a foreign key constraint fails \(`([a-zA-Z_]+)`.`([a-zA-Z_]+)`, CONSTRAINT `([a-zA-Z_]+)` FOREIGN KEY \(`([a-zA-Z_]+)`\) REFERENCES `([a-zA-Z_]+)` \(`([a-zA-Z_]+)`\)\)/',$error_text,$hits)){

            //\FB::info($hits);

            $error_text = "Code Alchemy couldn't save the ".(string) new CamelCase_Name($hits[2],'_',' ').". This Model must reference a valid "

                .(string) new CamelCase_Name($hits[5],'_',' ')." but a valid one wasn't provided.";

        }
        $this->string_representation = (string) $error_text;

    }

}