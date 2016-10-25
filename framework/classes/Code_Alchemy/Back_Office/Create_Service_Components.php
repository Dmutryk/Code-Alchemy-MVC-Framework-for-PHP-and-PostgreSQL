<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/16/15
 * Time: 9:42 PM
 */

namespace Code_Alchemy\Back_Office;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Core\Webroot;
use Code_Alchemy\Database\Table\PostgreSQL\PostgreSQL_Database_Tables;

/**
 * Class Create_Service_Components
 * @package Code_Alchemy\Back_Office
 *
 * Creates Service Components for Back Office
 */
class Create_Service_Components extends Alchemist{

    /**
     * Create_Service_Components constructor.
     * @param PostgreSQL_Database_Tables $tables
     */
    public function __construct( PostgreSQL_Database_Tables $tables ){

        $baseDirectory = new Webroot() . "/back-office/app/services/";

        if ( ! file_exists($baseDirectory))

            mkdir($baseDirectory);

        foreach ( $tables->as_array() as $table ){

            $serviceDirectory = $baseDirectory . $table['table_name'];

            if ( ! file_exists($serviceDirectory) )

                mkdir($serviceDirectory);

        }


    }
}