<?php


namespace __namespace__\Models\Triggers\Before_Insert;


use Code_Alchemy\Models\Triggers\Custom_Trigger;

/**
 * Class __classname__
 * @package __namespace__\Models\Triggers\Before_Insert
 *
 * This trigger fires automagically BEFORE inserting a new Model of the same type.
 *
 * You can use to to take custom actions, and also to modify the proposed model
 * values before they are inserted.
 *
 * By default all values are copied over, remember if you omit this action, there
 * will be nothing to insert!
 */
class __classname__ extends Custom_Trigger {

    /**
     * @var bool false by default, but trigger can prevent insert by setting true
     */
    private $is_error = false;

    /**
     * @var string An error that you can define when insert is denied
     */
    private $insert_error = '';

    /**
     * @param array $values to be set as part of new model
     */
    public function __construct( array $values ){

        // By default, copy all values over, but you cna change this
        $this->array_values = $values;

    }

    /**
     * @return bool true Allows parent to determine if an error was thrown
     */
    public function is_error(){ return $this->is_error; }

    /**
     * @return string Allows parent to obtain an insert error, if any
     */
    public function insert_error(){ return $this->insert_error; }

}