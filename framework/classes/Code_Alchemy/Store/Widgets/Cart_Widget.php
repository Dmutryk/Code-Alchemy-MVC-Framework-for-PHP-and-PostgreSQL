<?php


namespace Code_Alchemy\Store\Widgets;


use Code_Alchemy\Core\Stringable_Object;
use Code_Alchemy\Store\Cart\Shopping_Cart;
use Code_Alchemy\Views\Helpers\Handlebars_Engine;

/**
 * Class Cart_Widget
 * @package Code_Alchemy\Store\Widgets
 *
 * The Cart Widget allows the Shopping Cart to be placed on the user's
 * view as a collapsible sidebar widget.
 */
class Cart_Widget extends Stringable_Object {

    public function __construct(){

        $this->string_representation = (string) (new Handlebars_Engine())

            ->render('shopping-cart-widget',(new Shopping_Cart(array('action'=>'fetch')))->as_array());


    }

}