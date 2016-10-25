<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/4/15
 * Time: 9:50 AM
 */

namespace Code_Alchemy\Database\SQL\Helpers;


use Code_Alchemy\Core\Stringable_Object;

/**
 * Class SQL_Conditions_Splitter
 * @package Code_Alchemy\Database\SQL\Helpers
 *
 * Set the text token splitter to split between the subject of the SQL statement
 * and the object
 *
 * For example:
 *
 * `name` NOT IN ('David','Sara')
 *
 * In this case, `name` is the Subject, NOT IN is the splitter, and the array of values
 * is the object.
 *
 * This class returns 'NOT IN' in the above case.
 */
class SQL_Conditions_Splitter extends Stringable_Object {

    private $rules = array(

        // Has an NOT IN (...) Clause
        array(

            // The regex to match
            'regex' => "/([a-z|_|0-9]+)\s+NOT\s+IN\s+\((\;?[0-9]+)+\)/",

            'splitter'=> 'NOT IN',

            // Replacements in SQL string
            'replace' => array(

                'regex' =>'/;/',

                'replace_with' => ','
            )
        ),

        // Has an IN (...) Clause
        array(

            // The regex to match
            'regex' => "/([a-z|_|0-9]+)\s+IN\s+\((\;?[0-9]+)+\)/",

            'splitter'=> 'IN',

            // Replacements in SQL string
            'replace' => array(

                'regex' =>'/;/',

                'replace_with' => ','
            )
        ),

        // Checks if IS NULL
        array(

            'regex' => '/\sIS\sNULL/',

            'splitter' => 'IS'
        ),

        // Using RLIKE
        array(

            'regex' => '/\s+RLIKE\s+/',

            'splitter' => 'RLIKE'

        ),

        // Using LIKE
        array(

            'regex' => '/\s+LIKE\s+/',

            'splitter' => 'LIKE'

        ),


        // Not Equal TO
        array(

            'regex' => '/!=/',

            'splitter' => '!='

        ),

        // Less than equal to
        array(

            'regex' => '/<=/',

            'splitter' => '<='

        ),


        // Less than
        array(

            'regex' => '/</',

            'splitter' => '<'

        ),

        // greater than equal to
        array(

            'regex' => '/>=/',

            'splitter' => '>='

        ),


        // greater than
        array(

            'regex' => '/>/',

            'splitter' => '>'

        ),





    );

    /**
     * @param string $sql_clause to check
     */
    public function __construct( &$sql_clause ){

        // By default
        $splitter = '=';

        // For each rule
        foreach ( $this->rules as $rule ){

            //if ( $this->is_development() ) \FB::info(get_called_class().": Checking Rule".new \xo_array($rule)." on splitter $sql_clause");

            // if matched the rule
            if ( preg_match($rule['regex'],$sql_clause,$hits)){

                //if ( $this->is_development() ) \FB::info(get_called_class().": Matched Regex ".$rule['regex']." on splitter $sql_clause");

                // Set splitter accordingly
                $splitter = $rule['splitter'];

                // If replacement indicated
                if ( isset( $rule['replace']))

                    // Do it
                    $sql_clause = preg_replace($rule['replace']['regex'],$rule['replace']['replace_with'],$sql_clause);

                // Stop right there, son!
                break;

            }

        }



        // Pass value back
        $this->string_representation = $splitter;
    }

}