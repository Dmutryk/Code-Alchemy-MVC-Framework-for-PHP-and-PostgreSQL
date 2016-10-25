<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/27/15
 * Time: 12:41 AM
 */

namespace Code_Alchemy\Controllers\Helpers;


use Code_Alchemy\Controllers\Controller_Configuration_File;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Webroot;

/**
 * Class Missing_Layout_Creator
 * @package Code_Alchemy\Controllers\Helpers
 *
 * Creates missing layouts, when possible
 */
class Missing_Layout_Creator extends Alchemist{

    /**
     * Missing_Layout_Creator constructor.
     * @param $layout_name
     */
    public function __construct( $layout_name ) {

        $clone_master = (new Controller_Configuration_File())

            ->settings()->clone_missing_from;

        if ( $clone_master){

            $directory = new Webroot() . "/app/views/layouts/";

            $clone_master_path = $directory .$clone_master.".php";

            copy($clone_master_path,$directory.$layout_name.".php");

        }

    }
}