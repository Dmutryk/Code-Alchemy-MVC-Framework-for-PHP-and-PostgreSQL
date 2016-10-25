<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 7/27/16
 * Time: 3:58 PM
 */

namespace Code_Alchemy\APIs\Yellow_Pages;
use Code_Alchemy\Core\Alchemist;


/**
 * Class YP_Search_Term
 * @package Code_Alchemy\APIs\Yellow_Pages
 */
class YP_Search_Term extends Alchemist {

    /**
     * @var string Term
     */
    public $term = '';

    /**
     * @var string
     */
    public $location = '';


    /**
     * YP_Search_Term constructor.
     * @param $term
     * @param string $location
     */
    public function __construct( $term, $location = '' ) {

        $this->term = $term;

        $this->location = $location;

    }

}