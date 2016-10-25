<?php

/**
 * Class Changeable sets required methods to help an object manage changes to its
 * saved values, such as for a Data object
 */
interface Changeable {

    /**
     * @param string $key to lookup
     * @return bool true iff the value for given key has changed
     */
    public function hasChanged( $key );
	
	//! set the given key as changed
	public function changed( $key );
	
	//! reset all changes
	public function resetChange();
	
	//! have their been any changes?
	public function noChanges();

}

?>