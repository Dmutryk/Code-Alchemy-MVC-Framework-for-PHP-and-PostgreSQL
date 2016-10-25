<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 12/6/15
 * Time: 10:45 PM
 */

namespace Code_Alchemy\DateTime;


use Code_Alchemy\Core\Alchemist;

/**
 * Class Time_Duration
 * @package Code_Alchemy\DateTime
 *
 * Represents a time duration
 */
class Time_Duration extends Alchemist{

    /**
     * @var int Duration
     */
    private $duration = 0;

    /**
     * Time_Duration constructor.
     * @param $start_time
     * @param $end_time
     */
    public function __construct( $start_time, $end_time ){

        $this->duration = abs( strtotime($start_time)- strtotime($end_time) );

    }

    /**
     * @return float duration in hours
     */
    public function in_hours(){

        return $this->duration / (60*60);
    }
}