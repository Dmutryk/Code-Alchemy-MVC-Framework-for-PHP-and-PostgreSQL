<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/16/15
 * Time: 9:02 PM
 */

namespace Code_Alchemy\Applications\Web_Director;


use Code_Alchemy\components\managed_service2;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Database\Database;

/**
 * Class Services_Fetcher
 * @package Code_Alchemy\Applications\Web_Director
 * 
 * Fetches Services to be managed by Web Director
 */
class Services_Fetcher extends Array_Representable_Object {

    /**
     * @var bool true to locallly debug
     */
    private $debug = false;

    public function __construct( array $settings ){
        
        $hidden = array();

        $unhidden = array();

        $database = new Database();

        foreach ( $database->tables_and_views() as $table ){

            $is_hidden = false;

            if ( isset( $settings['hidden_services']))

                if ( in_array($table,$settings['hidden_services']))

                    $is_hidden = true;

            $options = array();

            if ( isset( $settings[$table]['service_label']))

                $options['service_label'] = $settings[$table]['service_label'];


            $service = new managed_service2( $table, $is_hidden, $options );

            // push if not hidden
            if ( ! $is_hidden )

                $unhidden[] = (array)$service->as_array();

            else

                $hidden[] = (array)$service->as_array();

        }

        // sort the services by Label
        usort( $unhidden,function($a,$b){

            if ($a['service_label'] == $b['service_label']) {

                return 0;
            }

            return ($a['service_label'] < $b['service_label']) ? -1 : 1;

        });

        $services = array_merge($unhidden, $hidden);

        $this->array_values = $services;

    }
}