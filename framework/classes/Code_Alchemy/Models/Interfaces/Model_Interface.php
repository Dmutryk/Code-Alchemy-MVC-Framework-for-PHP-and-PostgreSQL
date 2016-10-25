<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/12/15
 * Time: 9:56 PM
 */

namespace Code_Alchemy\Models\Interfaces;


interface Model_Interface {

    /**
     * @param string $reason
     * @param array $missing_fields
     * @param bool|false $echo_back_sql to screen, for debugging
     * @return bool
     */
    public function put(

        $reason = '',
        array &$missing_fields = array(),
        $echo_back_sql = false

    );

}