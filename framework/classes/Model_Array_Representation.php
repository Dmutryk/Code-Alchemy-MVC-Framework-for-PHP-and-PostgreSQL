<?php


namespace Code_Alchemy\representors;
use Code_Alchemy\Core\CamelCase_Name;
use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\helpers\Model_Simple_Name;
use Code_Alchemy\Models\Helpers\Model_Creator;
use Code_Alchemy\models\model_wrapper;
use Code_Alchemy\tools\code_tag;

/**
 * Class Model_Array_Representation comprises a Representor,
 * that allows a Model to be displayed as an Array
 *
 * @package parnassus\representors
 */
class Model_Array_Representation {

    /**
     * @var array Representation of Object
     */
    private $representation = array();

    /**
     * @var bool true to debug
     */
    private $debug = false;

    public function __construct(
        \business_object $model,
        $called_class,
        $prefix = '',
        array $exclusions = array(),
        $associative = true

    ){

        $tag = new code_tag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);

        $this->debug = $this->debug || Code_Alchemy_Framework::instance()->debug;

        if ( $this->debug ) \FB::info("$tag->firebug_format: Getting array representation for $called_class id $model->id");

        // for each model column
        $columns = method_exists($model,'columns')?

            $model->columns():

                $model->source()->columns();

        foreach($columns as $col){

            // Seo name
            if ( $col === 'seo_name' && ( $model->seo_name != $model->seo_name())){

                $model->seo_name = $model->seo_name();

                $model->save();
            }

            // normalize value as needed
            $value = $this->normalize_value( $model->$col, $col );

            // For created date
            if ( $col =='created_date')

                $this->representation[$prefix.$col.'_ago'] = (string) new \human_time(strtotime($model->$col));

            // FOr fontawesome representations
            if ( preg_match('/fontawesome_(.+)/',$col,$matches)){

                $this->representation[$prefix.$col.'_html'] =
                    "<i class='fa ".$value."'></i>";

            }

            // if this is a an id, or a _by member
            if ( preg_match( '/([a-zA-Z_]+)_id/',$col,$hits) ||

                preg_match( '/([a-zA-Z_]+)_by/',$col,$hits2)){

                // Get the method to call the related member
                $method = count( $hits) ? $hits[1]  : @$hits2[1]."_by";

                // Alternate method

                $alt_method = (string) new CamelCase_Name($method,'_');

                // Get the related model
                $related_model = $method ? $model->$method():null;

                if ( ! $related_model)

                    $related_model = $alt_method ? $model->$alt_method():null;



                // If it's an object
                if ( is_object($related_model) && $related_model->exists ){

                    // Get its reference column
                    $ref = (get_class($related_model)!= 'Code_Alchemy\Models\Dynamic_Model')?

                        $related_model->source()->reference_column():

                        $related_model->reference_column()

                    ;

                    // If it has one
                    if ( $ref ){

                        $val2 = $related_model->$ref() ? $related_model->$ref():$related_model->$ref;

                        $name = $prefix . $method . "_$ref";

                        $this->representation[$name] = $val2;

                    }
                } else {

                    if ( $this->debug ) \FB::info("$tag->firebug_format: No related model for $col on this Model");


                }

            }

            // if not excluded
            if ( ! in_array($col,$exclusions)){
                if ( $associative)
                    $this->representation[ $prefix.$col]= $value;
                else
                    $this->representation[]= $value;
            }

            // For email templates provide an editable version

            if ( preg_match( '/email_template/',$called_class)){

                if ( $col == 'text')

                    $this->representation[$prefix."editable_$col"] = htmlentities($value);

                if ( $col == 'key')

                    $this->representation[$prefix."template_$col"] = $value;

            }

            // for images, automatically include URL if defined by child
            if ( preg_match('/([a-zA-Z_]+)_filename/',$col) ){

                $method = "$col".'_url';

                if ( method_exists($model,$method))

                    $this->representation[$prefix.$method] = $model->$method();

            }

            // For event date
            if ( $col =='event_date'){

                $this->representation[$prefix.'event_date_day'] = date('d',strtotime($model->$col));

                $this->representation[$prefix.'event_date_month'] = date('M',strtotime($model->$col));

            }


        }
        // magic members, too, please
        foreach ($model->as_array_magic_members as $member)
        {
            if( $associative)
                $this->representation[$prefix.$member] = $model->$member;
            else
                $this->representation[] = $model->$member;
        }

        /***
         * Get the SEO Name, if set
         *
         */
        $reference_column = method_exists($model,'reference_column')?

            $model->reference_column():

                $model->source()->reference_column();


        // Automatically add SEO name
        if ( ! isset( $model->seo_name ) && $model->$reference_column ){

        $this->representation[$prefix."seo_name"] = (string) new \Code_Alchemy\components\seo_name($model->$reference_column);

        }

        // Get reference value
        $ref_val = $model->$reference_column()?$model->$reference_column(): $model->$reference_column;

        // If set
        if ( $ref_val)

        {
        $this->representation[$prefix.$reference_column ] = $ref_val;
        }

        // If we have a record creator
        if ( $model->created_by ){

            $creator = new Model_Creator( (int) $model->created_by );

            $refcol = $creator->reference_column();

            if ( ! in_array("created_by_$refcol",$exclusions))

                $this->representation[$prefix."created_by_$refcol"] = $creator->reference_name();

            if ( ! in_array("created_by_seo_$refcol",$exclusions))

                $this->representation[$prefix."created_by_seo_$refcol"] = (string) new \Code_Alchemy\components\seo_name($creator->reference_name());

        }

        // Add Code_Alchemy link
        $this->representation[$prefix.'parnassus_direct_link'] =

           'http://'. @$_SERVER['HTTP_HOST']."/parnassus/models/".new Model_Simple_Name($called_class)."/".$model->id."/edit";


    }


    /**
     * Normalize a value
     * @param $value
     * @param $column
     * @return int|string
     */
    private function normalize_value( $value, $column ){

        $value = (string)$value;

        if ( $column=='id' && is_numeric($value)) $value = (int)$value;

        // For boolean values
        if ( preg_match( '/is_([a-zA-Z_]+)/',$column) )

            $value = (bool) $value;

        return $value;

    }

    /**

     * @return array representation of Model
     */
    public function as_array(){

        return $this->representation;

    }

}