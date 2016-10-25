<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 1/27/16
 * Time: 12:22 PM
 */

namespace Code_Alchemy\Creators;
use Code_Alchemy\Database\Database;
use Code_Alchemy\Database\Postgres\Filters\Database_Table_Template_Name;
use Code_Alchemy\Database\Table\Database_Table;
use Code_Alchemy\Models\Model_Configuration;


/**
 * Class Server_Model_Creator
 * @package Code_Alchemy\Creators
 *
 * Creates a new Server Model
 */
class Server_Model_Creator extends Entity_Creator{

    /**
     * @var string Model Name
     */
    private $model_name = '';

    /**
     * @var string Template Name
     */
    private $template_name = '';

    /**
     * Server_Model_Creator constructor.
     * @param $model_name
     * @param $template_name
     */
    public function __construct( $model_name, $template_name ) {

        $this->model_name = $model_name;

        // Normalize Template name for use with PostgreSQL
        $this->template_name = (string) new Database_Table_Template_Name($template_name);

    }

    /**
     * @param bool $verbose
     * @return bool
     */
    public function create($verbose = false) {

        if ( $verbose ) {

            echo get_called_class().": Creating new Server Model $this->model_name using template $this->template_name.  Options appear next\r\n";

        }



        // Step One, create table
        $table_creator = new Database_Table_Creator($this->model_name,$this->template_name);

        // Pass options through
        $table_creator->set_options( $this->get_options());

        if ( ! $table_creator->create($verbose) ){

            // Bubble error
            $this->error = $table_creator->error;

            return false;

        }

        if ( @$this->user_options['simulate']) {

            if ( $verbose ) echo get_called_class()." Simulating creation, so configuration will not be changed.\r\n";

        } else {
            // Step 2 Add table to list in models.json
            $model_config = new Model_Configuration();



            $model_config->add_table_or_view($this->model_name);

            // Step 3: Add config to models.json
            if ( ! $model_config->add_model_from_table(new Database_Table($this->model_name) )){

                $this->error = "Unable to setup Model in models.json: ". $model_config->error();

                return false;
            }

            // Step 4: Get model columns
            (new Database())->get_field_names($this->model_name);


        }

        return true;
    }
}