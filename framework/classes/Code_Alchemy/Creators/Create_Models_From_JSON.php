<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 4/27/16
 * Time: 4:10 PM
 */

namespace Code_Alchemy\Creators;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Creators\Helpers\Custom_Fields_SQL_String;
use Code_Alchemy\Creators\Helpers\Model_Design_JSON;
use Code_Alchemy\JSON\JSON_File;

/**
 * Class Create_Models_From_JSON
 * @package Code_Alchemy\Creators
 *
 * Create a bunch of Models from a JSON specification
 */
class Create_Models_From_JSON extends Entity_Creator {

    public function __construct() {

    }

    public function create($verbose = false) {

        if ( $verbose) echo get_called_class().": Ready to create models from ".$this->filename()."\r\n";

        var_dump($this->user_options);

        // Receive JSON
        $spec = $this->specification();

        foreach ( $spec->models() as $model_spec ){

            $creator = new Server_Model_Creator( $model_spec->name, $model_spec->template );

            $creator->set_options([

                // Simulate if required
                'simulate' => @$this->user_options['simulate'] == 'true' ? true: false,

                'intersects' => @$model_spec->intersects,

                'references' => @$model_spec->references,

                'custom_fields' => @$model_spec->custom_fields ?

                    (string) new Custom_Fields_SQL_String($model_spec->custom_fields,@$spec->mls_settings()->languages)

                    :'',


            ]);

            $creator->create( $verbose );
        }

        if ( $verbose) echo get_called_class().": All done.\r\n";

    }

    /**
     * @return string filename
     */
    private function filename(){

        return (string) $this->get_options()['filename'];

    }

    /**
     * @return Model_Design_JSON
     */
    private function specification(){

        return new Model_Design_JSON( $this->filename() );

    }

}