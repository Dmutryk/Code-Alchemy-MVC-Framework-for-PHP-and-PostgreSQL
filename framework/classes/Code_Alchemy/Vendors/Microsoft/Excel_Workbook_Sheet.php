<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/11/16
 * Time: 8:56 PM
 */

namespace Code_Alchemy\Vendors\Microsoft;


use Code_Alchemy\Core\Array_Representable_Object;

class Excel_Workbook_Sheet extends Array_Representable_Object {

    /**
     * @var string name of sheet
     */
    private $name = '';

    /**
     * @var array
     */
    private $rows = [];

    /**
     * Excel_Workbook_Sheet constructor.
     * @param $name
     */
    public function __construct( $name ) {

        $this->name = $name;

    }

    public function add_row( array $row ){

        $this->rows[] = $row;
    }

    public function name(){ return $this->name; }

    public function rows(){ return $this->rows; }

}