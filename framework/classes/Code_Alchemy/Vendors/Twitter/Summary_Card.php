<?php


namespace Code_Alchemy\Vendors\Twitter;


use Code_Alchemy\Templates\Web_Snippet;

class Summary_Card extends Web_Snippet {

    public function __construct( array $data ){

        parent::__construct( 'twitter-summary-card' , $data);

    }


}