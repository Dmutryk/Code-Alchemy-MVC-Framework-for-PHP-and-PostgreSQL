<?php
/**
 * Created by JetBrains PhpStorm.
 * User: davidg
 * Date: 12/26/14
 * Time: 3:18 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy\components;


use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Database\Table\Database_Table;
use Code_Alchemy\Models\Factories\Model_Factory;
use Code_Alchemy\Models\Helpers\Model_Class_Verifier;
use Code_Alchemy\helpers\plural_for;
use Code_Alchemy\models\model;

class managed_service2 extends Alchemist{

    /**
     * @var string name of table to manage
     */
    private $table_name = '';

    /**
     * @var string Service Label
     */
    private $service_label = '';

    /**
     * @var bool true if service is hidden
     */
    private $is_hidden = false;

    /**
     * @var bool True to send firebug output
     */
    private $firebug = false;

    /**
     * @param $table_name
     * @param bool $is_hidden
     * @param array $options to override defaults
     */
    public function __construct(
        $table_name,
        $is_hidden = false,
        $options = array()
    ){

        $this->table_name = $table_name;

        $this->service_label = $this->service_label();

        $this->is_hidden = $is_hidden;

        // Set options
        foreach ( $options as $name =>$value )

            if ( property_exists($this,$name))

                $this->$name = $value;


    }

    /**
     * @return string Service Label, derived from tablename
     */
    private function service_label(){

        $label = '';

        $words = explode('_',$this->table_name);

        $count = count($words);

        foreach ( $words as $word ){

            $label .= (strlen($label))?' '.ucfirst($word):ucfirst($word);

        }


        $plural_for = new plural_for($label);

        $label = $plural_for->word;

        return $label;

    }

    /**
     * @return array representation
     */
    public function as_array(){

        $arr = array(
            'table_name' => $this->table_name,
            'service_label' => $this->service_label,
            'bootstrap_class'=>$this->bootstrap_class(),
            'count_models'=>$this->count_models(),
            'is_hidden'=>$this->is_hidden,

            'parent_tables' => $this->parent_tables(),

            'web_director_url' => $this->web_director_url()

        );
        return $arr;

    }

    /**
     * @return string Web Director URL
     */
    private function web_director_url(){

        return "/parnassus/list_of/$this->table_name/1/25";
    }

    /**
     * @return array of parent tables
     */
    private function parent_tables(){

        $parent_tables = array();

        $table = new Database_Table( $this->table_name );

        $foreign_keys = $table->foreign_keys(true);

        foreach ( $foreign_keys as $key ){

            if ( preg_match('/([a-zA-Z0-9_]+)_id$/',$key,$hits))

                $parent_tables[] = $hits[1];

        }

        return ($parent_tables);

    }

    /**
     * @return int count of models
     */
    private function count_models(){

        $count = 0;

        $model_class = (string) new \Code_Alchemy\Models\Helpers\Model_Class_For( $this->table_name );

        if ( $model_class ){

            $model = $this->get_factory(

                (new Model_Class_Verifier($model_class))->is_dynamic_model()?

                    (new $model_class($this->table_name))->get_factory():

                        $model_class::factory()

            );

            if ( $this->firebug) \FB::info("Managed Service 2: counting instances of $model_class");

            $count = $model->count_all( $model->is_safe_deleteable()? "is_deleted='0'": '');

        }

        return $count;
    }

    /**
     * @param Model_Factory $model
     * @return Model_Factory
     */
    private function get_factory( Model_Factory $model ){ return $model; }


    /**
     * @param \xo_model $model
     * @return \xo_model
     */
    private function get_model( \xo_model $model ){ return $model; }

    /**
     * @return string bootstrap class
     */
    private function bootstrap_class(){

        static $counter = 0;

        $classes = array('primary','success','info','warning','danger');

        $counter = $counter +1;

        if ( $counter >= count($classes)) $counter = 0;

        return $classes[$counter];

    }

}