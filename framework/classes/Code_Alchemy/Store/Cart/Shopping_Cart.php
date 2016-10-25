<?php


namespace Code_Alchemy\Store\Cart;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\Models\Factories\Model_Factory;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;

/**
 * Class Shopping_Cart
 * @package Code_Alchemy\Store\Cart
 *
 * Shopping Cart Server implementation, best used with Ajax calls
 *
 */
class Shopping_Cart extends Array_Representable_Object {

    /**
     * @var Model
     */
    private static $_cart;

    /**
     * @var bool true to append cart to result
     */
    private $append_cart = true;

    /**
     * @var string Language of Application
     */
    private $language = 'en';


    /**
     * @param array $data
     */
    public function __construct( array $data ){

        // Allow app to set language
        $this->language = (new Configuration_File())->language();

        // Load data
        $this->array_values = $data;

        // If we got the cart
        if ( self::$_cart || (self::$_cart = $this->get_cart()) != null ){

                // If action...
            if ( $this->action )

                // Take action
                $this->take_action();

            // Else we have an error
            else

                $this->error = get_called_class().": No action specified";

            // Append Cart if necessary
            if ( self::$_cart && $this->append_cart )

                $this->cart = self::$_cart->as_array();

        }

        if ( $this->error ) \FB::error(get_called_class().": ".$this->error);

    }

    /**
     * @return bool
     */
    private function get_cart(){

        $cart = null;

        // Does cart exist?
        $cookie_name = (string)new Shopping_Cart_Cookie_Name();

        if ( ! isset( $_COOKIE[$cookie_name])){

            //if ( $this->is_development()) \FB::info(get_called_class().": Loading Cart from Database");

            $cart_model = (new Model('shopping_cart'))

                ->find("ip_address='".$_SERVER['REMOTE_ADDR']."'",'',false,false);
            \FB::info("La IP es ".$_SERVER['REMOTE_ADDR']."  (get_cart)");

            if ($cart_model->exists){
                \FB::info("el carro existe (get_cart)");

               $cart = $cart_model;
                // Set a Cookie
                if( ! setcookie($cookie_name,$cart->id,time()+18600,'/')){

                    $cart = null;

                    $this->error = 'Cookies must be enabled to use the Shopping Cart';

                };


            } else {
                \FB::info("el carro NO existe (get_cart)");

                $cart = $cart_model

                    ->create_from(array(

                        'ip_address' => $_SERVER['REMOTE_ADDR'],

                    ));

                if ( $cart && $cart->exists ){

                    // Set a Cookie
                    if( ! setcookie($cookie_name,$cart->id,time()+18600,'/')){

                        $cart = null;

                        $this->error = 'Cookies must be enabled to use the Shopping Cart';

                    };

                } else {

                    $this->error = $cart->error();

                    $cart = null;
                }


            }

        } else {

            //if ( $this->is_development()) \FB::info(get_called_class().": Loading Cart from Database");

            $id = (string) new Key_Column('shopping_cart');

            $cart = (new Model('shopping_cart'))

                ->find("$id='".$_COOKIE[$cookie_name]."'",'',false,false);
        }

        return $cart;
    }

    /**
     * Take action based on user's request
     */
    private function take_action(){

        if ( method_exists($this,$this->action)){

            $action = $this->action;

            $this->$action();

        }


        else

            $this->error = $this->action. ": Shopping Cart method not implemented";


    }

    /**
     * Fetch the cart
     */
    private function fetch(){

        $this->cart = self::$_cart->as_array();

    }

    /**
     * @param $item_id
     * @param $item_option_id
     * @return bool
     */
    public function has_item( $item_id, $item_option_id = null ){

        $id = self::$_cart->id;

        $query = $item_option_id ?

            "shopping_cart_id='$id',item_id='$item_id',item_option_id='$item_option_id'" :

            "shopping_cart_id='$id',item_id='$item_id'";

        //\FB::info($query,"query");


        return !! (new Model('shopping_cart_item'))

            ->find($query)->exists;


    }

    /**
     * Add an item to the cart
     */
    private function add(){


        if ( $this->item_id ){


            $model = (new Model('shopping_cart_item'));

            if ( $model->create_from(array(

                'shopping_cart_id' => self::$_cart->id,

                'item_id' => $this->item_id,

                'item_option_id' => $this->item_option_id

            ))->exists ){

                $this->is_added = true;

                // Invalidate Cache for Factory to ensure fresh view
                Model_Factory::invalidate_cache();

                self::$_cart = $this->get_cart();

                // Refresh cart
                $this->cart = self::$_cart->as_array();

            }

            else {

                $error = $model->error();

                if (preg_match('/duplicate/i',$error))

                    $error = $this->language =='en' ?

                        "This item is already in your cart":

                        "El artículo ya está en el carrito";

                $this->error = $error;

            }

        } else

            $this->error = 'You must specify an Item id';

    }

    /**
     * Removes an item from the Cart
     */
    private function remove(){

        if ( $this->item_id ){

            $id = self::$_cart->id;

            $query = "item_id='$this->item_id',shopping_cart_id='$id'";

            if( $this->item_option_id && intval($this->item_option_id) != 0){
                $query = $query.",item_option_id='$this->item_option_id'";
            }

            $model = (new Model('shopping_cart_item'))

                ->find($query);


            if ( $model->delete() ){

                $this->is_removed = true;

                // Invalidate Cache for Factory to ensure fresh view
                Model_Factory::invalidate_cache();

                self::$_cart = $this->get_cart();

                // Refresh cart
                $this->cart = self::$_cart->as_array();



            }



        else

                $this->error = $model->error();

        } else

            $this->error = 'You must specify an Item id';


    }

