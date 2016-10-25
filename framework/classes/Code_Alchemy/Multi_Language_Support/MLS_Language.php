<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/26/15
 * Time: 12:06 PM
 */

namespace Code_Alchemy\Multi_Language_Support;


use Code_Alchemy\Core\Array_Object;

/**
 * Class MLS_Language
 * @package Code_Alchemy\Multi_Language_Support
 *
 * Represents a single MLS language
 */
class MLS_Language extends Array_Object{

    /**
     * MLS_Language constructor.
     * @param array $members
     */
    public function __construct(array $members) {

        parent::__construct($members);

    }
}