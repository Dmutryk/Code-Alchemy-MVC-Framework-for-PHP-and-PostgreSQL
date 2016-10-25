<?php


namespace Code_Alchemy\Creators;


use Code_Alchemy\Database\Database;
use Code_Alchemy\Helpers\Namespace_Guess;
use Code_Alchemy\Text_Operators\String_Values_Replacer;

class Database_Table_Creator extends Entity_Creator {

    /**
     * @var string Name of table to be created
     */
    private $name = '';

    /**
     * @var string Type of table to be created
     */
    private $type = '';


    private $database;

    /**
     * @var bool true to enable debugging
     */
    private $firebug = false;

    /**
     * @param string $name of table
     * @param string $type of table
     */
    public function __construct( $name, $type ){

        $this->name = $name;

        $this->type = $type;

        $this->database = new Database();

    }

    /**
     * @param bool $verbose
     * @return bool true if table created
     */
    public function create( $verbose = false ){

        if ( $verbose ) {

            \FB::info(get_called_class().": Creating DB table");

        }



        $result = true;

        if ( ! $this->type ){

            $result = false;

            $this->error = get_called_class().": A Template Type must be specified";

        } else {

            // Set template name
            $template_name =  $this->type. "_table.sql";

            //if ( $verbose ) echo get_called_class().": Using template $template_name\r\n\r\n";

            $intersections = isset( $this->user_options['intersects'])? explode(',',$this->user_options['intersects']):array(1,2);

            // Prepare in-string replacements
            $replacements = array(
                '/__database_name__/' => $this->database ? $this->database->name() : '',
                '/__table_name__/' => $this->name,
                '/__abbr__/' => $this->name,
                '/__model_name__/'=>@$this->user_options['references'],
                '/__model1_name__/'=>@$intersections[0],
                '/__model2_name__/'=>@$intersections[1],
                '/__model3_name__/'=>@$intersections[2],
                '/__model_name_lc__/'=>@strtolower($this->user_options['references']),
                '/\-\-PLACEHOLDER/' =>@$this->user_options['custom_fields'],
                '/__appname__/' => (string) new Namespace_Guess(),
            );

            $template_name = $this->root() . "/sql/$template_name";

            if ( file_exists($template_name ) ){

                $script = (string) new String_Values_Replacer(
                    file_get_contents($template_name),
                    $replacements
                );

                if ( $verbose ) echo ($script);

                if ( $this->firebug || $verbose) \FB::info($script);

                if ( @$this->user_options['simulate'] ){

                    if ( $verbose ) echo get_called_class().": Simulating action, nothing will be created\r\n";

                } else {


                    if ( ! $this->database->query( $script) ){

                        $result = false;

                        $this->error = $this->database->error();

                        $error = "Unable to create table `$this->name`: $this->error";

                        \FB::error($error);

                        if ( $verbose ) echo "Error:". $error."\r\n";

                    }
                }





            } else {

                \FB::warn("$template_name: No such template found");

                $result = false;

                $this->error = "$template_name: No such SQL template exists";

            }


        }

        return $result;

    }

}