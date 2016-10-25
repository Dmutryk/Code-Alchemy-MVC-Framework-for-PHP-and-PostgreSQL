<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/27/15
 * Time: 1:07 AM
 */

namespace Code_Alchemy\Multi_Language_Support\Resolvers;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Localization\Current_Language;

/**
 * Class Resolve_Scope_Language_Members
 * @package Code_Alchemy\Multi_Language_Support\Resolvers
 *
 * Resolve language-based members in the given Scope
 */
class Resolve_Scope_Language_Members extends Array_Representable_Object {

    /**
     * Resolve_Scope_Language_Members constructor.
     * @param array $members
     */
    public function __construct( array $members ) {

        $result = [];

        $lang = (string) new Current_Language();

        foreach ( $members as $name => $value ){

            $result[$name] = $value;

            if ( preg_match("/([a-zA-Z_]+)_$lang/",$name,$hits))

                $result[$hits[1]] = $value;

        }

        $this->array_values = $result;

    }
}