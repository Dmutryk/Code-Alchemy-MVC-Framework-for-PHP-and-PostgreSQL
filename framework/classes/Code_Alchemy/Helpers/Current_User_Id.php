<?php


namespace Code_Alchemy\Helpers;


use Code_Alchemy\Core\Application_Configuration_File;
use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Security\Officer;

class Current_User_Id extends Stringable_Object {

    /**
     * @var int Fake User Id, which can be set for testing purposes
     */
    private static $fake_user_id = -1;

    public function __construct(){

        // Check for Fake User Id
        if ( self::$fake_user_id == -1 )

            $this->check_fake_userid();

        $id = self::$fake_user_id ? self::$fake_user_id :

            (new Officer())->me()->id();

        $this->string_representation = (string) $id;

    }

    /**
     * Check if we should use a Fake user id
     */
    private function check_fake_userid(){

        $fake_userid = (new Application_Configuration_File())

            ->find('fake-user-id');

        self::$fake_user_id = $fake_userid ? $fake_userid: 0;

    }

}