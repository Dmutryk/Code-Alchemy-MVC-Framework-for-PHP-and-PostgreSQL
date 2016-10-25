<?php
/**
 * Created by JetBrains PhpStorm.
 * User: davidg
 * Date: 12/26/14
 * Time: 3:23 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\helpers;


class plural_for {

    /**
     * @var string Plural of given string
     */
    private $plural = '';

    /**
     * @var string the whole word with plural attached
     */
    public $word = '';

    /**
     * @param string $string to pluralize
     */
    public function __construct( $string ){

        $this->plural =     "s";

        $this->word = $string.$this->plural;

        if ( preg_match('/y$/',$string))

        {
            $this->plural = "ies";

            $this->word = substr($string,0,strlen($string)-1).$this->plural;

        }


        if ( preg_match('/s$/',$string) & ! preg_match('/ks$/',$string))

        {
            $this->plural = "ses";

            $this->word = substr($string,0,strlen($string)-1).$this->plural;

        }

        if ( preg_match('/ks$/',$string))

        {
            $this->plural = "s";

            $this->word = substr($string,0,strlen($string)-1).$this->plural;

        }

        if ( preg_match('/es$/',$string) )

        {
            $this->plural = "s";

            $this->word = substr($string,0,strlen($string)-1).$this->plural;

        }



    }

    public function __toString(){

        return $this->plural;

    }

}