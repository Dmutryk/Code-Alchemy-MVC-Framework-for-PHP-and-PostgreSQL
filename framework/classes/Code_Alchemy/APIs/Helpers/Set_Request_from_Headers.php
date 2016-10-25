<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 2/3/16
 * Time: 4:55 PM
 */

namespace Code_Alchemy\APIs\Helpers;


use Code_Alchemy\Core\Alchemist;

/**
 * Class Set_Request_from_Headers
 * @package Code_Alchemy\APIs\Helpers
 *
 * Uses certain known HTTP headers to set request values
 */
class Set_Request_from_Headers extends Alchemist{

    public function __construct() {

        $list_views = @apache_request_headers()['List-View-When-Available'];

        if ( $list_views) $_REQUEST['_list_view_if_available'] = true;

        $field_info = @apache_request_headers()['Field-Info'];

        if ( $field_info ) $_REQUEST['_field_info'] = true;

    }

}