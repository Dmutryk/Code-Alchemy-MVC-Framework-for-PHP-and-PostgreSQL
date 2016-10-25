<?php namespace Code_Alchemy\DateTime;

use Code_Alchemy\Core\Boolean_Value;

/**
 * Class Is_Day_Eligible
 * @package Code_Alchemy\DateTime
 *
 * Is the given day eligible, based on the given criteria
 */
class Is_Day_Eligible extends Boolean_Value{

    /**
     * @var array of eligibility values
     */
    private $eligibility = [

        'weekdays' => [1,2,3,4,5],

        'weekends' => [6,7]

    ];

    /**
     * Is_Day_Eligible constructor.
     * @param int $dayId
     * @param string $criteria
     */
    public function __construct( $dayId, $criteria) {

        $this->boolean_value = ( ! isset($this->eligibility[$criteria])

            || in_array((int)$dayId,$this->eligibility[$criteria])  );

    }
}