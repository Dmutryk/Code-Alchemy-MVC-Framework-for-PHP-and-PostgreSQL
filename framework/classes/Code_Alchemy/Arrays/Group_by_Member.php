<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 9/27/15
 * Time: 9:37 AM
 */

namespace Code_Alchemy\Arrays;


use Code_Alchemy\components\seo_name;
use Code_Alchemy\Core\Array_Representable_Object;

/**
 * Class Group_by_Member
 * @package Code_Alchemy\Arrays
 *
 * Groups arrays by a specific member, in alphabetical order
 */
class Group_by_Member extends Array_Representable_Object{

    /**
     * @var string Member name
     */
    private $member_name = '';


    /**
     * @var int add a grouping Id to each group
     */
    private static $id = 1;

    /**
     *
     * @param array $items to group
     * @param string $member_name to group by
     */
    public function __construct( array $items, $member_name ){

        $this->member_name = $member_name;

        $grouped_members = array();

        foreach( $items as $item ){


            $member = $item[$member_name];

            if ( ! isset( $grouped_members[$member]))

                $grouped_members[ $member ] = array(

                    $member_name => $item[ $member_name ],

                    'seo_name' => (string) new seo_name($item[$member_name]),

                    'items' => array(

                        $item
                    )
                );

            else

                $grouped_members[ $member]['items'][] = $item;

        }

        // Sort
        usort($grouped_members,array(

            $this, 'sort_items'
        ));

        $modified_members = array();

        foreach ( $grouped_members as $key => $member ){

            $member['_group_id'] = self::$id++;

            $modified_members[ $key ] = $member;
        }

        $this->array_values = $modified_members;

    }

    private function sort_items( $a, $b ){

        return $a[$this->member_name] < $b[ $this->member_name ]?

            -1 : ( $a[$this->member_name] > $b[ $this->member_name ] ? 1:0);
    }

}