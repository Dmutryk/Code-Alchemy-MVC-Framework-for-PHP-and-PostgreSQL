<?php


namespace Code_Alchemy\Models\Helpers;


use Code_Alchemy\Core\Array_Representable_Object;
use Code_Alchemy\Core\Configuration_File;
use Code_Alchemy\helpers\plural_for;
use Code_Alchemy\Models\As_Array_Pre_Filters\Shopping_Cart_Pre_Filter;
use Code_Alchemy\Models\Attributes\Editable_Model_Name;
use Code_Alchemy\Models\Components\Model_Settings;
use Code_Alchemy\Models\Factories\Model_Factory;
use Code_Alchemy\Models\Key_Column;
use Code_Alchemy\Models\Model;
use Code_Alchemy\Models\Model_Configuration;
use Code_Alchemy\Models\Triggers\Model_Trigger;
use Code_Alchemy\Multi_Language_Support\MLS_Manager;
use Code_Alchemy\Multi_Language_Support\Resolvers\Resolve_Scope_Language_Members;
use Code_Alchemy\Multimedia\Video\Youtube_Video_Thumbnail;

/**
 * Class As_Array_Pre_Filter
 * @package Code_Alchemy\Models\Helpers
 *
 * The job of the Pre Filter is to filter through all array members
 * of the Model, and make adjustments based on known rules.
 *
 * For example:
 *
 * 1. When an image filename is present, automatically add a member
 * with the full image url, for display on a web page
 */
class As_Array_Pre_Filter extends Array_Representable_Object {

    public function __construct( array $members, $model_name, array $model_configuration ){

        // Get Model Settings
        $oModelSettings = new Model_Settings($model_name);

        // Get the keys... we'll need 'em!'descri
        $keys = array_keys( $members );

        // Auto concatenate first and last name
        if ( in_array('first_name',$keys) && in_array( 'last_name',$keys))

            $members['full_name'] = $members['first_name']. ' '.$members['last_name'];

            //if ( $this->is_development()) \FB::info($members);

       foreach ( $members as $name=>$value){


            // For youtube videos...
            if ( $name == 'unique_youtube_identity' ){

                // Add thumbnail
                $members['youtube_video_thumbnail'] = '<img src="'.(string) new Youtube_Video_Thumbnail( $value ).'">"';

            }



            // For fontawesome representations
            if ( preg_match('/fontawesome_(.+)/',$name,$matches))

                $members[$name.'_html'] = "<i class='fa ".$value."'></i>";




            if ( $name == 'website_image_affixed' )

            {

                $members['image_title'] = @$value['title'];

                $members['image_filename_url'] = $value['image_filename_url'];

            }

            if ( $name == 'description')

               if(is_string($value))
                $members['description_notags'] = strip_tags($value);


            // For numerics
            if ( is_numeric( $value ))

                $members[ $name ] = $value + 0;


            // If Boolean, show accordingly
            if ( (new Is_Boolean_Member($name,$value))->bool_value() )

                $members[ $name ] = (bool) $value;


            // For image filenames
            if ( preg_match('/(.+_)?image_filename$/',$name,$hits)){

                // Add the image filename URL
                ////$members['image_filename_url'] = "/images/$model_name/$value";
                $members['image_filename_url'] = "/images/website_image/$value";

                // And external
                ////$members['external_image_filename_url'] = "http://".$_SERVER['HTTP_HOST']. "/images/$model_name/$value";
                $members['external_image_filename_url'] = "http://".$_SERVER['HTTP_HOST']. "/images/website_image/$value";



            }

            // Ids should be Integer
            if ( ($name =='id' || (preg_match('/(.+)_id$/',$name) && ! in_array($name,array('unique_object_id', 'facebook_id','youtube_id'))) ) && $value && is_numeric($value))

                $members[$name] = (int)$value;

           if (

           (
               ! ( $oModelSettings->auto_parse_relationships === false

               ) &&

               ! ( @(new Configuration_File())->find('models')['auto_parse_relationships'] === false)
           )
                &&
           preg_match('/(.+)_id$/',$name,$hits)



           && ( ! in_array($name,array('reference_model_id',

               'unique_object_id', 'sortable_id','facebook_id')))

                && ! preg_match('/(.+)sortable_id/',$name)

               // Exclude own Key Column
               && $name != (string) new Key_Column($model_name)


           ){

               //if ( $this->is_development() ) \FB::info(get_called_class().": Parsing relationship for $name");

               $model_configuration_for_related = (new Model_Configuration())->model_for($hits[1]);


               // For foreign ids
               $model_name2 = $hits[1];

               $temp = $members;

                // Add the reference column to the array
                $offset = array_search($name, $keys);

                $members = array_merge(

                    // part up to this id
                    array_slice($temp,0, ($offset)),

                    // add in the new member
                    array(
                        $name => (int)$value,

                        $model_name2 => (new Model($model_name2))->find(new Key_Column($model_name2). "='".$value."'")->reference_value()
                    ),

                    // And the part after that one
                    array_slice($temp, ($offset))
                );


            } else {

             // \FB::info(get_called_class().": Unable to get related object name for $name");

           }



        }

        // Should affix referenced by ?
        if ( $oModelSettings->affix_referenced_by )

            $members = (new Referenced_By_Models($model_name,$oModelSettings,$members))

                ->as_array();


        // if we should affix references
        if ( isset( $model_configuration['affix_references']) && $model_configuration['affix_references'])

            // For each one
            foreach ( $model_configuration['references'] as $referenced ){

                $members[ $referenced."_affixed" ] = (new Model($referenced))

                    ->find(new Key_Column($referenced)."='".$members[(string) new Referencee_Key_Column($referenced)]."'")->as_array();

                // Automatically promote image filename
                if ( ! isset( $members['image_filename_url']) && $referenced == 'website_image'){

                    $members['image_filename_url'] = $members[$referenced."_affixed"]['image_filename_url'];

                    $members['external_image_filename_url'] = $members[$referenced."_affixed"]['external_image_filename_url'];

                }


            }


        // For shopping Cart
        if ( $model_name == 'shopping_cart')

            $members = (new Shopping_Cart_Pre_Filter($members))->as_array();





        global $code_alchemy_page_start_time;

        $editable_model_name = (string) new Editable_Model_Name($model_name);

        if ( ! ( @(new Configuration_File())->find('models')['attach-meta-data']) === false)

            // Add a signature for Code-Alchemy data
            $members['_code_alchemy'] = array(

                'model'=> $model_name,

            'manage_url' => 'http://'.$_SERVER['HTTP_HOST']."/parnassus/models/$editable_model_name/".$members[(string)(new Key_Column($model_name))]."/edit",


            'time_elapsed' =>  number_format(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"],4)."s"

            );


        // MLS support
        if ( (new MLS_Manager())->is_enabled() )

            $members = (new Resolve_Scope_Language_Members($members))->as_array();

        $this->array_values = $members;

        //if ( $this->is_development() ) \FB::info($this->array_values);

    }

}
