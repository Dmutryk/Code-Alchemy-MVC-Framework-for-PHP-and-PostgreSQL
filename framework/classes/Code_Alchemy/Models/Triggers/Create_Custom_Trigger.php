<?php


namespace Code_Alchemy\Models\Triggers;


use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Filesystem\Utilities\Smart_File_Copier;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;
use Code_Alchemy\Helpers\Namespace_Guess;

class Create_Custom_Trigger {
    
    public function __construct( $trigger_class, $trigger_type, $model_name ){

        global $webapp_location;

        // Get ready to copy it
        $camelCase_Name = new CamelCase_Name($trigger_type, '_');

        $copier = new Smart_File_Copier(

            new Code_Alchemy_Root_Path()."/templates/classes/". $camelCase_Name .".php",

            "$webapp_location/app/classes/".new Namespace_Guess()."/Models/Triggers/".$camelCase_Name."/". new CamelCase_Name($model_name,'_').".php",

            array(
                '/__namespace__/'=>(string) new Namespace_Guess(),
                '/__classname__/'=>(string) new CamelCase_Name($model_name,'_')
            ),

            false
        );

        if ( $copier->copy() )

            \FB::info("$trigger_class: A Custom Trigger $camelCase_Name was added for Model $model_name");

        else

            \FB::warn($copier->error);

    }

}