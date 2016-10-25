<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g42
 * Date: 4/10/13
 * Time: 08:52 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\behaviors;
use Code_Alchemy\beans;
use Code_Alchemy\components;

interface transactable {

    /**
     * Post a new Transaction
     * @param beans\Transaction $transaction
     * @return components\TransactionResult
     */
    public function post_transaction( $transaction );

}