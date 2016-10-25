<?php

namespace Code_Alchemy\Store\Cart;

/**
 * Class Cached_Shopping_Cart
 * @package Code_Alchemy\Store\Cart
 *
 * A Cached version of the Shopping Cart, for performance
 */
class Cached_Shopping_Cart {

    /**
     * @var
     */
    private static $cart = null;

    public function __construct(){

        if ( ! self::$cart ) self::$cart = new Shopping_Cart(array('action' => 'fetch'));

    }

    /**
     * @return Shopping_Cart
     */
    public function cart(){ return self::$cart; }
}