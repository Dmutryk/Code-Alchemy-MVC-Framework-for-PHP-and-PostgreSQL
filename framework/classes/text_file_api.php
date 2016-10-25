<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g42
 * Date: 4/01/14
 * Time: 10:45 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy;


class text_file_api  {

    /**
     * @var mixed|string data sent back from client
     */
    private $data = '';

    /**
     * @var string the filename associated with this operation
     */
    private $filename = '';

    /**
     * @var string method
     */
    private $method = 'GET';

    public function __construct( $options = array()){

        $input = file_get_contents('php://input');

        $this->data = json_decode($input, true);

        $this->filename = $_REQUEST['filename'];

        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] );



    }

    /**
     * @return text_file_api_result result of processing
     */
    public function process_request(){

        $result = new text_file_api_result();

        $result->filename = $this->filename;
        $result->method = $this->method;

        switch ( $this->method ){

            /**
             * Write changes to the file
             */
            case 'POST':
            case 'PUT':

            $contents = $this->data['contents'];

            if ( file_exists( $this->filename )){

                $fh = fopen($this->filename,"w");

                $fresult = fputs($fh,$contents);

                fclose($fh);

                $result->contents = $contents;

                $result->save_result = $fresult;

            }


            break;

            /**
             * Fetch the file
             */
            case 'GET':

                $contents = '';

                if ( file_exists( $this->filename )){

                    $fh = fopen($this->filename,"r");

                    while ( $str = fgets($fh)){

                        $contents .= $str;
                    }

                    fclose($fh);

                    $result->contents = $contents;
                }
        }


        return $result;
    }
}