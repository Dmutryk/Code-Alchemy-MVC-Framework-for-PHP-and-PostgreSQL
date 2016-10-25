<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/18/16
 * Time: 5:56 PM
 */

namespace Code_Alchemy\Validation;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class Validation_Error
 * @package Code_Alchemy\Validation
 *
 * Translates a validation code into a validation error
 */
class Validation_Error extends Stringable_Object{

    const REQUIRED = 1;

    /**
     * @var array of errors
     */
    private $errors = array(

        self::REQUIRED => array(

            'en' => 'This field is required',

            'es' => 'Este campo es obligatorio'

        )
    );

    /**
     * Validation_Error constructor.
     * @param $error_code
     * @param string $lang
     */
    public function __construct( $error_code, $lang = 'en' ) {

        $this->string_representation = (string) @$this->errors[$error_code][$lang];

    }

}