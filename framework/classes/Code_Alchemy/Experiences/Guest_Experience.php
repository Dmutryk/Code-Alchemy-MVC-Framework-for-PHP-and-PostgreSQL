<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 7/30/15
 * Time: 5:07 PM
 */

namespace Code_Alchemy\Experiences;


/**
 * Class Guest_Experience
 * @package Code_Alchemy\Experiences
 *
 * A Guest Experience comsists of actions behaviors and events common to guests
 * visiting and interacting with a website, when not logged in
 */
class Guest_Experience extends Website_Experience {

    public function __construct( array $initial_values = array() ){

        // Save values
        $this->user_values = $initial_values;

        parent::__construct();

        $this->load();

        // Automatically save when values present
        if ( count( $initial_values ) > 0 )

            $this->is_saved_on_create = $this->save();


    }


}