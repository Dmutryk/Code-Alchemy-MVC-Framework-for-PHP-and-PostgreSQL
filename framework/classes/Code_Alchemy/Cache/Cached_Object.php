<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/21/15
 * Time: 12:23 AM
 */

namespace Code_Alchemy\Cache;


use Code_Alchemy\Core\Alchemist;

/**
 * Class Cached_Object
 * @package Code_Alchemy\Cache
 *
 * Caches a single object
 */
class Cached_Object extends Alchemist{

    /**
     * @var null
     */
    private static $object = null;



}