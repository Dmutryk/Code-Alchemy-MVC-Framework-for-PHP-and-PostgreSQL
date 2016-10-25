<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/18/16
 * Time: 4:23 PM
 */

namespace Code_Alchemy\Payment_Alchemy\Validation;


use Code_Alchemy\Payment_Alchemy\Assets\Credit_Card;
use Code_Alchemy\Validation\Validated_Data;
use Code_Alchemy\Validation\Validated_Email_Address;
use Code_Alchemy\Validation\Validation_Error;

/**
 * Class Pre_Validate_Payment
 * @package Code_Alchemy\Payment_Alchemy\Validation
 */

class Pre_Validate_Payment extends Validated_Data{

    /**
     * @var array of additional required fields
     */
    private $required_fields = array();


    private $custom_validation_method = null;

    /**
     * Pre_Validate_Payment constructor.
     * @param array $data
     * @param string $language
     * @param array $required_fields that user may specify beyond what is required
     */
    public function __construct( array $data, $language = 'en', array $required_fields = array() ) {

        $this->_data = $data;

        $this->_lang = $language;

        $this->required_fields = $required_fields;

        $this->validate( $data );

    }

    /**
     * @param array $data
     */
    protected function validate( array $data ){

        $this->_is_valid = true;

        foreach ( array_merge([ 'payer','credit_card' ,'expiration_date','cvv'],$this->required_fields) as $required )

            // Payer
            if ( ! isset( $data[$required]) || ! $data[$required]){

                $this->_is_valid = false;

                $this->_errors[$required] = (string) new Validation_Error(Validation_Error::REQUIRED,$this->_lang);

            }

        if ( ! $this->_is_valid ) return;

        // Validate email addresses
        if ( ! $this->validate_emails( $data ))

            return;

        // Validate card
        $card  = new Credit_Card($data);

        if ( ! $card->is_valid() ){

            $this->_is_valid = false;

            $this->_errors[$card->invalid_member] = $card->invalid_reason;

        }




    }


    /**
     * Validate emails
     * @param array $data
     * @return bool
     */
    private function validate_emails( array $data ){

        $result = true;

        $fields = [ 'email', 'buyer_email', 'payer_email' ];

        foreach ( $fields as $field ){

            if ( isset( $data[$field]) && filter_var($data[$field], FILTER_VALIDATE_EMAIL) === false){

                $this->_errors[$field] = "Invalid email. Correo electrónico inválido.";

                $result = false;

            }

        }


        return $result;
    }

}