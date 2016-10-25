<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/23/15
 * Time: 7:45 PM
 */

namespace Testing\Unit_Tests;


use Code_Alchemy\Security\Officer;

class Login_Unit_Test extends Unit_Test {

    /**
     * @var int User Id to test
     */
    private $user_id;

    /**
     * @param array $data for testing
     */
    public function __construct( array $data ){

        foreach ( $data as $name => $datum )

            if ( property_exists($this,$name))

                $this->$name = $datum;


    }

    /**
     * Execute the test
     */
    public function execute(){

        $officer

            = (new Officer(true));
        $officer

            ->login_api( $this->user_id );

        \FB::info($officer->me()->as_array());

    }

}