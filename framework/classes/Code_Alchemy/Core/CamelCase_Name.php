<?php


namespace Code_Alchemy\Core;


class CamelCase_Name extends Stringable_Object {

    /**
     * @param $phrase
     * @param string $separate
     * @param string $join
     * @param bool $tolowercase
     */
    public function __construct( $phrase, $separate = ' ', $join = '_', $tolowercase = false ){

        // Reduce extra space
        $phrase = preg_replace('/\s+/',' ',$phrase);

        // Split into pieces
        $pieces = explode($separate,$phrase);

        $words = array();

        // Set as Ucfirst
        foreach ( $pieces as $piece )

            $words[] = $tolowercase ? strtolower($piece) : ucfirst($piece);

        // Join and save
        $this->string_representation = implode($join,$words);


    }
}