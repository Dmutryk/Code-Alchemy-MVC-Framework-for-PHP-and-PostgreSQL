<?php
namespace Code_Alchemy\helpers;

class Encryption {

    const CYPHER = MCRYPT_BLOWFISH;
    const MODE   = MCRYPT_MODE_CBC;

    private $key = '';

    private $result = '';

    public function __construct( $text, $action, $key ){

        $this->key = $key;

        $this->$action( $text );

    }

    public function encrypt($plaintext)
    {
        $td = mcrypt_module_open(self::CYPHER, '', self::MODE, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $this->key, $iv);
        $crypttext = mcrypt_generic($td, $plaintext);
        mcrypt_generic_deinit($td);
        $this->result =  (string)base64_encode($iv.$crypttext);
    }

    public function decrypt($crypttext)
    {
        $crypttext = base64_decode($crypttext);
        $plaintext = '';
        $td        = mcrypt_module_open(self::CYPHER, '', self::MODE, '');
        $ivsize    = mcrypt_enc_get_iv_size($td);
        $iv        = substr($crypttext, 0, $ivsize);
        $crypttext = substr($crypttext, $ivsize);
        if ($iv)
        {
            mcrypt_generic_init($td, $this->key, $iv);
            $plaintext = mdecrypt_generic($td, $crypttext);
        }
        $this->result =  (string)trim($plaintext);
    }

    public function __toString(){ return $this->result; }
}

?>