<?php


namespace __mynamespace__\jobs;

use Code_Alchemy\jobs\Cron_Job;
use Code_Alchemy\tools\code_tag;

class __classname__ extends Cron_Job {

    /**
     * @param bool $verbose true to send output
     */
    public function run( $verbose = false ){

        $tag = new code_tag(__FILE__,__LINE__,get_class(),__FUNCTION__);

    }


}