    /**
     * Toggle the Item by either adding or removing it as needed
     * @param int|null $item_id
     * @param int|null $item_option_id
     * @return Shopping_Cart
     */

    public function toggle( $item_id = null, $item_option_id = null){

        \FB::info($item_id,"item_id LIN 330 toggle");
        // Allows user to override
        if ( $item_id ) $this->item_id = $item_id;

        if ( $item_option_id ) $this->item_option_id = ($item_option_id);

        if ( $this->has_item($this->item_id,$this->item_option_id))

            $this->remove();

        else

            $this->add();

        return $this;

    }

    /**
     * @return Model SHopping Cart Item
     */
    private function shopping_cart_item(){

        $id = self::$_cart->id;


        $query = ($this->item_option_id) ?

            "item_option_id='$this->item_option_id',item_id='$this->item_id',shopping_cart_id='$id'":
            "item_id='$this->item_id',shopping_cart_id='$id'";

        //\FB::info("In shopping_cart_item() cart:");
        //\FB::info("item_option_id", $this->item_option_id);
        //\FB::info("item_id", $this->item_id);

        return (new Model('shopping_cart_item'))

            ->find($query);

    }

    /**
     * Update the Quantity of an Item
     */
    private function update_quantity(){

        if ( $this->has_item($this->item_id,$this->item_option_id)){

            $model = $this->shopping_cart_item();

            if ( $model->update(array(

                'quantity' => $this->quantity

            ))->put() )

            {
                $this->quantity_updated = true;

                // Invalidate Cache for Factory to ensure fresh view
                Model_Factory::invalidate_cache();

                self::$_cart = $this->get_cart();

                // Refresh cart
                $this->cart = self::$_cart->as_array();


            }

            else {

                $this->result = 'error';

                $this->error = $model->error();
            }
        } else {

            $this->result = 'error';

            $this->error = "$this->item_id: No such item in Cart";

        }




    }

    /**
     * @return array or count of Cart items
     */
    private function shopping_cart_items( $is_count = false ){

        $model_Factory = (new Model_Factory('shopping_cart_item'));
        //$model_Factory = (new Model_Factory('items_cart'));

        \FB::info("items lin 426 Shopping cart (code_alchemy)");
        $query = $is_count ?

            "shopping_cart_id='" . self::$_cart->id . "'":

            "shopping_cart_id='" . self::$_cart->id . "',order by created_date ASC"
        ;

        if ( $this->is_development() ) \FB::info(get_called_class().": Items query is $query");

        \FB::info("In shopping_cart_items( is_count = false ) :");
        \FB::info( $model_Factory );

        return $is_count ? $model_Factory->count_all($query):$model_Factory

            ->find_all($query);
    }

    /**
     * Reset the cart, removing all Items
     */
    private function reset(){

        foreach ( $this->shopping_cart_items() as $item )

            $item->delete();

          $this->is_reset = ! count( $this->shopping_cart_items());
    }

    /**
     * @return bool true if cart is reset
     */
    public function is_reset(){

        return ! ( $this->shopping_cart_items( true) );
    }

    /**
     * @return int shopping_cart id
     */
    private function shopping_cart_id(){

        return (int) self::$_cart->id;

    }

    /**
     * @param int $discount_id to check
     * @return bool true if cart has this discount
     */
    public function has_discount( $discount_id ){


        $result = (new Model('shopping_cart_discount'))

            ->find(

                "shopping_cart_id='" . $this->shopping_cart_id() . "',discount_id='$discount_id'",'',false,false)->shopping_cart_id;

        if ( $this->is_development() ) \FB::info(get_called_class().": Checking if Discount $discount_id is applied to Cart ".$this->shopping_cart_id().". Result is ".$result);

        return !!$result;

    }

    /**
     * @param int $discount_id
     * @return array result
     */
    public function toggle_discount( $discount_id ){

        $action = 'apply';

        $shopping_cart_id = $this->shopping_cart_id();

        $Model = (new Model('shopping_cart_discount'))

            ->find("shopping_cart_id='$shopping_cart_id',discount_id='$discount_id'");

        // if already exists
        if ( $Model->exists ){

            $action = 'remove';

            $result = $Model->delete() ? 'success': 'error';

        }


        else

            $result = $Model

            ->create_from(array(

                'shopping_cart_id' => $shopping_cart_id,

                'discount_id' => $discount_id

            ))->exists ? 'success': 'error';

        $error = $Model->error();

        return array(

            'result' => $result,

            'action' => $action,

            'error' => $error,

            'discount' => (new Model('discount'))

                ->find("id='$discount_id'",'',false,false)->as_array()

        );

    }

    /**
     * @return float total as a floating number
     */
    public function total_as_float(){

        $total = 0.0;

        foreach ( $this->shopping_cart_items() as $item )

            $total += (float) $item->as_array()['total'];

        return $total;

    }

    /**
     * @param $item_id
     * @return int
     */
    public function quantity_for( $item_id ){

        $this->item_id = $item_id;

        return (int) $this->shopping_cart_item()->quantity;

    }

}