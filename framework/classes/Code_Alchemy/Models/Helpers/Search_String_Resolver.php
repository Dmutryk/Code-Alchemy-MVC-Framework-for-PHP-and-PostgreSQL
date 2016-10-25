<?php


namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Stringable_Object;

class Search_String_Resolver extends Stringable_Object {

    public function __construct( $search ){

        if ( preg_match("/{{([a-z_]+)}}/",$search,$hits))

            switch ( $hits[1] ){

                case 'current_user_id':

                break;
            }

    }

}