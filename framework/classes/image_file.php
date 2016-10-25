<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David Greenberg
 * Date: 7/04/14
 * Time: 08:58 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\apis;


use Code_Alchemy\restful_result;

class image_file {

    /**
     * @var string image directory where images are stored
     */
    private $image_directory = '/';

    /**
     * @param array $options to use when instantiating API
     */
    public function __construct( $options = array() ){

        $this->image_directory = isset($options['image_directory'])?$options['image_directory']:'/';

    }

    /**
     * @return restful_result of processing the REQUEST
     */
    public function process_request(){

        $data = json_decode(file_get_contents('php://input'),true);

        $method = strtoupper($_SERVER['REQUEST_METHOD'] );

        $filename = \REQUEST_URI::create()->part(3);

        $success = true;

        $error = '';

        switch( $method){

            case 'DELETE':

                $file = $this->image_directory.$filename;

                if ( file_exists($file))
                    $success = unlink($file);
                else {
                    $success = false;
                    $error = 'Image file not found';
                }

        }

        $result = new restful_result(array(
            'method'=>$method,
            'image_directory'=>$this->image_directory,
            'filename'=>$filename,
            'result'=>$success?'success':'error',
            'error'=>$error
        ));

        return $result;

    }

}