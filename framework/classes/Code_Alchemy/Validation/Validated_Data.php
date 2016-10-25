<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/18/16
 * Time: 4:24 PM
 */

namespace Code_Alchemy\Validation;


use Code_Alchemy\Core\Alchemist;

/**
 * Class Validated_Data
 * @package Code_Alchemy\Validation
 *
 * Validates data and sets a result
 */
abstract class Validated_Data extends Alchemist {

    /**
     * @var array of original data
     */
    protected $_data = array();

    /**
     * @var bool true if valid
     */
    protected  $_is_valid = true;

    /**
     * @var array of errors, one per member
     */
    protected $_errors = array();

    /**
     * @var string
     */
    protected $_lang = 'en';

    /**
     * @return bool true if valid
     */
    public function is_valid(){ return $this->_is_valid; }

    /**
     * @param array $data
     * @return void
     */
    abstract protected function validate( array $data );

    /**
     * @return array of validation errors
     */
    public function validation_errors(){ return $this->_errors; }

    /**
     * @return array
     */
    public function original_data(){ return $this->_data;  }

}