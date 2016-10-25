<?php


namespace Code_Alchemy\Builders;


use Code_Alchemy\Core\Managed_Component;
use Code_Alchemy\Creators\Database_Table_Creator;
use Code_Alchemy\Creators\Server_Model_Creator;
use Code_Alchemy\Database\Table\Database_Table;
use Code_Alchemy\Filesystem\Directory_Creator;
use Code_Alchemy\Models\Model_Configuration;

class Component_Builder extends Managed_Component{

    /**
     * @var array of Database tables to build,
     * each specifies name and type
     */
    private $database_tables = array();

    /**
     * @var array of Server Models to build,
     * each specifies name and template
     */
    private $server_models = array();

    /**
     * @var array of directories to create, each with
     * required permissions flag
     */
    private $directories = array();


    /**
     * @param array $database_tables to build
     */
    protected function add_database_tables( array $database_tables ){

        foreach ( $database_tables as $name=>$type)

            $this->database_tables[$name] = $type;

    }

    /**
     * @param array $models to add
     */
    protected function add_server_models( array $models ){

        $config = new Model_Configuration();

        foreach ( $models as $name => $type ){

            (new Server_Model_Creator($name,$type))

                ->set_options($this->get_options())

                ->create( true );
        }

    }

    /**
     * @param array $directories to create
     */
    protected function add_directories( array $directories){

        foreach ( $directories as $name=>$perms)

            $this->directories[$name] = $perms;
    }

    /**
     * Build a Component
     * @param bool $verbose
     * @return bool true if component was built successfully
     */
    public function build( $verbose = false ){

        $result = true;

        // Build each table
        foreach ( $this->database_tables as $name=>$type){

            $creator = new Database_Table_Creator($name,$type);

            $creator->set_options( $this->user_options, get_called_class() );

            $result &= $creator->create(false);

            if ( ! $result ) $this->error .= $creator->error.". ";

            else {

                if ( $verbose ) echo "\t$name: This database table was successfully created\r\n";

            }

        }

        // Create directories as necessary
        foreach ( $this->directories as $name =>$perms){

            $creator = new Directory_Creator($name,$perms);

            $creator->set_options( $this->user_options, get_called_class() );

            $result &= $creator->create($verbose);

            if ( ! $result ) $this->error .= $creator->error.". ";


        }

        return $result;

    }


}