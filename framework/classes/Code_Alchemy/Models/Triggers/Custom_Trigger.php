<?php


namespace Code_Alchemy\Models\Triggers;


use Code_Alchemy\Core\Array_Representable_Object;

class Custom_Trigger extends Array_Representable_Object {

    /**
     * @var bool true if changes were made to original Model
     */
    protected $is_changed = false;

    /**
     * @return bool true if changes were made
     */
    public function is_changed(){ return $this->is_changed; }

}