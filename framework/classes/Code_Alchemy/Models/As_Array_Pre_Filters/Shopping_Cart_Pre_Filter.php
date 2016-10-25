<?php


namespace Code_Alchemy\Models\As_Array_Pre_Filters;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\formatters\currencies\Colombian_Pesos;
use Code_Alchemy\Models\Factories\Model_Factory;
use Code_Alchemy\Models\Helpers\Key_Column_For;

class Shopping_Cart_Pre_Filter extends Array_Representable_Object {

    public function __construct( array $members ){

        // Add items

/*
        $members['items'] = (new Model_Factory('shopping_cart_item'))
            ->all_undeleted_as_array("order by created_date ASC,shopping_cart_id='".$members['id']."'");
*/


        $id = (string) new Key_Column_For('shopping_cart');

        $members['items'] = (new Model_Factory('items_cart'))

            ->all_sorted_as_array("order by created_date ASC, shopping_cart_id='".$members[$id]."'");



        // Add count Items
        $members['count_items'] = count( $members['items'] );

        $members['is_empty_class'] = ! $members['count_items']?'is-empty':'';

        $members['total'] = 0;


        $k = 0;
        foreach ( $members['items'] as $item ) {
            $members['total'] += isset($item['total_cost']) ? $item['total_cost'] : ($item['quantity'] * $item['unit_cost']);
            $members["items"][$k]["item_affixed"]['website_image_affixed']['image_filename_url'] = $members["items"][$k]['image_filename_url'];
            $members["items"][$k]["item_affixed"]['formatted_price'] = $members["items"][$k]['formatted_price'];
            $k++;
        }


/*
        foreach ( $members['items'] as $item )
            $members['total'] += isset( $item['total_cost']) ? $item['total_cost']:$item['total'];
*/

        $members['display_total'] = (string) new Colombian_Pesos($members['total'],0);

        $members['cart_has_items'] = $members['count_items'] > 0;

        $this->array_values = $members;

    }

}