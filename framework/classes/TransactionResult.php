<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g42
 * Date: 4/10/13
 * Time: 08:57 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\components;


class TransactionResult {

    private $receipt = '',
            $sequence_no = '',
            $result = '',
            $is_approved = false,
            $authorization_number = '',
            $error = '';

    public function __construct( $arr ){

        foreach ( $arr as $name=>$value)
            $this->$name = $value;

    }

    /**
     * @return array representation of Result
     */
    public function as_array(){
        return array(
            'receipt'=>$this->receipt,
            'sequence_no'=>$this->sequence_no,
            'result'=>$this->result,
            'is_approved'=>$this->is_approved,
            'authorization_number'=>$this->authorization_number,
            'error'=>$this->error
        );
    }

    public function sequence_no(){ return $this->sequence_no; }

    public function result(){ return $this->result; }

    public function is_approved(){  return $this->is_approved; }

    public function authorization_number(){  return $this->authorization_number; }

    public function error(){ return $this->error; }
}