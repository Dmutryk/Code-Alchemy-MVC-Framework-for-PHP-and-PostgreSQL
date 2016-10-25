<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 10/4/15
 * Time: 5:57 PM
 */

namespace Code_Alchemy\Database\Import;


use Code_Alchemy\Core\Alchemist;

/**
 * Class Import_Structure_Header
 * @package Code_Alchemy\Database\Import
 *
 * Import Structure Header
 */
class Import_Structure_Header extends Alchemist{

    /**
     * @var array of header values
     */
    private $header_values = array();

    /**
     * @param array $header_values
     */
    public function set( array $header_values ){

        $this->header_values = $header_values;

    }

    /**
     * @param array $values
     * @return array of mapped values
     */
    public function map_values( array $values ){

        $map = array();

        foreach( $this->header_values as $id => $value )

            $map[ $value ] = trim($values[ $id ]);

        return $map;
    }

    /**
     * @return bool true if set
     */
    public function is_set(){ return count( $this->header_values) > 0; }

}