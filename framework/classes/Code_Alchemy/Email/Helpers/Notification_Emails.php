<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/12/15
 * Time: 10:41 PM
 */

namespace Code_Alchemy\Email\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Models\Factories\Model_Factory;

/**
 * Class Notification_Emails
 * @package Code_Alchemy\Email\Helpers
 *
 * Get all Notification Emails
 */
class Notification_Emails extends Array_Representable_Object{

    /**
     * Notification_Emails constructor.
     */
    public function __construct() {

        $this->array_values = (new Model_Factory('notification_email'))

            ->find_all_values_as_array('email', 'all', false);

    }
}