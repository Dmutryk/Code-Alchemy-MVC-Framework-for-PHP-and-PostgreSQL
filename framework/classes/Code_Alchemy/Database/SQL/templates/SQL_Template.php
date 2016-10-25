<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/16/15
 * Time: 11:16 PM
 */

namespace Code_Alchemy\Database\SQL\templates;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;

/**
 * Class SQL_Template
 * @package Code_Alchemy\Database\SQL\templates
 *
 * SQL template
 */
class SQL_Template extends Stringable_Object {

    /**
     * SQL_Template constructor.
     * @param string $subdirectory
     * @param $template_name
     */
    public function __construct( $subdirectory = '', $template_name ) {

        $dir = (new Code_Alchemy_Root_Path())."sql"."$subdirectory";

        $template_file = $dir . $template_name.".sql";

        $this->string_representation = file_exists($template_file)

            ? file_get_contents($template_file): '';

    }
}