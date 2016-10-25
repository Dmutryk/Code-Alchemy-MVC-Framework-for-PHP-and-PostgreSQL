<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/21/15
 * Time: 7:27 PM
 */

namespace Code_Alchemy\Multimedia;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class File_Fontawesome_Class
 * @package Code_Alchemy\Multimedia
 *
 * An appropriate fontawesome class for the file type
 */
class File_Fontawesome_Class extends Stringable_Object {

    public  function __construct( $filename ){

        $class = 'fa-file';

        $this->string_representation = $class;
    }
}