<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 11/18/15
 * Time: 4:16 PM
 */

namespace Code_Alchemy\Internet;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Canonical_Hostname
 * @package Code_Alchemy\Internet
 *
 * Get the canonical hostname
 */
class Canonical_Hostname extends Stringable_Object{

    public function __construct( $hostname ){

        $parts = explode('.',$hostname);

        while ( count( $parts) > 2)

            array_shift($parts);

        $this->string_representation = implode('.',$parts);

    }

}