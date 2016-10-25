<?php

namespace Code_Alchemy\Themes\Helpers;


use Code_Alchemy\Arrays\Filters\Filter_by_Value;
use Code_Alchemy\Arrays\Filters\Ignore_Values;
use Code_Alchemy\Arrays\Filters\Ignore_Values_Until_Matched;
use Code_Alchemy\Arrays\Filters\Remove_Empty_Values;
use Code_Alchemy\Arrays\Filters\Trim_Values;
use Code_Alchemy\Arrays\Operators\Strip_Values_Leave_Keys;
use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Filesystem\Directory_API;
use Code_Alchemy\Filesystem\Text_File_As_Lines;
use Code_Alchemy\Helpers\Theme_Name_Guess;
use Code_Alchemy\Heuristics\Insert_After_Last_Match;

/**
 * Class HEAD_Section_Normalizer
 * @package Code_Alchemy\Themes\Helpers
 *
 * Normalizes the HEAD section for a given theme, by:
 *
 * 1) Extracting the "common part" which is common for all
 * distinct HEAD fragments, into a single PHP require
 */
class HEAD_Section_Normalizer extends Array_Representable_Object{

    /**
     * This component receives no parameters upon instantiation
     */
    public function __construct(){

        $this->theme = (string) new Theme_Name_Guess();

        $this->components_root = (string) new Theme_Component_Directory( $this->theme );

        $this->head_files = (new Directory_API($this->components_root))

            ->directory_listing(true,array(),'/^head\-/');

        $this->num_head_files = count( $this->head_files );

        // Build results
        $results = array();

        // For each file
        foreach ( $this->head_files as $file ){

            foreach (

            (new Ignore_Values((new Ignore_Values_Until_Matched(

                (new Remove_Empty_Values(

                    (new Trim_Values(

                        (new Text_File_As_Lines($file))

                            ->as_array()))

                        ->as_array()))

                    ->as_array(),'/<head>/i'))

                ->as_array(),array('</head>')))->as_array()




            as $line ){

                // If not in array yet
                if ( ! isset( $results[$line]))

                    $results[$line] = 1;

                else

                    $results[$line]++;

            }

        }

        $common_head = $this->components_root."head-common.php";

        $extracted_values = (new Strip_Values_Leave_Keys(

            (new Filter_by_Value($results, $this->num_head_files)

            )->as_array()

        ))->as_array();

        // If not created yet
        if ( ! file_exists( $common_head)) {


            $this->head_written = file_put_contents($common_head,implode("\n",

                $extracted_values

                ));
        }

        // Now go through each file and strip the common values, replacing with new string
        foreach ( $this->head_files as $file ){

            file_put_contents($file,implode("\n",

                (new Insert_After_Last_Match(

                    array('<?php require_once $webroot ."/app/views/components/'.$this->theme.'/head-common.php";?>'),

                    (new Ignore_Values(

                        (new Remove_Empty_Values((new Trim_Values((new Text_File_As_Lines($file))->as_array()))->as_array()))->as_array()
                        ,$extracted_values

            ))->as_array(),array(

                'match' => '<head>'

            )))->as_array()));

        }

        shell_exec("git add $common_head");

        $this->results = $results;

    }

}