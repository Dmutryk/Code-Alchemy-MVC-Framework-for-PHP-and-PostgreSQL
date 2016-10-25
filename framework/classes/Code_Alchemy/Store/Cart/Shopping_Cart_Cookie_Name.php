<?php


namespace Code_Alchemy\Store\Cart;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Helpers\Namespace_Guess;

/**
 * Class Shopping_Cart_Cookie_Name
 * @package Code_Alchemy\Store\Cart
 *
 * The name of the Shopping Cart Cookie
 */
class Shopping_Cart_Cookie_Name extends Stringable_Object {


    public function __construct(){

        $cookie_name = 'code_alchemy_shopper_' . new Namespace_Guess();

        $this->string_representation = $cookie_name;

    }

}